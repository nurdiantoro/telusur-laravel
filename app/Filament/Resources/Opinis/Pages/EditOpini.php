<?php

namespace App\Filament\Resources\Opinis\Pages;

use App\Filament\Resources\Opinis\OpiniResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOpini extends EditRecord
{
    protected static string $resource = OpiniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
