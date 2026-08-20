<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Permission;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PermissionResource extends BaseModalResource
{
    protected static ?string $model = Permission::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuraciones';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Permisos';

    protected static ?string $modelLabel = 'Permiso';

    protected static ?string $pluralModelLabel = 'Permisos';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Permiso')
                    ->helperText('Formato verbo-recurso, por ejemplo: read-invoices')
                    ->required()
                    ->maxLength(191)
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                Select::make('module_id')
                    ->label('Módulo')
                    ->relationship('module', 'name')
                    ->placeholder('Sin módulo')
                    ->native(true)
                    ->columnSpanFull(),
                TextInput::make('group')
                    ->label('Grupo')
                    ->helperText('Agrupa los permisos dentro del módulo. Clave de traducción.')
                    ->required()
                    ->maxLength(60)
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->label('Orden')
                    ->integer()
                    ->default(0)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->defaultGroup('module.name')
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('module_id')
                    ->label('Módulo')
                    ->relationship('module', 'name')
                    ->placeholder('Todos'),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Permiso')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('module.name')
                    ->label('Módulo')
                    ->placeholder('Sin módulo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('group')
                    ->label('Grupo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('order')
                    ->label('Orden')
                    ->sortable(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
        ];
    }
}
