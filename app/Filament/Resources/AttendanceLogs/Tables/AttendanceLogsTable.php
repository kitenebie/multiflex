<?php

namespace App\Filament\Resources\AttendanceLogs\Tables;

use App\Models\AttendanceLog;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Ymsoft\FilamentTablePresets\Filament\Actions\ManageTablePresetAction;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class AttendanceLogsTable implements HasFilamentTablePresets
{
    use WithFilamentTablePresets;
    public static function configure(Table $table): Table
    {
        $query = AttendanceLog::query();
        if (Auth::user()->roles()->where('name', 'coach')->exists()) {
            $query->whereHas('user.subscriptions', function ($q) {
                $q->where('coach_id', Auth::user()->id)->where('is_extendable', false);
            });
        }
        if (Auth::user()->roles()->where('name', 'member')->exists()) {
            $query->where('user_id', Auth::user()->id)->whereHas('user.subscriptions', function ($q) {
                $q->where('is_extendable', false);
            });
        }
        return $table
            ->query($query)
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()->toggleable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable()->toggleable(),
                TextColumn::make('time_in')
                    ->dateTime('h:i:s A')
                    ->sortable()->toggleable(),
                TextColumn::make('time_out')
                    ->dateTime('h:i:s A')
                    ->sortable()->toggleable(),
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
                    ->button()->color('info')
                    ->tooltip('view'),
                EditAction::make()
                    ->label(' ')
                    ->button()
                    ->color('warning')
                    ->tooltip('edit'),
            ])
            ->toolbarActions([
                ManageTablePresetAction::make()->label(' ')
                    ->button()
                    ->tooltip('manage table'),
                BulkActionGroup::make([DeleteBulkAction::make()->label('Archive')->icon('heroicon-o-archive-box-x-mark'),])->label('danger zone')->icon('heroicon-o-shield-exclamation')
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
