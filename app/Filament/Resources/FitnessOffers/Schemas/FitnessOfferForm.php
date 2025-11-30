<?php

namespace App\Filament\Resources\FitnessOffers\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\FitnessOffer;
use Filament\Forms\Components\Select;

class FitnessOfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('duration_days')
                    ->required()
                    ->numeric(),
                Repeater::make('description')
                    ->label('Fitness Offers')
                    ->schema([
                        TextInput::make('fitness_offered')
                            ->label('Fitness Offered')
                            ->required(),
                        Repeater::make('includes')
                            ->label('include')
                            ->schema([
                                TextInput::make('sub_fitness_offered')
                                    ->label('Sub Fitness Offered')
                                    ->required(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->defaultItems(1)
                    ->columnSpanFull(),
                // Select::make('upgrade_to')
                //     ->columnSpanFull()
                //     ->options(FitnessOffer::pluck('name', 'id'))
                //     ->default(null),
            ]);
    }
}
