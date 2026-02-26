<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->unique()
                    // ->readOnly()
                    ->required(),
                TextInput::make('post_type_id')
                    ->numeric(),
                FileUpload::make('cover')
                    ->disk('public')
                    ->visibility('public')
                    ->image()
                    ->automaticallyCropImagesToAspectRatio('16:9')
                    ->automaticallyResizeImagesMode('cover')
                    ->automaticallyResizeImagesToWidth('1920')
                    ->automaticallyResizeImagesToHeight('1080'),
                // TextInput::make('cover'),
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
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status_id')
                    ->required()
                    ->numeric(),
                TextInput::make('category_id')
                    ->numeric(),
                TextInput::make('author_id')
                    ->required()
                    ->numeric(),
                TextInput::make('views')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('publish_time'),
            ]);
    }
}
