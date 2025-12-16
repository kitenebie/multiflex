<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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
                ->action(function (array $data) {
                    $this->saveCutoffData($data['First_Cutoff'] ?? null);
                    $this->dispatch('close-modal');
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'First cutoff day saved successfully!'
                    ]);
                })
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

    protected function saveCutoffData(?int $cutoffDay): void
    {
        if ($cutoffDay === null) {
            return;
        }

        $filePath = base_path('cutoff-bases.json');
        $data = [
            'first_cutoff_day' => $cutoffDay,
            'updated_at' => now()->toISOString(),
            'updated_by' => Auth::user()?->name ?? 'System'
        ];

        // Create the file if it doesn't exist, or update existing data
        if (File::exists($filePath)) {
            $existingData = json_decode(File::get($filePath), true);
            $data = array_merge($existingData ?? [], $data);
        }

        File::put($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}
