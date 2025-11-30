<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Coach extends Page
{
    protected static ?string $recordTitleAttribute = 'Instractors';
    protected static ?string $navigationLabel = 'Instractors';
    protected static ?string $slug = 'Instractors';
    protected static UnitEnum|string|null $navigationGroup = 'Users';
    
    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false; // not logged in = no access
        }

        return !$user->roles()->where('name', 'coach')->exists();
    }
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected string $view = 'filament.pages.coach';
}
