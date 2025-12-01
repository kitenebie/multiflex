<?php

namespace App\Livewire\Admin\Members;

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

class Index extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('role', 'member'))
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
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->form([
                        Grid::make(2)
                            ->schema([
                        TextInput::make('name')->disabled(),
                        TextInput::make('email')->disabled(),
                        Select::make('role')->options(['member' => 'Member', 'coach' => 'Coach', 'admin' => 'Admin'])->disabled(),
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
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form([
                        Grid::make(2)
                            ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->email()->required()->unique(table: 'users', column: 'email', ignoreRecord: true),
                        Select::make('role')->options(['member' => 'Member', 'coach' => 'Coach', 'admin' => 'Admin']),
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
                    ->label('Approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-users')
                    ->color('success')
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                        Notification::make()
                            ->title('Approved')
                            ->body('The member has been approved successfully.')
                            ->success()
                            ->send();
                        $this->dispatch('refresh');
                    }),
                    DeleteAction::make()
            ])
            ->toolbarActions([
                Action::make('create_member')
                    ->label('Create Member')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->email()->required()->unique(table: 'users', column: 'email'),
                                TextInput::make('password')->password()->required()->confirmed()->minLength(8),
                                TextInput::make('password_confirmation')->password()->required(),
                                Select::make('role')->options(['member' => 'Member'])->default('member')->disabled(),
                                Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->default('active'),
                                Textarea::make('address'),
                                TextInput::make('age')->numeric(),
                                Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),
                            ])
                            ->columns(2)
                    ])
                    ->action(function (array $data) {
                        $data['password'] = bcrypt($data['password']);
                        $user = User::create($data);
                        $user->update(['qr_code' => bcrypt($user->id)]);
                        $this->dispatch('refresh');
                    }),
                    
            ]);
    }
    public function render()
    {
        return view('livewire.admin.members.index');
    }
}
