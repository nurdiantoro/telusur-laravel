<?php

namespace App\Filament\Resources\SidebarAds;

use App\Filament\Resources\SidebarAds\Pages\CreateSidebarAds;
use App\Filament\Resources\SidebarAds\Pages\EditSidebarAds;
use App\Filament\Resources\SidebarAds\Pages\ListSidebarAds;
use App\Filament\Resources\SidebarAds\Schemas\SidebarAdsForm;
use App\Filament\Resources\SidebarAds\Tables\SidebarAdsTable;
use App\Models\SidebarAds;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SidebarAdsResource extends Resource
{
    protected static ?string $model = SidebarAds::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('sidebar_ads.read');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermission('sidebar_ads.create');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermission('sidebar_ads.update');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermission('sidebar_ads.delete');
    }

    public static function form(Schema $schema): Schema
    {
        return SidebarAdsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SidebarAdsTable::configure($table);
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
            'index' => ListSidebarAds::route('/'),
            'create' => CreateSidebarAds::route('/create'),
            'edit' => EditSidebarAds::route('/{record}/edit'),
        ];
    }
}
