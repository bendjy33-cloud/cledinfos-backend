<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\CloudinaryService;

class PostObserver
{
    public function created(Post $post): void
    {
        try {

            if ($post->image) {

                $path = storage_path('app/public/' . ltrim($post->image, '/'));

                if (file_exists($path)) {

                    $url = app(\App\Services\CloudinaryService::class)
                        ->upload(
                            $path,
                            'cledinfos/posts'
                        );

                    $post->updateQuietly([
                        'image_url' => $url,
                    ]);
                }
            }

        } catch (\Throwable $e) {

            \Log::error('Observer Cloudinary Error', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}