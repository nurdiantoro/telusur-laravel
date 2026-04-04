<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPostActivities;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'Post';

    public static function getNavigationGroup(): ?string

    {
        return 'Post Management';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    /*
    |--------------------------------------------------------------------------
    | Resource Authorization
    |--------------------------------------------------------------------------
    |
    | Bagian ini mengatur semua akses CRUD untuk resource Post.
    | Semua permission diambil dari sistem permission custom:
    | post.read, post.create, post.update, post.delete
    |
    | Controller akses sepenuhnya dikendalikan oleh role permission
    |
    */

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('post.read');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermission('post.create');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermission('post.update');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermission('post.delete');
    }

    /*
    |--------------------------------------------------------------------------
    | Form Schema
    |--------------------------------------------------------------------------
    |
    | Definisi form untuk create dan edit Post.
    | Dipisahkan ke class PostForm agar lebih modular.
    |
    */

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    /*
    |--------------------------------------------------------------------------
    | Table Schema
    |--------------------------------------------------------------------------
    |
    | Definisi tabel listing data Post di admin panel.
    | Menggunakan class terpisah PostsTable untuk maintainability.
    |
    */

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    | Relasi antar resource (misal categories, tags, dll).
    | Saat ini belum digunakan.
    |
    */

    public static function getRelations(): array
    {
        return [
            // 'categories' => PostCategoryResource::class,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Page Routing
    |--------------------------------------------------------------------------
    |
    | Mapping halaman-halaman yang tersedia dalam PostResource.
    | Semua route Filament didefinisikan di sini.
    |
    */

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
            'activities' => ListPostActivities::route('/{record}/activities'),
        ];
    }
}
