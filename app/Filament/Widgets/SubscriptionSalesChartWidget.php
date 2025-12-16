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

    protected ?string $heading = 'Subscribers by Fitness Offer by Month';
    protected int|string|array $columnSpan = 1;
    protected ?string $height = '300px';

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
            $year = $this->year ?: now()->year;
            
            // Get all fitness offers
            $fitnessOffers = \App\Models\FitnessOffer::all();
            
            $datasets = [];
            $colors = ['#f59e0b', '#ef4444', '#10b981', '#3b82f6', '#8b5cf6', '#f97316', '#06b6d4', '#84cc16'];
            
            foreach ($fitnessOffers as $index => $offer) {
                $monthlyData = [];
                for ($month = 1; $month <= 12; $month++) {
                    $count = SubscriptionTransaction::query()
                        ->join('subscriptions', 'subscription_transactions.subscription_id', '=', 'subscriptions.id')
                        ->where('subscriptions.fitness_offer_id', $offer->id)
                        ->where('subscriptions.status', 'active')
                        ->whereYear('subscription_transactions.paid_at', $year)
                        ->whereMonth('subscription_transactions.paid_at', $month)
                        ->distinct('subscriptions.user_id')
                        ->count('subscriptions.user_id');
                    $monthlyData[] = $count;
                }
                
                $datasets[] = [
                    'label' => $offer->name,
                    'data' => $monthlyData,
                    'backgroundColor' => $colors[$index % count($colors)],
                ];
            }
    
            return [
                'datasets' => $datasets,
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                        'text' => 'Month',
                    ],
                ],
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Total Subscribers',
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
