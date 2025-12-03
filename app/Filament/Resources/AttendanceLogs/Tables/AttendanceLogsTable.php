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

class AttendanceLogsTable
{
    public static function configure(Table $table): Table
    {
        $query =AttendanceLog::query();
        if(Auth::user()->roles()->where('name', 'coach')->exists())
        {
            $query->whereHas('user.subscriptions', function($q) {
                $q->where('coach_id', Auth::user()->id)->where('is_extendable', false);
            });
        }
        if(Auth::user()->roles()->where('name', 'member')->exists())
        {
            $query->where('user_id', Auth::user()->id)->whereHas('user.subscriptions', function($q) {
                $q->where('is_extendable', false);
            });
        }
        return $table
            ->query($query)
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('time_in')
                    ->time()
                    ->sortable(),
                TextColumn::make('time_out')
                    ->time()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([  DeleteBulkAction::make()->label('Archive')->icon('heroicon-o-archive-box-x-mark'),])->label('danger zone')->icon('heroicon-o-shield-exclamation')
            ]);
    }
}
