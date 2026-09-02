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
            'original_image' => $post->getOriginal('image'),
            'image_changed' => $post->getOriginal('image') !== $post->image,
        ]);

        /*
        |--------------------------------------------------------------------------
        | MAIN IMAGE
        |--------------------------------------------------------------------------
        */

        $this->uploadMainImage($post);

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

        /*
        |--------------------------------------------------------------------------
        | Verifye si image la chanje
        |--------------------------------------------------------------------------
        */

        $originalImage = $post->getOriginal('image');

        $imageChanged = $originalImage !== $imagePath;

        Log::info('CHECK MAIN IMAGE', [
            'post_id' => $post->id,
            'image' => $imagePath,
            'original_image' => $originalImage,
            'image_url' => $post->image_url,
            'image_changed' => $imageChanged,
            'exists' => $imagePath
                ? $disk->exists($imagePath)
                : false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pa gen nouvo image
        |--------------------------------------------------------------------------
        */

        if (! $imagePath) {
            Log::warning('MAIN IMAGE HAS NO FILE PATH', [
                'post_id' => $post->id,
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Si image lan pa chanje epi image_url deja egziste,
        | pa bezwen upload li ankò.
        |--------------------------------------------------------------------------
        */

        if (! $imageChanged && $post->image_url) {
            Log::info('MAIN IMAGE NOT CHANGED - ALREADY ON CLOUDINARY', [
                'post_id' => $post->id,
                'image_url' => $post->image_url,
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Verifye nouvo fichye a sou disk public
        |--------------------------------------------------------------------------
        */

        if (! $disk->exists($imagePath)) {
            Log::error('MAIN IMAGE FILE NOT FOUND', [
                'post_id' => $post->id,
                'image' => $imagePath,
            ]);

            return;
        }

        try {
            $path = $disk->path($imagePath);

            Log::info('UPLOAD MAIN IMAGE TO CLOUDINARY', [
                'post_id' => $post->id,
                'path' => $path,
                'image_changed' => $imageChanged,
            ]);

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts'
            );

            /*
            |--------------------------------------------------------------------------
            | Mete nouvo URL Cloudinary a
            |--------------------------------------------------------------------------
            */

            $post->update([
                'image_url' => $url,
            ]);

            Log::info('MAIN IMAGE UPLOADED SUCCESSFULLY', [
                'post_id' => $post->id,
                'url' => $url,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Nou pa efase fichye lokal la kounye a.
            |--------------------------------------------------------------------------
            */

        } catch (\Throwable $e) {
            Log::error('CLOUDINARY MAIN IMAGE ERROR', [
                'post_id' => $post->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }

    /**
     * Upload gallery image to Cloudinary.
     */
    protected function uploadGalleryImage($postImage): void
    {
        $disk = Storage::disk('public');

        $imagePath = $postImage->image;

        /*
        |--------------------------------------------------------------------------
        | Chèche ansyen image path la
        |--------------------------------------------------------------------------
        */

        $originalImage = $postImage->getOriginal('image');

        $imageChanged = $originalImage !== $imagePath;

        Log::info('CHECK GALLERY IMAGE', [
            'post_image_id' => $postImage->id,
            'post_id' => $postImage->post_id,
            'image' => $imagePath,
            'original_image' => $originalImage,
            'image_url' => $postImage->image_url,
            'image_changed' => $imageChanged,
            'exists' => $imagePath
                ? $disk->exists($imagePath)
                : false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pa gen image
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
        | Si image lan pa chanje epi li deja sou Cloudinary,
        | pa upload ankò.
        |--------------------------------------------------------------------------
        */

        if (! $imageChanged && $postImage->image_url) {
            Log::info('GALLERY IMAGE NOT CHANGED - ALREADY ON CLOUDINARY', [
                'post_image_id' => $postImage->id,
                'image_url' => $postImage->image_url,
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Verifye fichye a
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
                'image_changed' => $imageChanged,
            ]);

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts/gallery'
            );

            /*
            |--------------------------------------------------------------------------
            | Mete nouvo URL Cloudinary a
            |--------------------------------------------------------------------------
            */

            $postImage->update([
                'image_url' => $url,
            ]);

            $postImage->refresh();

            Log::info('GALLERY IMAGE UPLOADED SUCCESSFULLY', [
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

            throw $e;
        }
    }
}