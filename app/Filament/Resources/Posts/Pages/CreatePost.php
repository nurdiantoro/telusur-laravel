<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Carbon\Carbon;
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

    /*
    |--------------------------------------------------------------------------
    | mutateFormDataBeforeCreate
    |--------------------------------------------------------------------------
    |
    | Fungsi ini adalah "checkpoint terakhir" sebelum data form disimpan ke database (create).
    | Dipakai di Filament saat proses CREATE record.
    | Wajib return array $data yang sudah dimodifikasi
    | Flow sederhananya:
    | User submit form → Validasi → Masuk ke function ini → Data dimodifikasi → Disimpan ke database
    |
    */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /*
        |
        |
        | Kalau pilih published
        */
        if ($this->submitStatus === 'published') {
            /*
            |
            |
            | Jika publish_at = immediately
            | langsung set publish_time ke sekarang
            */
            if ($this->data['publish_at'] === 'immediately') {
                $data['publish_time'] = now();
            }
            /*
            |
            |
            | Jika publish_at = scheduled
            | cek apakah waktunya sudah lewat atau belum
            */
            if ($this->data['publish_at'] === 'scheduled' && !empty($data['publish_time'])) {
                if (Carbon::parse($data['publish_time'])->isFuture()) {
                    $data['status'] = 'pending';
                }
            }
        } else {
            $data['status'] = $this->submitStatus;
        }

        return $data;
    }

    /*
    |
    |
    | Redirect ke LIST setelah create
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
