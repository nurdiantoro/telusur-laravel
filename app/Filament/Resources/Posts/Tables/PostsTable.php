<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('publish_time')
                    ->label('Publish')
                    ->formatStateUsing(
                        fn($state) =>
                        $state
                            ? $state->format('d M Y') . ' - ' . $state->format('H:i')
                            : '-'
                    )
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'published'   => 'success', // hijau
                        'draft'       => 'warning', // kuning
                        'unpublished' => 'danger',  // merah
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft'       => 'Draft',
                        'published'   => 'Published',
                        'unpublished' => 'Unpublished',
                    ])
                    ->default('draft'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
