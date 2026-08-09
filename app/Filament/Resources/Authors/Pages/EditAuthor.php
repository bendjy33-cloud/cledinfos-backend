<?php

namespace App\Filament\Resources\Authors\Pages;

use App\Filament\Resources\Authors\AuthorResource;
use App\Services\CloudinaryService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditAuthor extends EditRecord
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $author = $this->record;

        Log::info('AUTHOR EDIT START', [
            'id' => $author->id,
            'photo' => $author->photo,
            'photo_url' => $author->photo_url,
        ]);

        if (! $author->photo) {
            Log::warning('NO PHOTO FOUND');

            return;
        }

        $path = storage_path(
            'app/public/' . $author->photo
        );

        Log::info('CHECK AUTHOR PHOTO', [
            'path' => $path,
            'exists' => file_exists($path),
        ]);

        if (! file_exists($path)) {
            Log::error('AUTHOR PHOTO FILE NOT FOUND');

            return;
        }

        try {
            Log::info('UPLOAD UPDATED AUTHOR PHOTO TO CLOUDINARY');

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/authors'
            );

            Log::info('UPDATED AUTHOR PHOTO UPLOADED', [
                'url' => $url,
            ]);

            $author->update([
                'photo_url' => $url,
            ]);

            $author->refresh();

            Log::info('AUTHOR PHOTO URL UPDATED', [
                'id' => $author->id,
                'photo_url' => $author->photo_url,
            ]);

            Storage::disk('public')->delete($author->photo);

            Log::info('LOCAL AUTHOR PHOTO DELETED', [
                'photo' => $author->photo,
            ]);
        } catch (\Throwable $e) {
            Log::error('CLOUDINARY AUTHOR EDIT ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}