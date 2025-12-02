<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;

class SubscriptionSalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Subscription Sales by Fitness Offer';

    protected int | string | array $columnSpan = 'full';

    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getFilters(): ?array
    {
        return [
            DatePicker::make('startDate')
                ->label('Start Date')
                ->default(now()->startOfMonth()),
            DatePicker::make('endDate')
                ->label('End Date')
                ->default(now()->endOfMonth()),
        ];
    }

    protected function getData(): array
    {
        $query = SubscriptionTransaction::query()
            ->join('subscriptions', 'subscription_transactions.subscription_id', '=', 'subscriptions.id')
            ->join('fitness_offers', 'subscriptions.fitness_offer_id', '=', 'fitness_offers.id')
            ->select('fitness_offers.name', DB::raw('SUM(subscription_transactions.amount) as total_sales'))
            ->groupBy('fitness_offers.id', 'fitness_offers.name');

        if ($this->startDate) {
            $query->where('subscription_transactions.paid_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('subscription_transactions.paid_at', '<=', $this->endDate . ' 23:59:59');
        }

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