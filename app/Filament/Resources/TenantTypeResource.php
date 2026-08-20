<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantTypeResource\Pages;
use App\Models\TenantType;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TenantTypeResource extends BaseModalResource
{
    protected static ?string $model = TenantType::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuraciones';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Tipos de Negocio';

    protected static ?string $modelLabel = 'Tipo de Negocio';

    protected static ?string $pluralModelLabel = 'Tipos de Negocio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(191)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenantTypes::route('/'),
        ];
    }
}
