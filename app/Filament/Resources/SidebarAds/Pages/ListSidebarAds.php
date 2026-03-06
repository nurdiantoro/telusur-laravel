<?php

namespace App\Filament\Resources\SidebarAds\Pages;

use App\Filament\Resources\SidebarAds\SidebarAdsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSidebarAds extends ListRecords
{
    protected static string $resource = SidebarAdsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
