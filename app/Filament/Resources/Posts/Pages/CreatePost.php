<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterCreate(): void
    {
        $post = $this->record;

        Log::info('POST CREATE START', [
            'id' => $post->id,
            'image' => $post->image,
            'image_url' => $post->image_url,
        ]);

        /*
        |--------------------------------------------------------------------------
        | FOTO PRINCIPALE
        |--------------------------------------------------------------------------
        */

        if ($post->image) {
            $disk = Storage::disk('public');

            $imagePath = $post->image;

            Log::info('CHECK MAIN IMAGE', [
                'post_id' => $post->id,
                'image' => $imagePath,
                'exists' => $disk->exists($imagePath),
            ]);

            if ($disk->exists($imagePath)) {
                try {
                    $path = $disk->path($imagePath);

                    Log::info('UPLOAD MAIN IMAGE TO CLOUDINARY', [
                        'post_id' => $post->id,
                        'path' => $path,
                    ]);

                    $url = app(CloudinaryService::class)->upload(
                        $path,
                        'cledinfos/posts'
                    );

                    $post->update([
                        'image_url' => $url,
                    ]);

                    $post->refresh();

                    Log::info('MAIN IMAGE UPLOADED SUCCESSFULLY', [
                        'post_id' => $post->id,
                        'image_url' => $post->image_url,
                    ]);
                } catch (\Throwable $e) {
                    Log::error('MAIN IMAGE CLOUDINARY ERROR', [
                        'post_id' => $post->id,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }
            } else {
                Log::error('MAIN IMAGE FILE NOT FOUND', [
                    'post_id' => $post->id,
                    'image' => $imagePath,
                ]);
            }
        } else {
            Log::warning('POST HAS NO MAIN IMAGE', [
                'post_id' => $post->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | FOTO SIPLEMANTÈ YO
        |--------------------------------------------------------------------------
        */

        $post->load('images');

        Log::info('GALLERY IMAGES FOUND', [
            'post_id' => $post->id,
            'count' => $post->images->count(),
        ]);

        foreach ($post->images as $postImage) {

            if (! $postImage->image) {
                Log::warning('GALLERY IMAGE HAS NO FILE', [
                    'post_image_id' => $postImage->id,
                ]);

                continue;
            }

            $disk = Storage::disk('public');

            $imagePath = $postImage->image;

            Log::info('CHECK GALLERY IMAGE', [
                'post_image_id' => $postImage->id,
                'image' => $imagePath,
                'exists' => $disk->exists($imagePath),
            ]);

            if (! $disk->exists($imagePath)) {
                Log::error('GALLERY IMAGE FILE NOT FOUND', [
                    'post_image_id' => $postImage->id,
                    'image' => $imagePath,
                ]);

                continue;
            }

            try {
                $path = $disk->path($imagePath);

                Log::info('UPLOAD GALLERY IMAGE TO CLOUDINARY', [
                    'post_image_id' => $postImage->id,
                    'path' => $path,
                ]);

                $url = app(CloudinaryService::class)->upload(
                    $path,
                    'cledinfos/posts/gallery'
                );

                $postImage->update([
                    'image_url' => $url,
                ]);

                $postImage->refresh();

                Log::info('GALLERY IMAGE UPLOADED SUCCESSFULLY', [
                    'post_image_id' => $postImage->id,
                    'image_url' => $postImage->image_url,
                ]);
            } catch (\Throwable $e) {
                Log::error('GALLERY IMAGE CLOUDINARY ERROR', [
                    'post_image_id' => $postImage->id,
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