<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class Coach extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected string $view = 'filament.pages.coach';
}
