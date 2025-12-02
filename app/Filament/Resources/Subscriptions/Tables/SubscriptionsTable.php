<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use App\Mail\CoachAssignmentNotification;
use App\Mail\SubscriptionApprovalNotification;
use App\Models\CoachHandle;
use App\Models\Subscription;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        $query = Subscription::query();
        if(Auth::user()->roles()->where('name', 'member')->exists())
        {
           $query->where('user_id', Auth::user()->id);
        }
        return $table
            ->query($query)
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('fitnessOffer.name')
                    ->searchable(),
                TextColumn::make('subscriptionTransactions.reference_no')
                    ->url(fn ($state) => '/app/subscription-transactions?search=' . $state)
                    ->color('primary')
                    ->searchable(),
                // SelectColumn::make('coach_id')
                //     ->label('Assigned Coach')
                //     ->options(User::where('role', 'coach')->where('status', 'active')->pluck('name', 'id'))
                //     ->searchable()
                //     ->disabled(fn ($record) => ($record->status === 'active' || $record->status === 'rejected' || $record->status === 'expired' || $record->status === 'inactive'))
                //     ->afterStateUpdated(function ($state, $record) {
                //         if ($state) {
                //             $record->update([
                //                 'status' => 'active'
                //             ]);
                //             CoachHandle::create([
                //                 'coach_id' => $state,
                //                 'member_id' => $record->user_id,
                //                 'fitnessOffer_id' => $record->fitness_offer_id,
                //                 'start_at' => $record->start_date,
                //                 'end_at' => $record->end_date,
                //             ]);
                //         }
                //     }),
                TextColumn::make('coach_id')
                    ->formatStateUsing(fn ($state) => User::where('id', $state)->first()?->name)
                    ->label('Assigned Coach'),
                TextColumn::make('status')
                   ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'expired' => 'danger',
                        'rejected' => 'danger',
                        'inactive' => 'gray',
                    }),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                // IconColumn::make('is_extendable')
                //     ->boolean(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->hidden(fn ($record) => (Auth::user()->role != 'admin' || $record->status === 'active' || $record->status === 'rejected' || $record->status === 'expired' || $record->status === 'inactive'))
                    ->color('success')
                    ->requiresConfirmation()
                    ->schema([
                        Select::make('coach_id')
                            ->label('Assign Coach')
                            ->options(User::where('role', 'coach')->where('status', 'active')->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function ($data, $record) {
                        $record->update([
                            'status' => 'active',
                            'coach_id' => $data['coach_id']
                        ]);
                        CoachHandle::create([
                            'coach_id' => $data['coach_id'],
                            'member_id' => $record->user_id,
                            'fitnessOffer_id' => $record->fitness_offer_id,
                            'start_at' => $record->start_date,
                            'end_at' => $record->end_date,
                        ]);
                        Notification::make()
                            ->title('Subscription approved successfully')
                            ->success()
                            ->send();

                        if ($record->user->email) {
                            Mail::to($record->user->email)->send(new SubscriptionApprovalNotification($record->user, $record));
                        }

                        $coach = User::find($data['coach_id']);
                        if ($coach && $coach->email) {
                            Mail::to($coach->email)->send(new CoachAssignmentNotification($coach, $record->user, $record));
                        }
                    }),
            ])
            ->toolbarActions([
                Action::make('sub')
                    ->hidden(fn () => Auth::user()->role != 'member')
                    ->label('Add Subscription')
                    ->icon('heroicon-o-plus')
                    ->url('/#pricingSection'),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
