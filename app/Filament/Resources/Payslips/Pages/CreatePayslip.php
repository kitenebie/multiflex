<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Payslip;
use App\Models\User;

class CreatePayslip extends CreateRecord
{
    protected static string $resource = PayslipResource::class;
    protected function handleRecordCreation(array $data): Payslip
    {
        // 🔥 ALL COACHES SELECTED
        $coaches = User::role('coach')->get();

        foreach ($coaches as $coach) {
            Payslip::create([
                'employee_id'  => $coach->id,
                'period_start' => $data['period_start'],
                'period_end'   => $data['period_end'],
                'basic_salary' => $coach->basic_salary, // must exist
                'allowances'   => 0,
                'overtime_pay' => 0,
                'sss'          => $data['sss'],
                'philhealth'   => $data['philhealth'],
                'pagibig'      => $data['pagibig'],
            ]);
        }

        // Filament requires one model returned
        return Payslip::latest()->first();
    }
}
