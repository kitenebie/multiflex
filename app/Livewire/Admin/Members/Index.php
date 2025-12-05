<?php

namespace App\Livewire\Admin\Members;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionTransaction;
use App\Models\FitnessOffer;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use App\Services\UserApprovalMailService;
use Ymsoft\FilamentTablePresets\Filament\Actions\ManageTablePresetAction;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class Index extends Component implements HasActions, HasSchemas, HasTable, HasFilamentTablePresets
{
    use WithFilamentTablePresets;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    public function table(Table $table): Table
    {
        $query = User::query()->where('role', 'member');

        if (Auth::user()->role == 'coach') {
            $query->whereHas('subscriptions', function ($q) {
                $q->where('coach_id', Auth::user()->id)->where('end_date', '>', now());
            });
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('name')->searchable()->toggleable(),
                TextColumn::make('email')->searchable()->toggleable(),
                TextColumn::make('role')->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'warning' => 'pending',
                        'success' => 'active',
                        default => 'gray',
                    })->toggleable(),
                TextColumn::make('address')->searchable()->toggleable(),
                TextColumn::make('age')->toggleable(),
                TextColumn::make('gender')->toggleable(),
                TextColumn::make('subscriptions.coach.name')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(' ')
                    ->button()
                    ->tooltip('view')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')->disabled(),
                                TextInput::make('email')->disabled(),
                                Select::make('role')->options(['member' => 'Member'])->disabled(),
                                Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->disabled(),
                                Toggle::make('membership')->label('Has Membership')->disabled(),
                                Textarea::make('address')->disabled(),
                                TextInput::make('age')->numeric()->disabled(),
                                Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])->disabled(),
                                Toggle::make('is_admin')->label('Is Admin')->disabled(),
                                DateTimePicker::make('created_at')->disabled(),
                                DateTimePicker::make('updated_at')->disabled(),
                            ])
                            ->columns(2)
                    ])
                    ->fillForm(fn(User $record) => $record->toArray()),
                Action::make('edit')
                    ->hidden(Auth::user()->role == 'coach')
                    ->label(' ')
                    ->button()
                    ->tooltip('edit')->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->email()->required()->unique(table: 'users', column: 'email', ignoreRecord: true),
                                Select::make('role')->options(['member' => 'Member'])->disabled(),
                                Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive']),
                                Textarea::make('address'),
                                TextInput::make('age')->numeric(),
                                Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),
                            ])
                            ->columns(2)
                    ])
                    ->fillForm(fn(User $record) => $record->toArray())
                    ->action(function (array $data, User $record) {
                        if (empty($data['password'])) {
                            unset($data['password']);
                        } else {
                            $data['password'] = bcrypt($data['password']);
                        }
                        $record->update($data);
                        $this->dispatch('refresh');
                    }),
                Action::make('approved')
                    ->hidden(fn($record) => Auth::user()->role == 'coach' || $record->status == 'active' || $record->status == 'inactive')
                    ->label(' ')
                    ->button()
                    ->tooltip('approve')->color('success')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-users')
                    ->color('success')
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                        $mailService = new UserApprovalMailService();
                        $mailService->sendApprovalNotification($record);
                        Notification::make()
                            ->title('Approved')
                            ->body('The member has been approved successfully.')
                            ->success()
                            ->send();
                        $this->dispatch('refresh');
                    }),
                DeleteAction::make()->label('Archive')->icon('heroicon-o-archive-box-x-mark')
                    ->label(' ')
                    ->button()
                    ->tooltip('decilene')->hidden(fn($record) => Auth::user()->role == 'coach' || $record->status == 'active' || $record->status == 'inactive')
                    ->icon('heroicon-o-x-mark')
            ])
            ->toolbarActions([
                ManageTablePresetAction::make()->label(' ')
                    ->button()
                    ->tooltip('manage table'),
                Action::make('create_member')
                    ->label('Create Member')
                    ->hidden(Auth::user()->role == 'coach')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->schema([
                        // User fields
                        Grid::make()->columnSpanFull()->schema([
                            TextInput::make('name')->required(),
                            TextInput::make('email')->email()->required()->unique(table: 'users', column: 'email'),
                            TextInput::make('password')->password()->revealable()->required(),
                            TextInput::make('password_confirmation')->password()->revealable()->same('password')->required(),
                            TextInput::make('address'),
                            TextInput::make('age')
                                ->default(18)
                                ->minValue(18)->numeric(),
                            Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),

                            // Subscription fields
                            Select::make('fitness_offer_id')
                                ->label('Fitness Offer')
                                ->options(FitnessOffer::pluck('name', 'id'))
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $offer = FitnessOffer::find($state);
                                    if ($offer) {
                                        $set('amount', $offer->price);
                                        $set('end_date', now()->addDays($offer->duration_days)->toDateString());
                                    }
                                }),
                            TextInput::make('months')
                                ->label('Months')
                                ->numeric()
                                ->default(0)
                                ->minValue(1)
                                ->maxValue(120)
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $startDate = $get('start_date');
                                    if ($startDate && $state) {
                                        $set('end_date', \Carbon\Carbon::parse($startDate)->addMonths($state)->toDateString());
                                    }
                                }),
                            Select::make('coach_id')
                                ->label('Coach')
                                ->options(User::where('role', 'coach')->pluck('name', 'id'))
                                ->required(),
                            DateTimePicker::make('start_date')->default(now())->required()->live()
                                ->displayFormat('d F Y')
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $months = $get('months');
                                    if ($state && $months) {
                                        $set('end_date', \Carbon\Carbon::parse($state)->addMonths($months)->toDateString());
                                    }
                                }),
                            DateTimePicker::make('end_date')->required()
                                ->displayFormat('d F Y')
                                ->disabled(),
                            // Transaction fields
                            TextInput::make('amount')->hidden()->numeric()->required()->prefix('PHP'),
                            Select::make('payment_method')
                                ->options([
                                    'Cash' => 'Cash',
                                    'upload' => 'Upload',
                                    'others' => 'Others',
                                ])
                                ->required(),
                            TextInput::make('reference_no'),
                            FileUpload::make('proof_of_payment')->image()->directory('proofs')->columnSpanFull(),
                            DateTimePicker::make('paid_at')->default(now())->required()->hidden(),
                        ])->columns(2)
                    ])
                    ->action(function (array $data) {
                        $userData = [
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => Hash::make($data['password']),
                            'role' => 'member',
                            'status' => 'active',
                            'address' => $data['address'] ?? null,
                            'age' => $data['age'] ?? null,
                            'gender' => $data['gender'] ?? null,
                        ];
                        $user = User::create($userData);
                        $user->update(['qr_code' => bcrypt($user->id)]);

                        unset($data['months']); // Remove months as it's not a column, used only for calculation

                        $subscription = Subscription::create([
                            'user_id' => $user->id,
                            'fitness_offer_id' => $data['fitness_offer_id'],
                            'coach_id' => $data['coach_id'],
                            'status' => 'active',
                            'start_date' => $data['start_date'],
                            'end_date' => $data['end_date'],
                            'is_extendable' => $data['is_extendable'] ?? true,
                        ]);

                        SubscriptionTransaction::create([
                            'subscription_id' => $subscription->id,
                            'amount' => $data['amount'],
                            'payment_method' => $data['payment_method'],
                            'reference_no' => $data['reference_no'] ?? null,
                            'paid_at' => $data['paid_at'],
                            'proof_of_payment' => $data['proof_of_payment'] ?? null,
                        ]);

                        $this->dispatch('refresh');
                    }),

            ]);
    }
    public function render()
    {
        return view('livewire.admin.members.index');
    }

    protected function getTableHeaderActions(): array
    {
        return $this->retrieveVisiblePresetActions();
    }

    protected function handleTableFilterUpdates(): void
    {
        $this->selectedFilamentPreset = null;
    }

    public function updatedTableSort(): void
    {
        $this->selectedFilamentPreset = null;
    }
}
