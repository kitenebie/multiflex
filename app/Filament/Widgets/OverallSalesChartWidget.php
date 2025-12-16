<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Carbon\CarbonImmutable;

class OverallSalesChartWidget extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Overall Total Sales';
    protected ?string $height = '300px';

    protected int | string | array $columnSpan = 1;

    public ?CarbonImmutable $startDate = null;
    public ?CarbonImmutable $endDate = null;
    public ?int $year = null;

    public function mount(): void
    {
        parent::mount();

        if (blank($this->filters)) {
            $this->filters = $this->getDefaultFiltersState();
        }

        $this->applyFilters(shouldRefresh: false);
        $this->year = data_get($this->filters, 'year', now()->year);
    }

    /**
     * Define the chart filters schema
     */
    public function filtersSchema($schema)
    {
        return $schema->components([
            Select::make('year')
                ->label('Year')
                ->options($this->getYearOptions())
                ->default(now()->year)
                ->native(false),

            Actions::make([
                Action::make('applyFilters')
                    ->label('Apply')
                    ->action('applyFilters')
                    ->icon('heroicon-o-funnel')
                    ->color('primary'),
                Action::make('resetFilters')
                    ->label('Reset')
                    ->color('danger')
                    ->action('resetFilters')
                    ->icon('heroicon-o-arrow-path'),
            ])->fullWidth(),
        ]);
    }

    public function applyFilters(bool $shouldRefresh = true): void
    {
        $filters = $this->filters ?? [];

        $year = data_get($filters, 'year', now()->year);
        $this->year = $year;

        $this->startDate = CarbonImmutable::createFromDate($year, 1, 1)->startOfDay();
        $this->endDate = CarbonImmutable::createFromDate($year, 12, 31)->endOfDay();

        if ($shouldRefresh) {
            $this->dispatch('$refresh');
        }
    }

    public function resetFilters(): void
    {
        $this->filters = $this->getDefaultFiltersState();
        $this->applyFilters();
    }

    protected function getDefaultFiltersState(): array
    {
        return [
            'year' => now()->year,
        ];
    }

    protected function getYearOptions(): array
    {
        $years = range(now()->year, 2010); // start from current year down to 2010
        return array_combine($years, $years);
    }


    protected function getData(): array
    {
        $year = $this->year ?? now()->year;

        // Create full months array (Jan → Dec)
        $months = collect(range(1, 12))->mapWithKeys(function ($m) use ($year) {
            return [
                sprintf('%04d-%02d', $year, $m) => 0, // default 0 sales
            ];
        });

        // Query actual sales
        $query = SubscriptionTransaction::query()
            ->select(
                DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total_sales')
            )
            ->whereYear('paid_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Merge: replace defaults with actual values
        foreach ($query as $row) {
            $months[$row->month] = $row->total_sales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => array_values($months->toArray()),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $months->keys()->map(function ($month) {
                return date('M', strtotime($month . '-01')); // Jan, Feb, ...
            })->toArray(),
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Month',
                    ],
                ],
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Total Sales',
                    ],
                ],
            ],
        ];
    }

    public function getHeading(): ?string
    {
        return $this->heading . ' ' . $this->year;
    }
}
