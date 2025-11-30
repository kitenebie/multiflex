<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class QRScanner extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Camera ;
    protected string $view = 'filament.pages.q-r-scanner';
}
