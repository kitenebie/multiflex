<?php

namespace App\Filament\Resources\SystemLogs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SystemLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('action')
                    ->required(),
                Textarea::make('details')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('ip_address')
                    ->required(),
            ]);
    }
}
