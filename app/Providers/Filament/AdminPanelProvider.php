<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\Width;
use daacreators\CreatorsTicketing\TicketingPlugin; // Add this line
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use AchyutN\FilamentLogViewer\FilamentLogViewer;
use Ymsoft\FilamentTablePresets\FilamentTablePresetPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('app')
            ->maxContentWidth(Width::Full)
            ->passwordReset()
            ->profile()
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->plugins([
                // FilamentShieldPlugin::make(),
                // TicketingPlugin::make(), 
                FilamentLogViewer::make()
                    // ->authorize(fn() => Auth::user()->role === 'admin')
                    ->navigationGroup('Developers Support')
                    ->navigationSort(10),
                FilamentTablePresetPlugin::make(),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->userMenuItems([
                'logout' => fn(Action $action) => $action->label('Log out')
                    ->hidden()
                    ->action(fn() => dd('logout')),
                Action::make('logout')
                    ->label('Log out')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->action(function () {
                        Auth::logout();
                        session()->invalidate();
                        session()->regenerateToken();
                        return redirect('/');
                    }),
            ])
            ->brandLogo(asset('assets/img/logo/logo2.png'))
            ->brandLogoHeight('4rem')
            ->globalSearch(false)
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems([
                'logout' => fn(Action $action) => $action->label('Log out')
                    ->hidden()
                    ->action(fn() => dd('logout')),
                Action::make('logout')
                    ->label('Log out')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->extraAttributes([
                        'x-on:click.prevent' => 'confirmLogout()',
                        'id' => 'logoutFi'
                    ]),
            ]);
    }
}
