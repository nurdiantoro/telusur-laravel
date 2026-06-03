<?php

namespace App\Filament\Resources\Opinis\Pages;

use App\Filament\Resources\Opinis\OpiniResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOpinis extends ListRecords
{
    protected static string $resource = OpiniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
