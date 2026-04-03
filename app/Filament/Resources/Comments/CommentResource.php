<?php

namespace App\Filament\Resources\Comments;

use App\Filament\Resources\Comments\Pages\ManageComments;
use App\Models\Comment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Pest\Support\View;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    // disable tombol create comment
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('comments.read');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermission('comments.update');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermission('comments.delete');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Post Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->disabled()
                    ->required(),
                TextInput::make('email')
                    ->disabled()
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('post.title')
                    ->disabled()
                    ->formatStateUsing(fn($record) => $record?->post?->title),
                TextInput::make('comment')
                    ->disabled()
                    ->required(),
                Select::make('status')
                    ->native(false)
                    ->required()
                    ->selectablePlaceholder(false)
                    ->options([
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('post.title')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('comment')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'approved'  => 'success', // hijau
                        'pending'   => 'warning', // kuning
                        'rejected'  => 'danger',  // merah
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                'status' => SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
            ])
            ->recordActions([
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageComments::route('/'),
        ];
    }
}
