<?php

namespace App\Filament\Resources\FitnessOffers;

use App\Filament\Resources\FitnessOffers\Pages\ListFitnessOffers;
use App\Filament\Resources\FitnessOffers\Schemas\FitnessOfferForm;
use App\Filament\Resources\FitnessOffers\Schemas\FitnessOfferInfolist;
use App\Filament\Resources\FitnessOffers\Tables\FitnessOffersTable;
use App\Models\FitnessOffer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FitnessOfferResource extends Resource
{
    protected static ?string $model = FitnessOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $recordTitleAttribute = 'Fitness Offers';
    protected static UnitEnum|string|null $navigationGroup = 'Management';
    public static function form(Schema $schema): Schema
    {
        return FitnessOfferForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FitnessOfferInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FitnessOffersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFitnessOffers::route('/'),
        ];
    }
}
