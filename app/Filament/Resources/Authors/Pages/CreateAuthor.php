<?php

namespace App\Filament\Resources\Authors\Pages;

use App\Filament\Resources\Authors\AuthorResource;
use App\Models\Author;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateAuthor extends CreateRecord
{
    protected static string $resource = AuthorResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $author = parent::handleRecordCreation($data);

        try {

            Log::info('AUTHOR CREATE START', [
                'id' => $author->id,
                'photo' => $author->photo,
            ]);

            if (! $author->photo) {
                return $author;
            }

            $path = storage_path('app/public/' . $author->photo);

            Log::info('AUTHOR PHOTO', [
                'path' => $path,
                'exists' => file_exists($path),
            ]);

            if (! file_exists($path)) {
                return $author;
            }

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/authors'
            );

            Log::info('AUTHOR CLOUDINARY URL', [
                'url' => $url,
            ]);

            Author::where('id', $author->id)->update([
                'photo_url' => $url,
            ]);

            $author->refresh();

            Log::info('AUTHOR UPDATED', [
                'photo_url' => $author->photo_url,
            ]);

        } catch (\Throwable $e) {

            Log::error('AUTHOR CLOUDINARY ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return $author;
    }
}