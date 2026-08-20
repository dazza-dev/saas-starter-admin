<?php

namespace App\Filament\Resources\TenantTypeResource\Pages;

use App\Filament\Resources\Pages\BaseModalListPage;
use App\Filament\Resources\TenantTypeResource;

class ListTenantTypes extends BaseModalListPage
{
    protected static string $resource = TenantTypeResource::class;

    protected static ?string $title = 'Tipos de Negocio';

    protected static ?string $breadcrumb = 'Listado';
}
