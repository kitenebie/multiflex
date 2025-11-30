<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class Coach extends Page
{
    
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
