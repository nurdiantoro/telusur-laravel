<?php

namespace App\Filament\Resources\SidebarAds\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SidebarAdsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('link')
                    ->required()
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('imagesCollection')
                    ->disk('public')
                    ->collection('imagesCollection')
                    ->image()
                    ->imageEditor()
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
