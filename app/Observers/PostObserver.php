<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    public function created(Post $post): void
    {
        Log::info('OBSERVER TEST', [
            'post' => $post->id,
            'image' => $post->image,
        ]);

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
                Log::info('No image found', [
                    'post_id' => $post->id
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
                'line' => $e->getLine(),
            ]);
        }
    }
}