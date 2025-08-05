<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Enums\ThemeMode;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->brandName('Centro de Hemodialisis')
            ->brandLogo(asset('images/hemodialisis-logo.png'))
            ->brandLogoHeight('10rem') // Ajusta el valor según el tamaño deseado
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            // ->darkMode(false)
            ->favicon(asset('images/favicon.png'))
            ->colors([
            'primary' => '#009999',
            ])
            ->sidebarWidth('13rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\Reportes\PlabaseReport::class,
                \App\Filament\Pages\Reportes\PlabasePorPacienteReport::class,
            ])
            ->widgets([
                \App\Filament\Widgets\PacienteBuscadorWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Gestión de Pacientes')
                    // ->icon('heroicon-o-users')
                    ->collapsed(false),
                NavigationGroup::make('Reportes')
                    // ->icon('heroicon-o-chart-bar')
                    ->collapsed(true),
                NavigationGroup::make('Administración')
                    // ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(true),
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
            ->authMiddleware([
            Authenticate::class,
            ])
            ->topNavigation(true)
            ->renderHook(
                'panels::head.end',
                fn (): string => Blade::render('
                    <style>
                        /* Personalización específica del logo SOLO en la barra de navegación */
                        /* NO afecta al login, que mantiene la configuración de brandLogoHeight */
                        
                        /* Para la navegación superior (topbar) - altura específica */
                        .fi-topbar .fi-logo {
                            height: 4rem !important;
                            width: auto !important;
                            max-width: none !important;
                        }
                        
                        /* Para la navegación lateral (sidebar) - altura específica */
                        .fi-sidebar-nav .fi-logo {
                            height: 3rem !important;
                            width: auto !important;
                            max-width: none !important;
                        }
                        
                        /* Ajustar el contenedor del topbar si es necesario */
                        .fi-topbar-item {
                            height: auto !important;
                        }
                        
                        /* Responsive - ajustar en pantallas pequeñas */
                        @media (max-width: 768px) {
                            .fi-topbar .fi-logo {
                                height: 3rem !important;
                            }
                            .fi-sidebar-nav .fi-logo {
                                height: 2.5rem !important;
                            }
                        }
                    </style>
                ')
            )
            ;


    }
}