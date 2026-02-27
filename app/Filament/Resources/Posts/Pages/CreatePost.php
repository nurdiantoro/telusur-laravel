<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    public string $submitStatus = 'published';

    protected function getFormActions(): array
    {
        return [
            Action::make('publish')
                ->label('Publish')
                ->color('primary')
                ->action(function () {
                    $this->submitStatus = 'published';
                    $this->create();
                }),

            Action::make('draft')
                ->label('Draft')
                ->color('gray')
                ->action(function () {
                    $this->submitStatus = 'draft';
                    $this->create(false); // tidak memperdulikan validasi form
                }),

            Action::make('cancel')
                ->label('Cancel')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    // Ubah Status sesuai tombol yang diklik
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = $this->submitStatus ?? 'draft';
        return $data;
    }

    /**
     * Redirect ke LIST setelah create
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
