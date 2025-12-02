<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Action;
use Filament\Forms\Form;

class OverallSalesChartWidget extends ChartWidget
{
    protected ?string $heading = 'Overall Total Sales';

    protected int | string | array $columnSpan = 1;

    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getData(): array
    {
        $startDate = $this->startDate ?: now()->startOfYear()->format('Y-m-d');
        $endDate = $this->endDate ?: now()->endOfYear()->format('Y-m-d');

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


    protected function getHeaderActions(): array
    {
        return [
            Action::make('filter')
                ->label('Filter Date Range')
                ->icon('heroicon-o-calendar')
                ->form([
                    DatePicker::make('startDate')
                        ->label('Start Date')
                        ->default($this->startDate ?: now()->startOfYear()->format('Y-m-d')),
                    DatePicker::make('endDate')
                        ->label('End Date')
                        ->default($this->endDate ?: now()->endOfYear()->format('Y-m-d')),
                ])
                ->action(function (array $data): void {
                    $this->startDate = $data['startDate'];
                    $this->endDate = $data['endDate'];
                }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}