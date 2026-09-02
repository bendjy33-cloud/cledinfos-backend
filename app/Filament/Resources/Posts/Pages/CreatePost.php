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
        ]);

        /*
        |--------------------------------------------------------------------------
        | MAIN IMAGE
        |--------------------------------------------------------------------------
        */

        if ($post->image) {
            $this->uploadMainImage($post);
        }

        /*
        |--------------------------------------------------------------------------
        | GALLERY / SLIDES
        |--------------------------------------------------------------------------
        */

        $post->load('images');

        Log::info('GALLERY IMAGES FOUND', [
            'post_id' => $post->id,
            'count' => $post->images->count(),
        ]);

        foreach ($post->images as $postImage) {
            $this->uploadGalleryImage($postImage);
        }

        $post->refresh();
        $post->load('images');

        Log::info('POST CREATE FINISHED', [
            'id' => $post->id,
            'image_url' => $post->image_url,
            'gallery_count' => $post->images->count(),
        ]);
    }

    /**
     * Upload main post image to Cloudinary.
     */
    protected function uploadMainImage($post): void
    {
        $disk = Storage::disk('public');

        $imagePath = $post->image;

        Log::info('CHECK MAIN IMAGE', [
            'post_id' => $post->id,
            'image' => $imagePath,
            'exists' => $disk->exists($imagePath),
        ]);

        if (! $imagePath) {
            return;
        }

        if ($post->image_url) {
            Log::info('MAIN IMAGE ALREADY ON CLOUDINARY', [
                'post_id' => $post->id,
                'image_url' => $post->image_url,
            ]);

            return;
        }

        if (! $disk->exists($imagePath)) {
            Log::error('MAIN IMAGE FILE NOT FOUND', [
                'post_id' => $post->id,
                'image' => $imagePath,
            ]);

            return;
        }

        try {
            $path = $disk->path($imagePath);

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts'
            );

            if (! $url) {
                Log::error('MAIN IMAGE CLOUDINARY RETURNED EMPTY URL', [
                    'post_id' => $post->id,
                ]);

                return;
            }

            $post->update([
                'image_url' => $url,
            ]);

            Log::info('MAIN IMAGE UPLOADED', [
                'post_id' => $post->id,
                'url' => $url,
            ]);

        } catch (\Throwable $e) {
            Log::error('CLOUDINARY MAIN IMAGE ERROR', [
                'post_id' => $post->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * Upload gallery / slide image to Cloudinary.
     */
    protected function uploadGalleryImage($postImage): void
    {
        $disk = Storage::disk('public');

        $imagePath = $postImage->image;

        Log::info('CHECK GALLERY IMAGE', [
            'post_image_id' => $postImage->id,
            'post_id' => $postImage->post_id,
            'image' => $imagePath,
            'image_url' => $postImage->image_url,
            'exists' => $imagePath
                ? $disk->exists($imagePath)
                : false,
        ]);

        if (! $imagePath) {
            Log::warning('GALLERY IMAGE HAS NO FILE PATH', [
                'post_image_id' => $postImage->id,
            ]);

            return;
        }

        if ($postImage->image_url) {
            Log::info('GALLERY IMAGE ALREADY ON CLOUDINARY', [
                'post_image_id' => $postImage->id,
                'image_url' => $postImage->image_url,
            ]);

            return;
        }

        if (! $disk->exists($imagePath)) {
            Log::error('GALLERY IMAGE FILE NOT FOUND', [
                'post_image_id' => $postImage->id,
                'image' => $imagePath,
            ]);

            return;
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

            if (! $url) {
                Log::error('GALLERY CLOUDINARY RETURNED EMPTY URL', [
                    'post_image_id' => $postImage->id,
                ]);

                return;
            }

            $postImage->update([
                'image_url' => $url,
            ]);

            $postImage->refresh();

            Log::info('GALLERY IMAGE UPLOADED', [
                'post_image_id' => $postImage->id,
                'url' => $postImage->image_url,
            ]);

        } catch (\Throwable $e) {
            Log::error('CLOUDINARY GALLERY IMAGE ERROR', [
                'post_image_id' => $postImage->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}