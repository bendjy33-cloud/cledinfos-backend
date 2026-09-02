<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\CloudinaryService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $post = $this->record;

        Log::info('POST EDIT START', [
            'id' => $post->id,
            'image' => $post->image,
            'image_url' => $post->image_url,
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
        | GALLERY IMAGES
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

        Log::info('POST EDIT FINISHED', [
            'id' => $post->id,
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

        if (! $disk->exists($imagePath)) {
            Log::error('MAIN IMAGE FILE NOT FOUND', [
                'post_id' => $post->id,
                'image' => $imagePath,
            ]);

            return;
        }

        /*
         * Si image_url deja egziste, sa vle di foto sa a
         * deja upload sou Cloudinary.
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

            /*
             * Nou pa efase fichye lokal la imedyatman.
             * Cloudinary se storage prensipal la.
             *
             * Kite li la pou evite pwoblèm si Render bezwen
             * fichye a pandan request la.
             */
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
     * Upload gallery image to Cloudinary.
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
         * Pa gen path ditou.
         */
        if (! $imagePath) {
            Log::warning('GALLERY IMAGE HAS NO FILE PATH', [
                'post_image_id' => $postImage->id,
            ]);

            return;
        }

        /*
         * Si image_url deja egziste, pa upload li ankò.
         */
        if ($postImage->image_url) {
            Log::info('GALLERY IMAGE ALREADY ON CLOUDINARY', [
                'post_image_id' => $postImage->id,
                'image_url' => $postImage->image_url,
            ]);

            return;
        }

        /*
         * Verifye fichye a sou disk public.
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