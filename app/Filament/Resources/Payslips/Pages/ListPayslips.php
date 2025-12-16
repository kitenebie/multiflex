<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
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
                ->modalWidth('md')
                ->action(function (array $data) {
                    $secondCutoff = $this->parseSecondCutoff($data['Second_Cutoff'] ?? null);
                    $this->saveCutoffData($data['First_Cutoff'] ?? null, $secondCutoff);
                    $this->dispatch('close-modal');
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'Cutoff days saved successfully!'
                    ]);
                })
                ->schema([
                    Select::make('First_Cutoff')
                        ->options(collect(range(1, 28))->mapWithKeys(function ($day) {
                            $suffix = $this->getOrdinalSuffix($day);
                            return [$day => $day . $suffix];
                        })->toArray())
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
                        ->required()
                        ->helperText('Select the cutoff day for the second pay period. Options will update based on your first cutoff selection.')
                ]),
        ];
    }

    protected function saveCutoffData(?int $firstCutoff, ?int $secondCutoff): void
    {
        if ($firstCutoff === null && $secondCutoff === null) {
            return;
        }

        $filePath = base_path('cutoff-bases.json');
        $data = [
            'first_cutoff_day' => $firstCutoff,
            'second_cutoff_day' => $secondCutoff,
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
}
