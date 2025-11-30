<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('fitness_offer_id')
                    ->relationship('fitnessOffer', 'name')
                    ->required(),
                Select::make('coach_id')
                    ->relationship('coach', 'name')
                    ->default(null),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'active' => 'Active', 'rejected' => 'Rejected', 'inactive' => 'Inactive', 'expired' => 'Expired'])
                    ->default('pending')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                Toggle::make('is_extendable')
                    ->required(),
            ]);
    }
}
