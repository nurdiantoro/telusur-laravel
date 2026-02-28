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

            Action::make('draft_or_unpublish')
                ->label(fn() => $this->record && $this->record->status === 'published' ? 'Unpublish' : 'Draft')
                ->color('gray')
                ->action(function () {
                    if ($this->record && $this->record->status === 'published') {
                        // Jika sudah published → ubah menjadi unpublished
                        $this->submitStatus = 'unpublished';
                    } else {
                        // Jika draft / create → tetap draft
                        $this->submitStatus = 'draft';
                    }

                    $this->save(false); // skip validasi
                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            DeleteAction::make(),
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
