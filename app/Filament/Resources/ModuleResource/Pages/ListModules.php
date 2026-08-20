<?php

namespace App\Filament\Resources\ModuleResource\Pages;

use App\Filament\Resources\ModuleResource;
use App\Filament\Resources\Pages\BaseModalListPage;

class ListModules extends BaseModalListPage
{
    protected static string $resource = ModuleResource::class;

    protected static ?string $title = 'Módulos';

    protected static ?string $breadcrumb = 'Listado';
}
