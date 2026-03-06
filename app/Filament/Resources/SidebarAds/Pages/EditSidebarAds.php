<?php

namespace App\Filament\Resources\SidebarAds\Pages;

use App\Filament\Resources\SidebarAds\SidebarAdsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSidebarAds extends EditRecord
{
    protected static string $resource = SidebarAdsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
