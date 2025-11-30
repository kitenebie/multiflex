<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Members extends Page
{
    protected static ?string $recordTitleAttribute = 'Members';
    protected static ?string $navigationLabel = 'Members';
    protected static ?string $slug = 'Members';
    protected static UnitEnum|string|null $navigationGroup = 'Users';
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
