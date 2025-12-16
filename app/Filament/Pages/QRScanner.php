<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class QRScanner extends Page
{
    protected static ?string $recordTitleAttribute = 'QR Code Scanner';
    protected static ?string $navigationLabel = 'QR Code Scanner';
    protected static ?string $slug = 'QR Code Scanner';
    protected static UnitEnum|string|null $navigationGroup = 'Attendance';
    
    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false; // not logged in = no access
        }

        return $user->roles()->where('name', 'admin')->exists();
    }
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCamera ;
    protected string $view = 'filament.pages.q-r-scanner';
}
