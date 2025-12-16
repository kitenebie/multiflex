<?php

namespace App\Filament\Resources\Payslips;

use App\Filament\Resources\Payslips\Pages\CreatePayslip;
use App\Filament\Resources\Payslips\Pages\EditPayslip;
use App\Filament\Resources\Payslips\Pages\ListPayslips;
use App\Filament\Resources\Payslips\Pages\ViewPayslip;
use App\Filament\Resources\Payslips\Schemas\PayslipForm;
use App\Filament\Resources\Payslips\Schemas\PayslipInfolist;
use App\Filament\Resources\Payslips\Tables\PayslipsTable;
use App\Models\Payslip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayslipResource extends Resource
{
    protected static ?string $model = Payslip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Payslip';

    public static function form(Schema $schema): Schema
    {
        return PayslipForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayslipInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayslipsTable::configure($table);
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
            'index' => ListPayslips::route('/'),
            'create' => CreatePayslip::route('/create'),
            'view' => ViewPayslip::route('/{record}'),
            'edit' => EditPayslip::route('/{record}/edit'),
        ];
    }
}
