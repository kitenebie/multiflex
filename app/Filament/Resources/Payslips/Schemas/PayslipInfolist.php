<?php

namespace App\Filament\Resources\Payslips\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PayslipInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Company Header
                TextEntry::make('company_header')
                    ->label('')
                    ->default('MULTIFLEX GYM MANAGEMENT SYSTEM')
                    ->extraAttributes(['class' => 'text-2xl font-bold text-center text-blue-600 block w-full'])
                    ->columnSpanFull(),

                TextEntry::make('company_address')
                    ->label('')
                    ->default('123 Fitness Street, Health City, Philippines 1000')
                    ->extraAttributes(['class' => 'text-center text-gray-600 block w-full'])
                    ->columnSpanFull(),

                TextEntry::make('company_contact')
                    ->label('')
                    ->default('Tel: (02) 8123-4567 | Email: info@multiflex.com')
                    ->extraAttributes(['class' => 'text-center text-gray-600 block w-full mb-6'])
                    ->columnSpanFull(),

                // Payslip Title
                TextEntry::make('payslip_title')
                    ->label('')
                    ->default('PAYSLIP')
                    ->extraAttributes(['class' => 'text-xl font-bold text-center text-gray-800 block w-full mb-6'])
                    ->columnSpanFull(),

                // Employee Information Section
                TextEntry::make('employee_section')
                    ->label('EMPLOYEE INFORMATION')
                    ->default('')
                    ->extraAttributes(['class' => 'text-lg font-bold text-gray-800 block w-full border-b-2 border-gray-300 pb-2 mb-4'])
                    ->columnSpanFull(),

                TextEntry::make('employee.name')
                    ->label('Employee Name:')
                    ->default('John Doe')
                    ->extraAttributes(['class' => 'font-semibold']),

                TextEntry::make('employee.email')
                    ->label('Email:')
                    ->default('john.doe@multiflex.com'),

                TextEntry::make('employee.position')
                    ->label('Position:')
                    ->default('Fitness Coach'),

                // Period Information
                TextEntry::make('period_info_section')
                    ->label('')
                    ->default('')
                    ->extraAttributes(['class' => 'block w-full mb-4'])
                    ->columnSpanFull(),

                TextEntry::make('period_start')
                    ->label('Pay Period Start:')
                    ->getStateUsing(fn($record) => $record->period_start->format('F j, Y'))
                    ->extraAttributes(['class' => 'font-semibold']),

                TextEntry::make('period_end')
                    ->label('Pay Period End:')
                    ->getStateUsing(fn($record) => $record->period_end->format('F j, Y'))
                    ->extraAttributes(['class' => 'font-semibold']),

                TextEntry::make('days_attended')
                    ->label('Days Attended:')
                    ->default('0')
                    ->suffix(' days')
                    ->extraAttributes(['class' => 'font-semibold']),

                TextEntry::make('is_submit')
                    ->label('Status:')
                    ->getStateUsing(fn($record) => $record->is_submit ? 'Submitted' : 'Draft')
                    ->extraAttributes(['class' => 'font-semibold']),

                // Earnings Section
                TextEntry::make('earnings_section')
                    ->label('EARNINGS')
                    ->default('')
                    ->extraAttributes(['class' => 'text-lg font-bold text-gray-800 block w-full border-b-2 border-gray-300 pb-2 mb-4 mt-6'])
                    ->columnSpanFull(),

                TextEntry::make('basic_salary')
                    ->label('Basic Salary:')
                    ->money('PHP')
                    ->extraAttributes(['class' => 'font-semibold']),

                TextEntry::make('total_salary')
                    ->label('Total Monthly Basic Salary:')
                    ->money('PHP')
                    ->extraAttributes(['class' => 'font-semibold']),

                TextEntry::make('allowances')
                    ->label('Allowances:')
                    ->money('PHP'),

                TextEntry::make('overtime_pay')
                    ->label('Overtime Pay:')
                    ->money('PHP'),

                TextEntry::make('total_earnings')
                    ->label('TOTAL EARNINGS:')
                    ->default(function ($record) {
                        return $record->basic_salary + $record->allowances + $record->overtime_pay;
                    })
                    ->money('PHP')
                    ->extraAttributes(['class' => 'text-lg font-bold text-green-600 bg-green-50 p-2 rounded block w-full mt-2'])
                    ->columnSpanFull(),

                // Deductions Section
                TextEntry::make('deductions_section')
                    ->label('DEDUCTIONS')
                    ->default('')
                    ->extraAttributes(['class' => 'text-lg font-bold text-gray-800 block w-full border-b-2 border-gray-300 pb-2 mb-4 mt-6'])
                    ->columnSpanFull(),

                TextEntry::make('sss')
                    ->label('SSS:')
                    ->money('PHP'),

                TextEntry::make('philhealth')
                    ->label('PhilHealth:')
                    ->money('PHP'),

                TextEntry::make('pagibig')
                    ->label('PAG-IBIG:')
                    ->money('PHP'),

                TextEntry::make('tax')
                    ->label('Withholding Tax:')
                    ->money('PHP'),

                TextEntry::make('total_deductions')
                    ->label('TOTAL DEDUCTIONS:')
                    ->money('PHP')
                    ->extraAttributes(['class' => 'text-lg font-bold text-red-600 bg-red-50 p-2 rounded block w-full mt-2'])
                    ->columnSpanFull(),

                // Net Pay Section
                TextEntry::make('net_pay_section')
                    ->label('')
                    ->default('')
                    ->extraAttributes(['class' => 'block w-full mt-6 mb-4'])
                    ->columnSpanFull(),

                TextEntry::make('net_pay')
                    ->label('NET PAY:')
                    ->default(function ($record) {
                        $totalEarnings = $record->basic_salary + $record->allowances + $record->overtime_pay;
                        return $totalEarnings - $record->total_deductions;
                    })
                    ->money('PHP')
                    ->extraAttributes(['class' => 'text-2xl font-bold text-center text-green-600 bg-green-100 p-4 rounded-lg block w-full mt-4'])
                    ->columnSpanFull(),

                // Footer Information
                TextEntry::make('footer_section')
                    ->label('')
                    ->default('')
                    ->extraAttributes(['class' => 'block w-full mt-8 border-t border-gray-300 pt-4'])
                    ->columnSpanFull(),

                TextEntry::make('generated_date')
                    ->label('Generated On:')
                    ->default(now()->format('F j, Y g:i A'))
                    ->extraAttributes(['class' => 'text-sm text-gray-500 text-center block w-full'])
                    ->columnSpanFull(),

                TextEntry::make('note')
                    ->label('')
                    ->default('This payslip is generated automatically by the Multiflex Gym Management System.')
                    ->extraAttributes(['class' => 'text-xs text-gray-400 italic text-center block w-full mt-2'])
                    ->columnSpanFull(),
            ]);
    }
}
