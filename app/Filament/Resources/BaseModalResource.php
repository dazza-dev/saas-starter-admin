<?php

namespace App\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;

abstract class BaseModalResource extends Resource
{
    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->recordActionsColumnLabel('Acciones')
            ->searchPlaceholder('Buscar')
            ->recordActionsAlignment('right')
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->label(null)
                    ->tooltip('Editar')
                    ->modalWidth(Width::Medium)
                    ->modalSubmitActionLabel('Guardar')
                    ->modalCancelActionLabel('Cancelar')
                    ->modalFooterActionsAlignment(Alignment::End),
                DeleteAction::make()
                    ->iconButton()
                    ->label(null)
                    ->tooltip('Eliminar')
                    ->modalWidth(Width::Small)
                    ->modalSubmitActionLabel('Eliminar')
                    ->modalCancelActionLabel('Cancelar')
                    ->modalFooterActionsAlignment(Alignment::End),
            ]);
    }
}
