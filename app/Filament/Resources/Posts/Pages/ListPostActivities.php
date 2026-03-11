<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Models\PostCategory;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListPostActivities extends ListActivities
{
    protected static string $resource = PostResource::class;

    protected array $relations = [
        'category_id' => [
            'model' => PostCategory::class,
            'attribute' => 'name',
        ],
    ];
}
