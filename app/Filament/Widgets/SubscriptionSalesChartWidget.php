<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Carbon\CarbonImmutable;

class SubscriptionSalesChartWidget extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Subscription Sales by Fitness Offer';
    protected int|string|array $columnSpan = 1;

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
     * Filter panel for selecting year
     */
    public function filtersSchema($schema)
    {
        return $schema->components([
            Select::make('year')
                ->label('Year')
                ->options($this->getYearOptions())
                ->default(now()->year)
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->applyFilters()),
        ]);
    }

    public function applyFilters(bool $shouldRefresh = true): void
    {
        $filters = $this->filters ?? [];
        $this->year = data_get($filters, 'year', now()->year);

        $this->startDate = CarbonImmutable::createFromDate($this->year, 1, 1)->startOfDay();
        $this->endDate = CarbonImmutable::createFromDate($this->year, 12, 31)->endOfDay();

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
        $startDate = $this->startDate?->format('Y-m-d') ?: now()->startOfYear()->format('Y-m-d');
        $endDate = $this->endDate?->format('Y-m-d') ?: now()->endOfYear()->format('Y-m-d');

        $query = SubscriptionTransaction::query()
            ->join('subscriptions', 'subscription_transactions.subscription_id', '=', 'subscriptions.id')
            ->join('fitness_offers', 'subscriptions.fitness_offer_id', '=', 'fitness_offers.id')
            ->select('fitness_offers.name', DB::raw('SUM(subscription_transactions.amount) as total_sales'))
            ->where('subscription_transactions.paid_at', '>=', $startDate)
            ->where('subscriptions.status', 'active')
            ->where('subscription_transactions.paid_at', '<=', $endDate . ' 23:59:59')
            ->groupBy('fitness_offers.id', 'fitness_offers.name');

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => $data->pluck('total_sales')->toArray(),
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Fitness Offer',
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
