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
        | 1. FOTO PRINCIPALE
        |--------------------------------------------------------------------------
        */

        if ($post->image) {
            $this->uploadMainImage($post);
        } else {
            Log::warning('POST HAS NO MAIN IMAGE', [
                'post_id' => $post->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. FOTO SIPLEMANTÈ YO
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

        Log::info('POST CREATE FINISHED', [
            'id' => $post->id,
        ]);
    }

    /**
     * Upload foto prensipal la sou Cloudinary.
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

        if (! $disk->exists($imagePath)) {
            Log::error('MAIN IMAGE FILE NOT FOUND', [
                'post_id' => $post->id,
                'image' => $imagePath,
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Si foto a deja gen image_url, pa upload li ankò.
        |--------------------------------------------------------------------------
        */

        if ($post->image_url) {
            Log::info('MAIN IMAGE ALREADY ON CLOUDINARY', [
                'post_id' => $post->id,
                'image_url' => $post->image_url,
            ]);

            return;
        }

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
     * Upload yon foto gallery sou Cloudinary.
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

        /*
        |--------------------------------------------------------------------------
        | Pa gen fichye
        |--------------------------------------------------------------------------
        */

        if (! $imagePath) {
            Log::warning('GALLERY IMAGE HAS NO FILE PATH', [
                'post_image_id' => $postImage->id,
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Foto a deja sou Cloudinary
        |--------------------------------------------------------------------------
        */

        if ($postImage->image_url) {
            Log::info('GALLERY IMAGE ALREADY ON CLOUDINARY', [
                'post_image_id' => $postImage->id,
                'image_url' => $postImage->image_url,
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Verifye fichye lokal la
        |--------------------------------------------------------------------------
        */

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