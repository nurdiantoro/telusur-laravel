<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

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
                    $this->save();
                }),

            Action::make('draft')
                ->label('Draft')
                ->color('gray')
                ->action(function () {
                    $this->submitStatus = 'draft';
                    $this->save(false);
                }),

            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = $this->submitStatus;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
