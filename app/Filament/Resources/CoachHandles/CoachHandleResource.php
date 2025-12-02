<?php

namespace App\Filament\Resources\CoachHandles;

use App\Filament\Resources\CoachHandles\Pages\CreateCoachHandle;
use App\Filament\Resources\CoachHandles\Pages\EditCoachHandle;
use App\Filament\Resources\CoachHandles\Pages\ListCoachHandles;
use App\Filament\Resources\CoachHandles\Pages\ViewCoachHandle;
use App\Filament\Resources\CoachHandles\Schemas\CoachHandleForm;
use App\Filament\Resources\CoachHandles\Schemas\CoachHandleInfolist;
use App\Filament\Resources\CoachHandles\Tables\CoachHandlesTable;
use App\Models\CoachHandle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CoachHandleResource extends Resource
{
    
    protected static ?string $navigationLabel = 'Instractor Handles';
    protected static ?string $slug = 'Instractor Handles';
    protected static UnitEnum|string|null $navigationGroup = 'Users';
    protected static ?string $model = CoachHandle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Instractor Handles';

    public static function form(Schema $schema): Schema
    {
        return CoachHandleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CoachHandleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoachHandlesTable::configure($table);
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
            'index' => ListCoachHandles::route('/'),
            'create' => CreateCoachHandle::route('/create'),
            'view' => ViewCoachHandle::route('/{record}'),
            'edit' => EditCoachHandle::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function canAccess(): bool
    {
        return false;
    }
}
