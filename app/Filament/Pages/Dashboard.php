<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\SubscriptionSalesChartWidget;

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

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            SubscriptionSalesChartWidget::class,
        ];
    }
}