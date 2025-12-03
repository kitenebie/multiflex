<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\SubscriptionSalesChartWidget;
use App\Filament\Widgets\OverallSalesChartWidget;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;

class Dashboard extends BaseDashboard
{
    //only admin can access
    public function mount(): void
    {
        if (Auth::user()->role !== 'admin') {
            if (Auth::user()->role === 'coach') {
                $this->redirect('/app/QR%20Code%20Scanner');
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReport')
                ->label('Generate Report')
                ->icon('heroicon-o-document-chart-bar')
                ->color('primary')
                ->form([
                    Select::make('report_type')
                        ->label('Report Type')
                        ->options([
                            'fitness_offers_sales' => 'Fitness Offers Sales',
                            'overall_sales' => 'Overall Sales',
                        ])
                        ->required(),
                    DatePicker::make('start_date')
                        ->label('Start Date')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('End Date')
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Generate report logic here
                    $report = Report::create([
                        'type' => $data['report_type'],
                        'filters' => [
                            'start_date' => $data['start_date'],
                            'end_date' => $data['end_date'],
                        ],
                        'created_by' => Auth::id(),
                    ]);

                    // For now, just show a notification
                    Notification::make()
                        ->title('Report Generated')
                        ->body("{$data['report_type']} report for period {$data['start_date']} to {$data['end_date']} has been generated.")
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            SubscriptionSalesChartWidget::class,
            OverallSalesChartWidget::class,
        ];
    }
}