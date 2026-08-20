<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Models\Module;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModuleResource extends BaseModalResource
{
    protected static ?string $model = Module::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuraciones';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Módulos';

    protected static ?string $modelLabel = 'Módulo';

    protected static ?string $pluralModelLabel = 'Módulos';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->helperText('Clave de traducción. Debe existir en permissions::names.modules del API.')
                    ->required()
                    ->maxLength(60)
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                TextInput::make('icon')
                    ->label('Ícono')
                    ->helperText('Nombre del ícono de Tabler, por ejemplo: settings')
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
            ->defaultSort('order')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('icon')
                    ->label('Ícono')
                    ->placeholder('Sin ícono'),
                TextColumn::make('permissions_count')
                    ->label('Permisos')
                    ->counts('permissions'),
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
            'index' => Pages\ListModules::route('/'),
        ];
    }
}
