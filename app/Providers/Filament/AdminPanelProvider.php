<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaBoiteACode\FilamentLogsExplorer\FilamentLogsExplorerPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->colors([
                'primary' => Color::hex('#2fd36b'),
            ])
            ->font('Inter')
            ->topbar(false)
            ->sidebarWidth('17rem')
            ->collapsedSidebarWidth('5.25rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationItems([
                NavigationItem::make('Ver el sitio')
                    ->url(fn (): string => route('home'), shouldOpenInNewTab: true)
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->sort(-1),
            ])
            ->brandName('Anselmi Admin')
            ->brandLogo(fn () => view('filament.panel.brand'))
            ->brandLogoHeight('2.75rem')
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.panel.sidebar-default'))
            ->renderHook(PanelsRenderHook::SIDEBAR_LOGO_BEFORE, fn () => view('filament.panel.collapsed-brand'))
            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_HEADING_BEFORE,
                fn () => view('filament.panel.dashboard-eyebrow'),
                scopes: Dashboard::class,
            )
            ->sidebarCollapsibleOnDesktop()
            ->plugin(
                FilamentLogsExplorerPlugin::make()
                    ->navigationLabel('Logs')
                    ->navigationGroup('Sitio')
            );
    }
}
