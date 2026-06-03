<?php

namespace App\Filament\Resources\Opinis;

use App\Filament\Resources\Opinis\Pages\CreateOpini;
use App\Filament\Resources\Opinis\Pages\EditOpini;
use App\Filament\Resources\Opinis\Pages\ListOpinis;
use App\Filament\Resources\Opinis\Schemas\OpiniForm;
use App\Filament\Resources\Opinis\Tables\OpinisTable;
use App\Models\Opini;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OpiniResource extends Resource
{
    protected static ?string $model = Opini::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    public static function getNavigationGroup(): ?string

    {
        return 'Post Management';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    /*
    |--------------------------------------------------------------------------
    | Resource Authorization
    |--------------------------------------------------------------------------
    |
    | Bagian ini mengatur semua akses CRUD untuk resource opini.
    | Semua permission diambil dari sistem permission custom:
    | opini.read, opini.create, opini.update, opini.delete
    |
    | Controller akses sepenuhnya dikendalikan oleh role permission
    |
    */

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('opini.read');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermission('opini.create');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermission('opini.update');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermission('opini.delete');
    }

    /*
    |--------------------------------------------------------------------------
    | Form Schema
    |--------------------------------------------------------------------------
    |
    | Definisi form untuk create dan edit Post.
    | Dipisahkan ke class PostForm agar lebih modular.
    |
    */

    public static function form(Schema $schema): Schema
    {
        return OpiniForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OpinisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOpinis::route('/'),
            'create' => CreateOpini::route('/create'),
            'edit' => EditOpini::route('/{record}/edit'),
        ];
    }
}
