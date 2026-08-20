<?php

namespace App\Filament\Widgets;

use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TenantStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tenants', Tenant::count())
                ->description('Total de tenants en el sistema')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),
            Stat::make('Tenants Activos', Tenant::where('status', 'active')->count())
                ->description('Tenants con estado activo')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Tenants Inactivos', Tenant::where('status', 'inactive')->count())
                ->description('Tenants con estado inactivo')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
