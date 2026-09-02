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

    protected ?string $oldImage = null;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->oldImage = $this->record->image;

        Log::info('POST EDIT BEFORE SAVE', [
            'post_id' => $this->record->id,
            'old_image' => $this->oldImage,
        ]);
    }

    protected function afterSave(): void
    {
        $post = $this->record;

        Log::info('========== POST EDIT START ==========', [
            'post_id' => $post->id,
            'old_image' => $this->oldImage,
            'new_image' => $post->image,
            'image_url' => $post->image_url,
        ]);

        // Upload image principale
        $this->uploadMainImage($post);

        // Upload images gallery
        $post->load('images');

        Log::info('GALLERY IMAGES FOUND', [
            'post_id' => $post->id,
            'count' => $post->images->count(),
        ]);

        foreach ($post->images as $postImage) {
            $this->uploadGalleryImage($postImage);
        }

        Log::info('========== POST EDIT FINISHED ==========', [
            'post_id' => $post->id,
        ]);
    }

    protected function uploadMainImage($post): void
    {
        $disk = Storage::disk('public');

        $imagePath = $post->image;

        Log::info('CHECK MAIN IMAGE', [
            'post_id' => $post->id,
            'old_image' => $this->oldImage,
            'new_image' => $imagePath,
            'image_url' => $post->image_url,
            'exists' => $imagePath
                ? $disk->exists($imagePath)
                : false,
        ]);

        if (! $imagePath) {
            Log::warning('MAIN IMAGE HAS NO PATH', [
                'post_id' => $post->id,
            ]);

            return;
        }

        // Si image la pa chanje epi li deja sou Cloudinary
        if (
            $this->oldImage === $imagePath &&
            $post->image_url
        ) {
            Log::info('MAIN IMAGE NOT CHANGED', [
                'post_id' => $post->id,
                'image_url' => $post->image_url,
            ]);

            return;
        }

        // Verifye nouvo fichye a
        if (! $disk->exists($imagePath)) {
            Log::error('MAIN IMAGE FILE NOT FOUND', [
                'post_id' => $post->id,
                'image' => $imagePath,
                'storage_path' => $disk->path($imagePath),
            ]);

            return;
        }

        try {
            $path = $disk->path($imagePath);

            Log::info('UPLOAD MAIN IMAGE TO CLOUDINARY', [
                'post_id' => $post->id,
                'path' => $path,
            ]);

            $cloudinary = app(CloudinaryService::class);

            $url = $cloudinary->upload(
                $path,
                'cledinfos/posts'
            );

            $post->update([
                'image_url' => $url,
            ]);

            $post->refresh();

            Log::info('MAIN IMAGE UPLOADED SUCCESSFULLY', [
                'post_id' => $post->id,
                'image' => $post->image,
                'image_url' => $post->image_url,
            ]);

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
            Log::warning('GALLERY IMAGE HAS NO PATH', [
                'post_image_id' => $postImage->id,
            ]);

            return;
        }

        // Si li deja sou Cloudinary epi pa gen nouvo fichye lokal
        if (
            ! $disk->exists($imagePath) &&
            $postImage->image_url
        ) {
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
                'storage_path' => $disk->path($imagePath),
            ]);

            return;
        }

        try {
            $path = $disk->path($imagePath);

            Log::info('UPLOAD GALLERY IMAGE TO CLOUDINARY', [
                'post_image_id' => $postImage->id,
                'path' => $path,
            ]);

            $cloudinary = app(CloudinaryService::class);

            $url = $cloudinary->upload(
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

            throw $e;
        }
    }
}