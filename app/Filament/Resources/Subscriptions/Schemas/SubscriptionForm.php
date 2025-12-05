<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionTransaction;
use App\Models\FitnessOffer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class SubscriptionForm
{
    private static array $transactionData;

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Store transaction data
        self::$transactionData = [
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'reference_no' => $data['reference_no'] ?? null,
            'paid_at' => $data['paid_at'],
            'proof_of_payment' => $data['proof_of_payment'] ?? null,
        ];

        // Create User
        $user = User::create([
            'name' => $data['user_name'],
            'email' => $data['user_email'],
            'password' => Hash::make($data['user_password']),
            'role' => 'member',
            'status' => 'active',
            'address' => $data['user_address'] ?? null,
            'age' => $data['user_age'] ?? null,
            'gender' => $data['user_gender'] ?? null,
        ]);
        $user->update(['qr_code' => bcrypt($user->id)]);

        // Set user_id for subscription
        $data['user_id'] = $user->id;

        // Remove user and transaction fields from data
        unset(
            $data['user_name'], $data['user_email'], $data['user_password'], $data['user_password_confirmation'],
            $data['user_address'], $data['user_age'], $data['user_gender'],
            $data['amount'], $data['payment_method'], $data['reference_no'], $data['paid_at'], $data['proof_of_payment']
        );

        return $data;
    }

    public static function afterCreate($record): void
    {
        // Create Transaction
        SubscriptionTransaction::create(array_merge(self::$transactionData, [
            'subscription_id' => $record->id,
        ]));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // User fields
                TextInput::make('user_name')
                    ->label('Name')
                    ->required(),
                TextInput::make('user_email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(table: 'users', column: 'email'),
                TextInput::make('user_password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(),
                TextInput::make('user_password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->revealable()
                    ->same('user_password')
                    ->required(),
                TextInput::make('user_address')
                    ->label('Address'),
                TextInput::make('user_age')
                    ->label('Age')
                    ->numeric(),
                Select::make('user_gender')
                    ->label('Gender')
                    ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),

                // Subscription fields
                Select::make('fitness_offer_id')
                    ->label('Fitness Offer')
                    ->options(FitnessOffer::pluck('name', 'id'))
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $offer = FitnessOffer::find($state);
                        if ($offer) {
                            $set('amount', $offer->price);
                            $set('end_date', now()->addDays($offer->duration_days)->toDateString());
                        }
                    }),
                Select::make('coach_id')
                    ->label('Coach')
                    ->options(User::where('role', 'coach')->pluck('name', 'id'))
                    ->required(),
                DateTimePicker::make('start_date')
                    ->default(now())
                    ->required(),
                DateTimePicker::make('end_date')
                    ->required(),
                Toggle::make('is_extendable')
                    ->default(true),

                // Transaction fields
                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('$'),
                Select::make('payment_method')
                    ->options([
                        'Cash' => 'Cash',
                        'upload' => 'Upload',
                        'others' => 'Others',
                    ])
                    ->required(),
                TextInput::make('reference_no'),
                FileUpload::make('proof_of_payment')
                    ->image()
                    ->directory('proofs'),
                DateTimePicker::make('paid_at')
                    ->default(now())
                    ->required(),
            ]);
    }
}
