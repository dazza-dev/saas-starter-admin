<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Filament::serving(function (): void {
            $route = request()->route();
            $routeName = $route ? $route->getName() : null;
            if ($routeName === 'filament.admin.auth.login') {
                Filament::getCurrentPanel()->brandLogo(asset('images/logo.svg'));
                Filament::getCurrentPanel()->darkModeBrandLogo(asset('images/logo-light.svg'));
                Filament::getCurrentPanel()->brandLogoHeight('3rem');
            }
        });
    }
}
