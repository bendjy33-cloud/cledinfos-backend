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
            Log::warning('NO IMAGE', [
                'post_id' => $post->id,
            ]);

            return;
        }

        $path = storage_path(
            'app/public/' . $post->image
        );

        Log::info('CHECK FILE', [
            'post_id' => $post->id,
            'path' => $path,
            'exists' => file_exists($path),
            'size' => file_exists($path)
                ? filesize($path)
                : null,
        ]);

        if (! file_exists($path)) {
            Log::error('FILE NOT FOUND', [
                'post_id' => $post->id,
                'path' => $path,
            ]);

            return;
        }

        try {
            Log::info('BEFORE CLOUDINARY UPLOAD', [
                'post_id' => $post->id,
                'path' => $path,
            ]);

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts'
            );

            Log::info('AFTER CLOUDINARY UPLOAD', [
                'post_id' => $post->id,
                'url' => $url,
            ]);

            if (! $url) {
                Log::error('CLOUDINARY RETURNED EMPTY URL', [
                    'post_id' => $post->id,
                ]);

                return;
            }

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
                'post_id' => $post->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}