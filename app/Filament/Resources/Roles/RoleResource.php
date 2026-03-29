<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\ManageRoles;
use App\Models\Role;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    /*
     | =========================================
     | ACCESS CONTROL (Permission Check)
     | =========================================
     | Semua akses RoleResource dikontrol manual
     | berdasarkan permission user login
     */

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('roles.read');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermission('roles.create');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermission('roles.update');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermission('roles.delete');
    }

    /*
     | =========================================
     | FORM (Create / Edit Role)
     | =========================================
     */

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // Nama role
                TextInput::make('name')
                    ->required(),

                // Permission multi select (many-to-many relation)
                CheckboxList::make('permissions')
                    ->relationship('permissions', 'name')
                    ->columns(2),
            ]);
    }

    /*
     | =========================================
     | TABLE (List Role)
     | =========================================
     */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role')
                    ->searchable()
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                TextColumn::make('permissions.name')
                    ->badge()
                    ->wrap()
                    ->separator(', '),
            ])

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(), // sengaja dimatikan kalau dibutuhkan safety
                ]),
            ]);
    }

    /*
     | =========================================
     | ROUTES / PAGES
     | =========================================
     */

    public static function getPages(): array
    {
        return [
            'index' => ManageRoles::route('/'),
        ];
    }

    /*
     | =========================================
     | QUERY OVERRIDE
     | =========================================
     | Hide role "administrator" biar jadi root system
     | (tidak bisa dilihat / diubah dari UI)
     */

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('name', '!=', 'administrator');
    }
}
