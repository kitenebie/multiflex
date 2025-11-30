<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false; // not logged in = no access
        }

        return $user->roles()->where('name', 'admin')->exists();
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pending Subscriptions', Subscription::where('status', 'pending')->count()),
            Stat::make('Total Active Subscriptions', Subscription::where('status', 'active')->count()),
            Stat::make('Pending Members', User::where('role', 'member')->where('status', 'pending')->count()),
            Stat::make('Pending Coaches', User::where('role', 'coach')->where('status', 'pending')->count()),
            Stat::make('Total Active Coaches', User::where('role', 'coach')->where('status', 'active')->count()),
            Stat::make('Total Active Members', User::where('role', 'member')->where('status', 'active')->count()),
        ];
    }
}