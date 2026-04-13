<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\FiltersResetActionPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('type'),
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
                ToggleColumn::make('headline')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->native(false)
                    ->options([
                        'draft'       => 'Draft',
                        'published'   => 'Published',
                        'unpublished' => 'Unpublished',
                    ]),
                SelectFilter::make('type')
                    ->native(false)
                    ->options([
                        'post' => 'Post',
                        'opini' => 'Opini',
                        'video' => 'Video',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->native(false),
                Filter::make('headline')
                    ->modifyFormFieldUsing(fn(Checkbox $field) => $field->inline(false))
                    ->query(fn(Builder $query): Builder => $query->where('headline', true))
            ], layout: FiltersLayout::AboveContent)
            ->filtersResetActionPosition(FiltersResetActionPosition::Footer)
            ->recordActions([
                EditAction::make(),
                Action::make('activities')
                    ->label('Activities')
                    ->url(fn($record) => PostResource::getUrl('activities', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'active' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('active', true)),
            'inactive' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('active', false)),
        ];
    }
}
