<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\HtmlString;
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
                ->modalContent(fn() => new HtmlString("Payslips are generated based on a monthly cutoff period, typically from the 1st to the last day of each month. Ensure that all work hours, overtime, and deductions are accurately recorded within this period to reflect in the payslip. For any discrepancies or adjustments, please contact the HR department before the end of the month."))
                ->modalWidth('5xl')
                ->action(function (array $data) {
                    $secondCutoff = $this->parseSecondCutoff($data['Second_Cutoff'] ?? null);
                    $this->saveCutoffData(
                        $data['First_Cutoff'] ?? null,
                        $secondCutoff,
                        $data['SSS'] ?? 0,
                        $data['PagIbig'] ?? 0,
                        $data['PhilHealth'] ?? 0,
                        $data['Tax'] ?? 0
                    );
                    $this->dispatch('close-modal');
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'Cutoff days and deduction rates saved successfully!'
                    ]);
                })
                ->schema([
                    Grid::make(1)
                        ->schema([
                            Select::make('First_Cutoff')
                                ->options(collect(range(1, 28))->mapWithKeys(function ($day) {
                                    $suffix = $this->getOrdinalSuffix($day);
                                    return [$day => $day . $suffix];
                                })->toArray())
                                ->default($this->getCutoffData()['first_cutoff_day'] ?? null)
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn() => $this->dispatch('$refresh'))
                                ->helperText('Select the cutoff day for the first pay period of the month.'),
                            Select::make('Second_Cutoff')
                                ->options(function (callable $get) {
                                    $firstCutoff = $get('First_Cutoff');
                                    if (!$firstCutoff) {
                                        return ['' => 'Please select First Cutoff first'];
                                    }
                                    return $this->getSecondCutoffOptions($firstCutoff);
                                })
                                ->default($this->getDefaultSecondCutoff())
                                ->required()
                                ->helperText('Select the cutoff day for the second pay period. Options will update based on your first cutoff selection.'),
                            TextInput::make('SSS')
                                ->label('SSS Rate (%)')
                                ->numeric()
                                ->default($this->getCutoffData()['sss_rate'] ?? 0)
                                ->helperText('Enter SSS contribution rate as percentage (e.g., 4.5 for 4.5%)'),
                            TextInput::make('PagIbig')
                                ->label('Pag-IBIG Rate (%)')
                                ->numeric()
                                ->default($this->getCutoffData()['pagibig_rate'] ?? 0)
                                ->helperText('Enter Pag-IBIG contribution rate as percentage (e.g., 2.0 for 2.0%)'),
                            TextInput::make('PhilHealth')
                                ->label('PhilHealth Rate (%)')
                                ->numeric()
                                ->default($this->getCutoffData()['philhealth_rate'] ?? 0)
                                ->helperText('Enter PhilHealth contribution rate as percentage (e.g., 3.0 for 3.0%)'),
                            TextInput::make('Tax')
                                ->label('Tax Rate (%)')
                                ->hidden()
                                ->default($this->getCutoffData()['tax_rate'] ?? 0)
                                ->helperText('Enter tax rate as percentage (e.g., 5.0 for 5.0%)'),
                        ]),
                ])->columns(2)
        ];
    }

    protected function saveCutoffData(?int $firstCutoff, ?int $secondCutoff, float $sssRate = 0, float $pagibigRate = 0, float $philhealthRate = 0, float $taxRate = 0): void
    {
        if ($firstCutoff === null && $secondCutoff === null && $sssRate === 0 && $pagibigRate === 0 && $philhealthRate === 0 && $taxRate === 0) {
            return;
        }

        $filePath = base_path('cutoff-bases.json');
        $data = [
            'first_cutoff_day' => $firstCutoff,
            'second_cutoff_day' => $secondCutoff,
            'second_cutoff_type' => $this->getSecondCutoffType($secondCutoff, $firstCutoff),
            'sss_rate' => $sssRate,
            'pagibig_rate' => $pagibigRate,
            'philhealth_rate' => $philhealthRate,
            'tax_rate' => $taxRate,
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

    protected function getSecondCutoffOptions(int $firstCutoff): array
    {
        $options = [];

        // Generate options for same month (after first cutoff)
        for ($day = $firstCutoff + 1; $day <= 28; $day++) {
            $suffix = $this->getOrdinalSuffix($day);
            $options["same_month_{$day}"] = "{$day}{$suffix} (Same Month)";
        }

        // Generate options for next month (1st to 15th)
        for ($day = 1; $day <= 15; $day++) {
            $suffix = $this->getOrdinalSuffix($day);
            $options["next_month_{$day}"] = "{$day}{$suffix} (Next Month)";
        }

        return $options;
    }

    protected function getOrdinalSuffix(int $day): string
    {
        return match ($day % 100) {
            11, 12, 13 => 'th',
            default => match ($day % 10) {
                1 => 'st',
                2 => 'nd',
                3 => 'rd',
                default => 'th'
            }
        };
    }

    protected function parseSecondCutoff(?string $secondCutoffKey): ?int
    {
        if (!$secondCutoffKey) {
            return null;
        }

        // Extract day number from keys like "same_month_15" or "next_month_15"
        if (preg_match('/_(\d+)$/', $secondCutoffKey, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    protected function getCutoffData(): array
    {
        $filePath = base_path('cutoff-bases.json');

        if (!File::exists($filePath)) {
            return [
                'first_cutoff_day' => null,
                'second_cutoff_day' => null,
                'second_cutoff_type' => 'same_month',
                'sss_rate' => 0,
                'pagibig_rate' => 0,
                'philhealth_rate' => 0,
                'tax_rate' => 0
            ];
        }

        $jsonData = File::get($filePath);
        $data = json_decode($jsonData, true);

        return [
            'first_cutoff_day' => $data['first_cutoff_day'] ?? null,
            'second_cutoff_day' => $data['second_cutoff_day'] ?? null,
            'second_cutoff_type' => $data['second_cutoff_type'] ?? 'same_month',
            'sss_rate' => $data['sss_rate'] ?? 0,
            'pagibig_rate' => $data['pagibig_rate'] ?? 0,
            'philhealth_rate' => $data['philhealth_rate'] ?? 0,
            'tax_rate' => $data['tax_rate'] ?? 0
        ];
    }

    protected function getDefaultSecondCutoff(): ?string
    {
        $cutoffData = $this->getCutoffData();
        $secondCutoffDay = $cutoffData['second_cutoff_day'] ?? null;
        $secondCutoffType = $cutoffData['second_cutoff_type'] ?? 'same_month';

        if (!$secondCutoffDay) {
            return null;
        }

        return "{$secondCutoffType}_{$secondCutoffDay}";
    }

    protected function getSecondCutoffType(?int $secondCutoffDay, ?int $firstCutoffDay): string
    {
        if (!$secondCutoffDay || !$firstCutoffDay) {
            return 'same_month';
        }

        // If second cutoff is after first cutoff in the same month
        if ($secondCutoffDay > $firstCutoffDay && $secondCutoffDay <= 28) {
            return 'same_month';
        }

        // Otherwise it's in the next month
        return 'next_month';
    }
}
