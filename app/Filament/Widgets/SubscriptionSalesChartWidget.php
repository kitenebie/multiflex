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

class SubscriptionSalesChartWidget extends ChartWidget
{
    protected ?string $heading = 'Subscription Sales by Fitness Offer';

    protected int | string | array $columnSpan = 1;

    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getData(): array
    {
        $startDate = $this->startDate ?: now()->startOfMonth()->format('Y-m-d');
        $endDate = $this->endDate ?: now()->endOfMonth()->format('Y-m-d');

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
}