<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;


class FitnessSubscription extends Page
{
    protected static ?string $recordTitleAttribute = 'Fitness Offers Pricing';
    protected static ?string $navigationLabel = 'Fitness Offers Pricing';
    protected static ?string $slug = 'Fitness Offers Pricing';
    protected static UnitEnum|string|null $navigationGroup = 'Management';
    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false; // not logged in = no access
        }

        // allow ONLY if user has role 'member'
        return $user->roles()->where('name', 'member')->exists();
    }
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected string $view = 'filament.pages.fitness-subscription';
}
