<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPayslip extends ViewRecord
{
    protected static string $resource = PayslipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
