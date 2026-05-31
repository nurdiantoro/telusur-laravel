<?php

namespace App\Filament\Resources\PageSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class PageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_header')
                    ->helperText('Ukuran 1000x288, maksimal 2MB')
                    ->image()
                    ->automaticallyResizeImagesMode('force')
                    ->automaticallyResizeImagesToWidth('1000')
                    ->automaticallyResizeImagesToHeight('288')
                    ->disk('public')
                    ->required(),
            ]);
    }
}
