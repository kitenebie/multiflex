<?php

namespace App\Livewire\Admin\Coaches;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
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
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;

class Index extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    public function table(Table $table): Table
    {
        $query = User::query()->where('role', 'member');

        if (Auth::user()->role == 'member') {
            $query->whereHas('subscriptions', function ($q) {
                $q->where('user_id', Auth::user()->id)->where('end_date', '>', now());
            });
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role'),
                TextColumn::make('status'),
                TextColumn::make('address')->searchable(),
                TextColumn::make('age'),
                TextColumn::make('gender'),
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
                SelectFilter::make('is_admin')
                    ->label('Admin')
                    ->options([
                        0 => 'No',
                        1 => 'Yes',
                    ]),
            ])
            ->recordActions([

                Action::make('view')
                    ->hidden(Auth::user()->role == 'member')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->form([
                        TextInput::make('name')->disabled(),
                        TextInput::make('email')->disabled(),
                        Select::make('role')->options(['coach' => 'Coach'])->disabled(),
                        Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->disabled(),
                        Textarea::make('address')->disabled(),
                        TextInput::make('age')->numeric()->disabled(),
                        Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])->disabled(),
                    ])
                    ->fillForm(fn(User $record) => $record->toArray()),
                Action::make('edit')
                    ->hidden(Auth::user()->role == 'member')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->email()->required()->unique(table: 'users', column: 'email', ignoreRecord: true),
                        Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive']),
                        Textarea::make('address'),
                        TextInput::make('age')->numeric(),
                        Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),
                    ])
                    ->fillForm(fn(User $record) => $record->toArray())
                    ->action(function (array $data, User $record) {
                        if (empty($data['password'])) {
                            unset($data['password']);
                        } else {
                            $data['role'] = bcrypt($data['password']);
                        }
                        $data['password'] = 'coach';
                        $record->update($data);
                        $this->dispatch('refresh');
                    }),
                Action::make('approved')
                    ->hidden(Auth::user()->role == 'member')
                    ->label('Approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-users')
                    ->color('success')
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                        Notification::make()
                            ->title('Approved')
                            ->body('The coach has been approved successfully.')
                            ->success()
                            ->send();
                        $this->dispatch('refresh');
                    }),
                DeleteAction::make()
            ])
            ->toolbarActions([
                Action::make('create_coach')
                    ->hidden(Auth::user()->role == 'member')
                    ->label('Create Coach')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->form([
                        Grid::make(2)
                            ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->email()->required()->unique(table: 'users', column: 'email'),
                        TextInput::make('password')->password()->required()->confirmed()->minLength(8)
                            ->revealable(),
                        TextInput::make('password_confirmation')->password()->required()
                            ->revealable(),
                        Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->default('active'),
                        Textarea::make('address'),
                        TextInput::make('age')->numeric(),
                        Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),
                            ])
                    ])
                    ->action(function (array $data) {
                        $data['password'] = bcrypt($data['password']);
                        $user = User::create($data);
                        $user->update(['qr_code' => hash('sha256', $user->id), 'role' => 'coach']);
                        $this->dispatch('refresh');
                    }),
            ]);
    }
    public function render()
    {
        return view('livewire.admin.coaches.index');
    }
}
