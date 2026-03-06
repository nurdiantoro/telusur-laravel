<?php

namespace App\Filament\Resources\SidebarAds\Pages;

use App\Filament\Resources\SidebarAds\SidebarAdsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSidebarAds extends CreateRecord
{
    protected static string $resource = SidebarAdsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function canCreateAnother(): bool
    {
        return false;
    }
}
