<?php

namespace App\Filament\Resources\Infographics;

use App\Filament\Resources\Infographics\Pages\ManageInfographics;
use App\Models\Infographic;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class InfographicResource extends Resource
{
    protected static ?string $model = Infographic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                FileUpload::make('image')
                    ->image()
                    ->automaticallyResizeImagesMode('cover')
                    ->automaticallyResizeImagesToWidth('500')
                    ->automaticallyResizeImagesToHeight('500')
                    ->disk('public')
                    ->required(),
                TextInput::make('link')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default('0'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->afterReordering(function (array $order): void {
                Cache::forget('infographics_cache');
            })
            ->columns([
                TextColumn::make('sort_order'),
                ImageColumn::make('image')
                    ->disk('public')
                    ->square(),
                TextColumn::make('link')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInfographics::route('/'),
        ];
    }
}
