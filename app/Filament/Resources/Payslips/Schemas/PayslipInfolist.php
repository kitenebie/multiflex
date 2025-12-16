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
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('period_start')
                    ->date(),
                TextEntry::make('period_end')
                    ->date(),
                TextEntry::make('basic_salary')
                    ->numeric(),
                TextEntry::make('overtime_hours')
                    ->numeric(),
                TextEntry::make('overtime_rate')
                    ->numeric(),
                TextEntry::make('overtime_amount')
                    ->numeric(),
                TextEntry::make('deductions')
                    ->numeric(),
                TextEntry::make('gross_amount')
                    ->numeric(),
                TextEntry::make('net_amount')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
