<?php

namespace App\Filament\Resources\FitnessOffers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Ymsoft\FilamentTablePresets\Filament\Actions\ManageTablePresetAction;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class FitnessOffersTable implements HasFilamentTablePresets
{
    use WithFilamentTablePresets;
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()->toggleable(),
                TextColumn::make('price')
                    ->money('PHP')
                    ->sortable()->toggleable(),
                TextColumn::make('description')
                    ->formatStateUsing(function ($state) {

                        // If null or empty, just return nothing
                        if (blank($state)) {
                            return '';
                        }

                        // Always convert ANY nested structure into HTML list
                        $renderItems = function ($value) use (&$renderItems) {
                            if (is_array($value)) {
                                // Loop if array
                                $html = '';
                                foreach ($value as $key => $v) {
                                    if($key == 'fitness_offered')
                                    {
                                    $html .= '<li style="font-weight: bold;">' . $renderItems($v) . '</li>';

                                    }else{

                                        $html .=   '<li>' . $renderItems($v) . '</li>';
                                    }
                                }
                                return '<ul>' . $html . '</ul>';
                            }

                            // Convert scalar safely
                            return e((string) $value);
                        };

                        return $renderItems($state);
                    })
                    ->html()->toggleable(),
                TextColumn::make('duration_days')
                    ->numeric()
                    ->sortable()->toggleable(),
                // TextColumn::make('upgrade_to')
                //     ->numeric()
                //     ->sortable(),
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
                    ->color('info'),
                EditAction::make()
                    ->label(' ')
                    ->color('warning')
                    ->button()
                    ->tooltip('edit'),
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
