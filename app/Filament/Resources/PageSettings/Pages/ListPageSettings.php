<?php

namespace App\Filament\Resources\PageSettings\Pages;

use App\Filament\Resources\PageSettings\PageSettingResource;
use App\Models\PageSetting;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPageSettings extends ListRecords
{
    protected static string $resource = PageSettingResource::class;

    public function mount(): void
    {
        $record = PageSetting::firstOrFail();

        $this->redirect(
            PageSettingResource::getUrl('edit', [
                'record' => $record,
            ])
        );
    }
}
