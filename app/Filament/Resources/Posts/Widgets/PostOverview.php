<?php

namespace App\Filament\Resources\Posts\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class PostOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $today = now()->startOfDay();
        return [
            Stat::make(
                'Total Berita',
                number_format(
                    Cache::remember('dashboard_total_posts', 60, fn() =>
                    Post::where('type', 'post')->where('status', 'published')->count()),
                    0,
                    ',',
                    '.'
                )
            )
                ->description('Berita yang sudah dipublikasikan')
                ->descriptionIcon('heroicon-m-newspaper'),

            Stat::make(
                'Total Views',
                number_format(
                    Cache::remember('dashboard_total_views', 60, fn() =>
                    Post::sum('views')),
                    0,
                    ',',
                    '.'
                )
            )
                ->description('Akumulasi seluruh tayangan')
                ->descriptionIcon('heroicon-m-eye'),

            Stat::make(
                'Terbit Hari Ini',
                Post::where('type', 'post')
                    ->where('status', 'published')
                    ->where('publish_time', '>=', $today)
                    ->count()
            )
                ->description('Berita dipublikasikan hari ini')
                ->descriptionIcon('heroicon-m-calendar-days'),

            Stat::make(
                'Terjadwal',
                Post::where('type', 'post')
                    ->where('status', 'scheduled')
                    ->count()
            )
                ->description('Menunggu publikasi')
                ->descriptionIcon('heroicon-m-clock'),
        ];
    }
}
