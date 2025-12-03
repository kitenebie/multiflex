<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Carbon\CarbonImmutable;

class OverallSalesChartWidget extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Overall Total Sales';

    protected int | string | array $columnSpan = 1;

    public ?CarbonImmutable $startDate = null;
    public ?CarbonImmutable $endDate = null;

    public function mount(): void
    {
        parent::mount();

        if (blank($this->filters)) {
            $this->filters = $this->getDefaultFiltersState();
        }

        $this->applyFilters(shouldRefresh: false);
    }

    /**
     * Define the chart filters schema
     */
    public function filtersSchema($schema)
    {
        return $schema->components([
            DatePicker::make('startDate')
                ->label('From Date')
                ->maxDate(fn () => data_get($this->filters, 'endDate'))
                ->default($this->getDefaultFiltersState()['startDate'])
                ->native(false),

            DatePicker::make('endDate')
                ->label('To Date')
                ->minDate(fn () => data_get($this->filters, 'startDate'))
                ->default($this->getDefaultFiltersState()['endDate'])
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

        $start = $this->resolveDate(data_get($filters, 'startDate'), true)
            ?? CarbonImmutable::now()->startOfYear()->startOfDay();
        $end = $this->resolveDate(data_get($filters, 'endDate'), false)
            ?? CarbonImmutable::now()->endOfYear()->endOfDay();

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end->startOfDay(), $start->endOfDay()];
        }

        $this->startDate = $start;
        $this->endDate = $end;

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
            'startDate' => CarbonImmutable::now()->startOfYear()->toDateString(),
            'endDate' => CarbonImmutable::now()->endOfYear()->toDateString(),
        ];
    }

    protected function resolveDate(null|string $value, bool $isStart): ?CarbonImmutable
    {
        if (blank($value)) {
            return null;
        }

        $date = CarbonImmutable::parse($value);

        return $isStart ? $date->startOfDay() : $date->endOfDay();
    }

    protected function getData(): array
    {
        $startDate = $this->startDate?->format('Y-m-d') ?: now()->startOfYear()->format('Y-m-d');
        $endDate = $this->endDate?->format('Y-m-d') ?: now()->endOfYear()->format('Y-m-d');

        $query = SubscriptionTransaction::query()
            ->select(
                DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total_sales')
            )
            ->whereBetween('paid_at', [$startDate, $endDate . ' 23:59:59'])
            ->groupBy('month')
            ->orderBy('month');

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => $data->pluck('total_sales')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('month')->map(function ($month) {
                return date('M Y', strtotime($month . '-01'));
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}