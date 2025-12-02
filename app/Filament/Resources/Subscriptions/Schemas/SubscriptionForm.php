<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                    ->options(User::query()->where('role', 'coach')->pluck('name', 'id'))
                    ->default(null),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'active' => 'Active', 'rejected' => 'Rejected', 'inactive' => 'Inactive', 'expired' => 'Expired'])
                    ->default('pending')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->after('start_date')
                    ->required(),
                TextInput::make('amount')
                    ->numeric()
                    ->required(),
                TextInput::make('payment_method')
                    ->required(),
                TextInput::make('reference_no'),
                DateTimePicker::make('paid_at'),
                FileUpload::make('proof_of_payment')
                    ->image()
                    ->directory('proofs'),
            ]);
    }
}
