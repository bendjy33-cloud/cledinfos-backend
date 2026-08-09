<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterCreate(): void
    {
        $post = $this->record;

        Log::info('POST CREATE START', [
            'id' => $post->id,
            'image' => $post->image,
        ]);

        if (! $post->image) {
            Log::warning('NO IMAGE');

            return;
        }

        $path = storage_path(
            'app/public/' . $post->image
        );

        Log::info('CHECK FILE', [
            'path' => $path,
            'exists' => file_exists($path),
        ]);

        if (! file_exists($path)) {
            Log::error('FILE NOT FOUND');

            return;
        }

        try {
            Log::info('UPLOAD POST IMAGE TO CLOUDINARY');

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts'
            );

            Log::info('CLOUDINARY POST IMAGE UPLOADED', [
                'url' => $url,
            ]);

            $post->update([
                'image_url' => $url,
            ]);

            $post->refresh();

            Log::info('POST DATABASE UPDATED', [
                'id' => $post->id,
                'image_url' => $post->image_url,
            ]);
        } catch (\Throwable $e) {
            Log::error('CLOUDINARY POST IMAGE ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}