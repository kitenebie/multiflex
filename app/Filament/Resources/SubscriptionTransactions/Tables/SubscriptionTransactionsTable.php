<?php

namespace App\Filament\Resources\SubscriptionTransactions\Tables;

use App\Models\SubscriptionTransaction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Ymsoft\FilamentTablePresets\Filament\Actions\ManageTablePresetAction;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class SubscriptionTransactionsTable implements HasFilamentTablePresets
{
    use WithFilamentTablePresets;
    public static function configure(Table $table): Table
    {
        
        $query = SubscriptionTransaction::query();
        if(Auth::user()->roles()->where('name', 'member')->exists())
        {
            $query->whereHas('subscription', function($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }
        return $table
            ->query($query)
            ->columns([
                TextColumn::make('subscription.fitnessOffer.name')
                    ->searchable()->toggleable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->sortable()->toggleable(),
                TextColumn::make('payment_method')
                    ->searchable()->toggleable(),
                TextColumn::make('reference_no')
                    ->url(fn ($state) => '/app/subscriptions?search=' . $state)
                    ->color('primary')
                    ->searchable()->toggleable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable()->toggleable(),
                ImageColumn::make('proof_of_payment')
                    ->url(fn ($record) => asset('storage/' . $record->proof_of_payment))
                    ->openUrlInNewTab()
                    ->size(70)->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(' ')
                    ->button()
                    ->tooltip('view')
                    ->color('warning'),
                Action::make('view_proof')
                    ->icon('heroicon-o-eye')
                    ->label(' ')
                    ->button()
                    ->tooltip('view proof of payment')
                    ->color('info')
                    ->url(fn ($record) => asset('storage/' . $record->proof_of_payment))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                ManageTablePresetAction::make()->label(' ')
                    ->button()
                    ->tooltip('manage table'),
                BulkActionGroup::make([  DeleteBulkAction::make()->label('Archive')->icon('heroicon-o-archive-box-x-mark'),])->label('danger zone')->icon('heroicon-o-shield-exclamation')
            ]);
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
