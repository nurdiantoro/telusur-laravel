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
                    Section::make('Post Detail')
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('author_name')
                                ->label('Author')
                                ->disabled()
                                ->dehydrated(false)
                                ->afterStateHydrated(function ($state, $set, $record) {
                                    if ($record) {
                                        $set('author_name', $record->author?->name);
                                    } else {
                                        $set('author_name', Auth::user()?->name);
                                    }
                                }),

                            Hidden::make('author_id')
                                ->default(fn() => Auth::id())
                                ->required(),

                            TextInput::make('slug')
                                ->disabled()
                                ->dehydrated()
                                ->unique(ignoreRecord: true)
                                ->required(fn($livewire) => $livewire->submitStatus === 'published'),

                            Toggle::make('headline')
                                ->onColor('success')
                                ->inline(false)
                                ->default(false),

                            Select::make('type')
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
                                    'required' => 'Category is required',
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
                                ->reactive()
                                ->options([
                                    'immediately' => 'Immediately',
                                    'scheduled' => 'Scheduled',
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
                                ->label('Publish Time')
                                ->minDate(now())
                                ->disabled(fn($get) => $get('publish_at') === 'immediately')
                                ->dehydrated(fn($get) => $get('publish_at') === 'scheduled')
                                ->required(function ($get, $livewire) {
                                    $isPublished = $livewire->submitStatus === 'published';
                                    $isScheduled = $get('publish_at') === 'scheduled';
                                    return $isPublished && $isScheduled;
                                })
                                ->validationMessages([
                                    'after_or_equal' => 'Waktu publish tidak boleh melewati waktu saat ini',
                                ])
                                /*
                                |  ----------------------------------------
                                |  Data tidak akan disimpan jika :
                                |  publish_at = immediately
                                |  atau
                                |  status nya sudah published (untuk edit post yang sudah published)
                                |  ----------------------------------------
                                */
                                ->dehydrated(function ($get) {
                                    $isPublished = $get('status') === 'published';
                                    $isScheduled = $get('publish_at') === 'scheduled';
                                    return $isPublished || $isScheduled;
                                })
                                ->hidden(function ($get) {
                                    $isPosted = $get('status') === 'published';
                                    return $isPosted;
                                }),
                        ]),

                    /*
                     |  ----------------------------------------
                     |  Kolok Kanan
                     |  1. Judul
                     |  2. Content
                     |  ----------------------------------------
                     */
                    Section::make('Content')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, $record) {
                                    if (! $record) {
                                        $set('slug', Str::slug($state));
                                    }
                                })
                                ->validationMessages([
                                    'required' => 'Title is required',
                                ]),
                            RichEditor::make('content')
                                ->required(fn($livewire) => $livewire->submitStatus === 'published')
                                ->validationMessages([
                                    'required' => 'Content is required',
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
