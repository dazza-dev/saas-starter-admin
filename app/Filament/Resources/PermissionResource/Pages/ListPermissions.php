<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\Pages\BaseModalListPage;
use App\Filament\Resources\PermissionResource;

class ListPermissions extends BaseModalListPage
{
    protected static string $resource = PermissionResource::class;

    protected static ?string $title = 'Permisos';

    protected static ?string $breadcrumb = 'Listado';
}
