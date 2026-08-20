<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Tenants';

    protected static ?string $modelLabel = 'Tenant';

    protected static ?string $pluralModelLabel = 'Tenants';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Datos del Tenant')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de la organización')
                            ->required()
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('tax_id')
                            ->label('Identificación fiscal')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('first_name')
                            ->label('Nombre del contacto')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('last_name')
                            ->label('Apellido del contacto')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(191)
                            ->columnSpanFull(),
                        Select::make('tenant_type_id')
                            ->label('Tipo de negocio')
                            ->relationship('tenantType', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'active' => 'Activo',
                                'inactive' => 'Inactivo',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Section::make('Usuario Administrador')
                    ->description('Estos datos se usarán para crear el usuario administrador del tenant.')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->columnSpanFull(),
                    ])
                    ->visibleOn('create'),
                Repeater::make('domains')
                    ->relationship('domains')
                    ->label('Dominios')
                    ->schema([
                        TextInput::make('domain')
                            ->label('Dominio')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('name')
                    ->label('Organización')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tax_id')
                    ->label('Id. fiscal')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tenantType.name')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('domains.domain')
                    ->label('Dominios')
                    ->listWithLineBreaks()
                    ->limitList(3),
            ])
            ->recordUrl(fn (Tenant $record) => static::getUrl('edit', ['record' => $record]));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
