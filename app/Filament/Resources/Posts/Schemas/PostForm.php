<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Gallery;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->columnSpanFull()
                ->schema([

                    /*
                    |  ----------------------------------------
                    |  Kolom Kiri
                    |  1. Author (disabled)
                    |  2. Slug (disabled)
                    |  3. Headline
                    |  4. Type (post, video, opini)
                    |  5. Gallery (jika type video, maka pilih gallery yang sudah diupload sebelumnya)
                    |  6. Video URL (jika type video, maka isi dengan link video)
                    |  7. Caption (jika type video, maka isi dengan caption video)
                    |  8. Category (jika type post, maka pilih kategori)
                    |  9. Tags (bisa multiple, relasi many to many dengan tags)
                    |  10. Publish At (immediately atau scheduled)
                    |  ----------------------------------------
                     */
                    Section::make('Detail Berita')
                        ->columnSpan(1)
                        ->schema([
                            Select::make('author_id')
                                ->label('Author')
                                ->relationship('author', 'name')
                                ->selectablePlaceholder(false)
                                ->default(auth()->id())
                                ->disabled(fn() => ! auth()->user()?->hasPermission('post.edit_author'))
                                ->dehydrated()
                                ->preload()
                                ->searchable()
                                ->required(fn($livewire, $get) => $livewire->submitStatus === 'published' && $get('type') === 'post')
                                ->validationMessages([
                                    'required' => 'Author Wajib Di isi',
                                ]),

                            TextInput::make('slug')
                                ->disabled()
                                ->dehydrated()
                                ->unique(ignoreRecord: true)
                                ->required(fn($livewire) => $livewire->submitStatus === 'published'),

                            Grid::make(2)
                                ->schema([
                                    Toggle::make('headline')
                                        ->onColor('success')
                                        ->inline(false)
                                        ->default(false),

                                    Toggle::make('infografis')
                                        ->onColor('success')
                                        ->inline(false)
                                        ->default(false),
                                ]),


                            Select::make('type')
                                ->label('Tipe')
                                ->options([
                                    'post' => 'Post',
                                    'video' => 'Video',
                                    'opini' => 'Opini',
                                ])
                                ->selectablePlaceholder(false)
                                ->default('post')
                                ->native(false)
                                ->reactive()
                                ->required(),

                            Hidden::make('gallery_id'),
                            View::make('.filament.gallery-picker')
                                ->viewData([
                                    'galleries' => Gallery::with('media')->get(),
                                ]),

                            TextInput::make('video_url')
                                ->label('Video URL')
                                ->prefix('youtube.com/watch?v=')
                                ->helperText(new HtmlString('youtube.com/watch?v=<b>ya7cXK71z4A</b>'))
                                ->hidden(fn($get) => $get('type') !== 'video'),

                            TextInput::make('caption'),

                            Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->selectablePlaceholder(false)
                                ->preload()
                                ->searchable()
                                ->required(fn($livewire, $get) => $livewire->submitStatus === 'published' && $get('type') === 'post')
                                ->validationMessages([
                                    'required' => 'Category Wajib Di isi',
                                ]),

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

                            /*
                            |
                            |
                            |
                            |  ----------------------------------------
                            |  Publish At
                            |  ----------------------------------------
                             */
                            Select::make('publish_at')
                                ->label('Publish Berita')
                                ->reactive()
                                ->options([
                                    'immediately' => 'Langsung Publish',
                                    'scheduled' => 'Jadwalkan',
                                ])
                                ->default('scheduled') // untuk create
                                ->afterStateHydrated(function ($state, callable $set, $record) {

                                    // Cek apakah ini edit, bukan create (ada record)
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
                                ->selectablePlaceholder(false)
                                ->native(false)
                                ->dehydrated(false)
                                ->required(fn($livewire) => $livewire->submitStatus === 'published')
                                ->hidden(function ($get) {
                                    $isPosted = $get('status') === 'published';
                                    return $isPosted;
                                }),
                            DateTimePicker::make('publish_time')
                                ->label('Jadwal Publish')
                                // ->minDate(now())
                                ->disabled(fn($get) => $get('publish_at') === 'immediately')
                                ->dehydrated(fn($get) => $get('publish_at') === 'scheduled')
                                ->required(function ($get, $livewire) {
                                    $isPublished = $livewire->submitStatus === 'published';
                                    $isScheduled = $get('publish_at') === 'scheduled';
                                    return $isPublished && $isScheduled;
                                })
                                /*
                                |  ----------------------------------------
                                |  Data tidak akan disimpan jika :
                                |  publish_at = immediately
                                |  atau
                                |  status nya sudah published (untuk edit post yang sudah published)
                                |  ----------------------------------------
                                */
                                ->dehydrated(function ($get) {
                                    $isScheduled = $get('publish_at') === 'scheduled';
                                    return $isScheduled;
                                }),
                        ]),

                    /*
                     |  ----------------------------------------
                     |  Kolom Kanan
                     |  1. Judul
                     |  2. Content
                     |  ----------------------------------------
                     */
                    Section::make('Konten Berita')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Berita')
                                ->maxLength(255)
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, $record) {
                                    if (! $record) {
                                        $set('slug', Str::slug($state));
                                    }
                                })
                                ->validationMessages([
                                    'required' => 'Title Wajib Di isi',
                                ]),
                            RichEditor::make('content')
                                ->label('Isi Berita')
                                ->required(fn($livewire) => $livewire->submitStatus === 'published')
                                ->validationMessages([
                                    'required' => 'Content Wajib Di isi',
                                ])
                                ->toolbarButtons([
                                    ['h2', 'h3', 'bold', 'italic', 'underline', 'strike', 'link'],
                                    ['alignStart', 'alignCenter', 'alignEnd'],
                                    ['attachFiles'],
                                    ['undo', 'redo'],
                                ])
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsDirectory('posts')
                                ->fileAttachmentsVisibility('public')
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }
}
