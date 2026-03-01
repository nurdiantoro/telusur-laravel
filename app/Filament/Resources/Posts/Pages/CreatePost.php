<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

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

                    $this->create(false);

                    $this->redirect(
                        $this->getResource()::getUrl('index')
                    );
                }),

            Action::make('cancel')
                ->label('Cancel')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ubah Status sesuai tombol yang diklik
        $data['status'] = $this->submitStatus ?? 'draft';

        // jika user tidak pilih scheduled → publish_time = sekarang
        if ($this->submitStatus === 'published') {
            if (empty($data['publish_time'])) {
                $data['publish_time'] = Carbon::now();
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // pastikan publish_time otomatis saat publish
        if ($this->submitStatus === 'published' && empty($data['publish_time'])) {
            $data['publish_time'] = now();
        }
        return $data;
    }

    /**
     * Redirect ke LIST setelah create
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function canCreateAnother(): bool
    {
        return false;
    }
}
