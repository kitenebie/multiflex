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
use Filament\Schemas\Components\Grid;
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
            $data['user_name'],
            $data['user_email'],
            $data['user_password'],
            $data['user_password_confirmation'],
            $data['user_address'],
            $data['user_age'],
            $data['user_gender'],
            $data['months'],
            $data['amount'],
            $data['payment_method'],
            $data['reference_no'],
            $data['paid_at'],
            $data['proof_of_payment']
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
                Grid::make()
                    ->columnSpanFull()
                    ->schema([
                        Select::make('user_id')
                            ->label('Member Name')
                            ->options(User::where('role', 'member')->pluck('name', 'id'))
                            ->required(),
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
                        TextInput::make('months')
                            ->label('Months')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $startDate = $get('start_date');
                                if ($startDate && $state) {
                                    $set('end_date', \Carbon\Carbon::parse($startDate)->addMonths($state)->toDateString());
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
                            ->hidden()
                            ->required()
                            ->prefix('PHP'),
                        Select::make('payment_method')
                            ->options([
                                'Cash' => 'Cash',
                                'upload' => 'Upload',
                                'others' => 'Others',
                            ])
                            ->required(),
                        TextInput::make('reference_no'),
                        FileUpload::make('proof_of_payment')
                            ->image()->columnSpanFull()
                            ->directory('proofs'),
                        DateTimePicker::make('paid_at')
                            ->default(now())->hidden()
                            ->required(),

                    ])->columns(2),
            ]);
    }
}
