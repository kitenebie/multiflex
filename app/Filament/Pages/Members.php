<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class Members extends Page
{
    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false; // not logged in = no access
        }

        return !$user->roles()->where('name', 'member')->exists();
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected string $view = 'filament.pages.members';
}
