<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    public string $submitStatus = 'draft';

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->submitStatus = $this->record->status;
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('publish')
                ->label('Publish')
                ->color('primary')
                ->action(function () {
                    $this->submitStatus = 'published';
                    $this->save(); // ✔ normal save + validation
                }),

            Action::make('draft')
                ->label('Draft')
                ->color('gray')
                ->visible(fn() => $this->record && in_array($this->record->status, ['draft', 'unpublished']))
                ->action(function () {

                    $this->submitStatus = 'draft';

                    $this->save(false); // skip validasi
                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            Action::make('unpublish')
                ->label('Unpublish')
                ->color('gray')
                ->visible(fn() => $this->record && $this->record->status === 'published')
                ->action(function () {

                    $this->submitStatus = 'unpublished';

                    $this->save(false); // skip validasi
                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
}
