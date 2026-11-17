<?php

namespace App\Filament\Resources\Posts\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Post;
use Filament\Tables\Columns\TextColumn;

class TopViewedPosts extends TableWidget
{
    protected static ?string $heading = '10 Berita Terpopuler';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {

        return $table
            ->query(
                Post::query()
                    ->where('type', 'post')
                    ->where('status', 'published')
                    ->orderByDesc('views')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Berita')
                    ->searchable()
                    ->limit(60),

                TextColumn::make('views')
                    ->label('Tayangan')
                    ->numeric(decimalPlaces: 0)
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
