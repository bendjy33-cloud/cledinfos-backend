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

        /*
        |--------------------------------------------------------------------------
        | 1. FOTO PRINCIPALE
        |--------------------------------------------------------------------------
        */

        if ($post->image) {
            $path = storage_path(
                'app/public/' . $post->image
            );

            Log::info('CHECK MAIN IMAGE', [
                'path' => $path,
                'exists' => file_exists($path),
            ]);

            if (file_exists($path)) {
                try {
                    Log::info('UPLOAD MAIN POST IMAGE TO CLOUDINARY');

                    $url = app(CloudinaryService::class)->upload(
                        $path,
                        'cledinfos/posts'
                    );

                    $post->update([
                        'image_url' => $url,
                    ]);

                    Log::info('MAIN POST IMAGE UPLOADED', [
                        'url' => $url,
                    ]);
                } catch (\Throwable $e) {
                    Log::error('MAIN IMAGE CLOUDINARY ERROR', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. FOTO SIPLEMANTÈ YO
        |--------------------------------------------------------------------------
        */

        $post->load('images');

        foreach ($post->images as $postImage) {

            if (! $postImage->image) {
                continue;
            }

            $path = storage_path(
                'app/public/' . $postImage->image
            );

            Log::info('CHECK GALLERY IMAGE', [
                'id' => $postImage->id,
                'image' => $postImage->image,
                'path' => $path,
                'exists' => file_exists($path),
            ]);

            if (! file_exists($path)) {
                Log::warning('GALLERY IMAGE FILE NOT FOUND', [
                    'id' => $postImage->id,
                    'path' => $path,
                ]);

                continue;
            }

            try {
                Log::info('UPLOAD GALLERY IMAGE TO CLOUDINARY', [
                    'id' => $postImage->id,
                ]);

                $url = app(CloudinaryService::class)->upload(
                    $path,
                    'cledinfos/posts/gallery'
                );

                $postImage->update([
                    'image_url' => $url,
                ]);

                Log::info('GALLERY IMAGE UPLOADED', [
                    'id' => $postImage->id,
                    'url' => $url,
                ]);

            } catch (\Throwable $e) {
                Log::error('GALLERY IMAGE CLOUDINARY ERROR', [
                    'id' => $postImage->id,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        }

        Log::info('POST CREATE FINISHED', [
            'id' => $post->id,
        ]);
    }
}