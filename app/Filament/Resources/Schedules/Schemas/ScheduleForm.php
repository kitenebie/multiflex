<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Coach selector - only for admin
                Select::make('coach_id')
                    ->label('Coach')
                    ->options(function () {
                        return User::where('role', 'coach')
                            ->pluck('name', 'id');
                    })
                    ->hidden(fn() => auth()->user()->role === 'coach')
                    ->required(fn() => auth()->user()->role === 'admin'),

                // Member selector (for admin)
                Select::make('member_id')
                    ->label('Member')
                    ->options(function () {
                        return User::where('role', 'member')
                            ->pluck('name', 'id');
                    })
                    ->hidden(fn() => auth()->user()->role === 'coach'),

                // Member selector (for coach)
                Select::make('member_id')
                    ->label('Member')
                    ->options(function () {
                        return User::where('role', 'member')
                            ->whereHas('subscriptions', function ($query) {
                                $query->where('coach_id', auth()->id())
                                      ->where('end_date', '>=', now());
                            })
                            ->pluck('name', 'id');
                    })
                    ->hidden(fn() => auth()->user()->role === 'admin'),

                DatePicker::make('date')->required(),
                TimePicker::make('time')->required(),
                Textarea::make('workout_plan')->required()->columnSpanFull(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                    ])
                    ->default('pending')
                    ->required(),

                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
