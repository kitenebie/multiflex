<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('coach_id')
                    ->options(User::where('role', 'coach')->pluck('name', 'id'))
                    ->hidden(!Auth::user()->role === 'admin')
                    ->required(Auth::user()->role === 'admin'),
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->options(User::where('role', 'member')->pluck('name', 'id'))
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('time')
                    ->required(),
                Textarea::make('workout_plan')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'ongoing' => 'Ongoing', 'completed' => 'Completed'])
                    ->default('pending')
                    ->required(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
