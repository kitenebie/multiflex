<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(['attendance' => 'Attendance', 'subscription' => 'Subscription', 'revenue' => 'Revenue'])
                    ->required(),
                Textarea::make('filters')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('file_path')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
