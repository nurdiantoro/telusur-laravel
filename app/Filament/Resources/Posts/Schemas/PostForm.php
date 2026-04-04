<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->columnSpanFull()
                ->schema([

                    /* =========================
                     |  KOLOM KIRI – META POST
                     ========================= */
                    Section::make('Post Detail')
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('author_name')
                                ->label('Author')
                                ->disabled()
                                ->dehydrated(false)
                                ->formatStateUsing(
                                    fn($state, $record) =>
                                    $record?->author?->name ?? Auth::user()?->name
                                ),

                            Hidden::make('author_id')
                                ->default(fn() => Auth::id())
                                ->required(),

                            TextInput::make('slug')
                                ->disabled()
                                ->dehydrated()
                                ->unique(ignoreRecord: true)
                                ->required(fn($livewire) => $livewire->submitStatus === 'published'),

                            Select::make('type')
                                ->options([
                                    'post' => 'Post',
                                    'video' => 'Video',
                                ])
                                ->default('post')
                                ->native(false)
                                ->reactive()
                                ->required(),

                            SpatieMediaLibraryFileUpload::make('image')
                                ->disk('public')
                                ->collection('imagesCollection')
                                ->maxSize(2480)
                                ->image()
                                ->imageEditor()
                                ->required(fn($livewire) => $livewire->submitStatus === 'published'),

                            Select::make('gallery_id')
                                ->label('Gallery')
                                ->relationship('gallery', 'title')
                                ->searchable()
                                ->preload(),

                            TextInput::make('video_url')
                                ->label('Video URL')
                                ->disabled(fn($get) => $get('type') !== 'video'),

                            TextInput::make('caption'),

                            Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                // ->multiple()
                                ->preload()
                                ->searchable()
                                ->required(fn($livewire) => $livewire->submitStatus === 'published'),
                            Select::make('tags')
                                ->relationship('tags', 'name')
                                ->multiple()
                                ->searchable()
                                ->searchPrompt('Cari tag...')
                                ->preload()
                                ->noOptionsMessage('Belum ada tag')
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->required(),
                                ]),
                            Select::make('publish_at')
                                ->reactive()
                                ->options([
                                    'immediately' => 'Immediately',
                                    'scheduled' => 'Scheduled',
                                ])
                                ->default('scheduled') // untuk create
                                ->afterStateHydrated(function ($state, callable $set, $record) {

                                    // Cek apakah ini edit (ada record)
                                    if ($record) {
                                        // Jika publish_time sudah ada → post sebelumnya dijadwalkan, default pilih 'scheduled'
                                        // Jika publish_time null → post belum pernah publish, default pilih 'immediately'
                                        if (!$record->publish_time) {
                                            $set('publish_at', 'immediately'); // immediately karena belum ada publish_time
                                        } else {
                                            $set('publish_at', 'scheduled'); // scheduled karena ada waktu publish
                                        }
                                    }
                                    // Note: Saat create, default tetap diatur oleh ->default('scheduled')
                                })
                                ->native(false)
                                ->dehydrated(false)
                                ->required(fn($livewire) => $livewire->submitStatus === 'published'),
                            DateTimePicker::make('publish_time')
                                ->label('Publish Time')
                                ->disabled(fn($get) => $get('publish_at') === 'immediately')
                                ->dehydrated(fn($get) => $get('publish_at') === 'scheduled')
                                ->required(fn($get, $livewire) => $livewire->submitStatus === 'published' && $get('publish_at') === 'scheduled'),
                        ]),

                    /* =========================
                     |  KOLOM KANAN – CONTENT
                     ========================= */
                    Section::make('Content')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->required(fn($livewire) => $livewire->submitStatus === 'published')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, $record) {
                                    if (! $record) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),
                            RichEditor::make('content')
                                ->required(fn($livewire) => $livewire->submitStatus === 'published')
                                ->toolbarButtons([
                                    ['h2', 'h3', 'bold', 'italic', 'underline', 'strike', 'link'],
                                    ['alignStart', 'alignCenter', 'alignEnd'],
                                    ['attachFiles'],
                                    ['undo', 'redo'],
                                ])
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsDirectory('posts')
                                ->fileAttachmentsVisibility('public')
                                ->extraAttributes([
                                    'style' => 'min-height: 700px;',
                                ])
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }
}
