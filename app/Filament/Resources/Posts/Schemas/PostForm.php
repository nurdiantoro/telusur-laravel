<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(fn($livewire) => $livewire->submitStatus === 'published')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $record) {
                        if (!$record) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->required(fn($livewire) => $livewire->submitStatus === 'published')
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->required()
                    ->options([
                        'post' => 'post',
                        'video' => 'video',
                    ])
                    ->default('post')
                    ->native(false),
                SpatieMediaLibraryFileUpload::make('cover')
                    ->disk('public')
                    ->collection('cover')
                    ->image(),
                TextInput::make('cover_thumbnail'),
                TextInput::make('caption'),
                TextInput::make('video_url')
                    ->url(),
                RichEditor::make('content')
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                        ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                        ['table'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                        ['undo', 'redo'],
                    ])
                    ->required(fn($livewire) => $livewire->submitStatus === 'published')
                    ->columnSpanFull(),
                TextInput::make('status_id')
                    ->required(fn($livewire) => $livewire->submitStatus === 'published')
                    ->numeric(),
                TextInput::make('category_id')
                    ->numeric(),
                TextInput::make('author_id')
                    ->required(fn($livewire) => $livewire->submitStatus === 'published')
                    ->numeric(),
                TextInput::make('views')
                    ->required(fn($livewire) => $livewire->submitStatus === 'published')
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('publish_time'),
            ]);
    }
}
