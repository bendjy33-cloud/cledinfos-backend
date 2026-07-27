<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    public function created(Post $post): void
    {
        $this->uploadImageToCloudinary($post);
    }

    public function updated(Post $post): void
    {
        if ($post->isDirty('image')) {
            $this->uploadImageToCloudinary($post);
        }
    }


    private function uploadImageToCloudinary(Post $post): void
    {
        try {

            if (!$post->image) {
                Log::info('No image found for post', [
                    'post_id' => $post->id
                ]);

                return;
            }


            // Si image la deja yon URL, pa repete upload
            if (str_starts_with($post->image, 'http')) {

                Log::info('Image already URL', [
                    'image' => $post->image
                ]);

                return;
            }


            $path = storage_path(
                'app/public/' . ltrim($post->image, '/')
            );


            Log::info('Checking image path', [
                'path' => $path,
                'exists' => file_exists($path),
            ]);


            if (!file_exists($path)) {

                Log::error('Local image not found', [
                    'path' => $path
                ]);

                return;
            }


            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts'
            );


            Log::info('Cloudinary upload success', [
                'url' => $url
            ]);


            $post->updateQuietly([
                'image_url' => $url,
            ]);


        } catch (\Throwable $e) {

            Log::error('Observer Cloudinary Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}