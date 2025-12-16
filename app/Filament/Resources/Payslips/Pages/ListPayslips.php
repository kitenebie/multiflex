<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListPayslips extends ListRecords
{
    protected static string $resource = PayslipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('date_cutoff_info')
                ->label('Date Cutoff Information')
                ->modalHeading('Payslip Date Cutoff Information')
                ->modalContent(fn() => "Payslips are generated based on a monthly cutoff period, typically from the 1st to the last day of each month. Ensure that all work hours, overtime, and deductions are accurately recorded within this period to reflect in the payslip. For any discrepancies or adjustments, please contact the HR department before the end of the month.")
                ->modalWidth('md')
                ->schema([
                    Select::make('First_Cutoff')
                        ->options(collect(range(1, 28))->mapWithKeys(function ($day) {
                            $suffix = match ($day % 100) {
                                11, 12, 13 => 'th',
                                default => match ($day % 10) {
                                    1 => 'st',
                                    2 => 'nd',
                                    3 => 'rd',
                                    default => 'th'
                                }
                            };
                            return [$day => $day . $suffix];
                        })->toArray())
                        ->required()
                        ->helperText('Select the cutoff day for the first pay period of the month.')
                ]),
        ];
    }
}
