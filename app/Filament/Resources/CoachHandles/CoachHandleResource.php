<?php

namespace App\Filament\Resources\CoachHadles;

use App\Filament\Resources\CoachHadles\Pages\CreateCoachHadle;
use App\Filament\Resources\CoachHadles\Pages\EditCoachHadle;
use App\Filament\Resources\CoachHadles\Pages\ListCoachHadles;
use App\Filament\Resources\CoachHadles\Pages\ViewCoachHadle;
use App\Filament\Resources\CoachHadles\Schemas\CoachHadleForm;
use App\Filament\Resources\CoachHadles\Schemas\CoachHadleInfolist;
use App\Filament\Resources\CoachHadles\Tables\CoachHadlesTable;
use App\Models\CoachHadle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoachHadleResource extends Resource
{
    protected static ?string $model = CoachHadle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Coach Handles';

    public static function form(Schema $schema): Schema
    {
        return CoachHadleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CoachHadleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoachHadlesTable::configure($table);
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
            'index' => ListCoachHadles::route('/'),
            'create' => CreateCoachHadle::route('/create'),
            'view' => ViewCoachHadle::route('/{record}'),
            'edit' => EditCoachHadle::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
