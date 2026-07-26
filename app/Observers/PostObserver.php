<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\CloudinaryService;

class PostObserver
{
    public function created(Post $post): void
    {
        if ($post->image) {

            $path = storage_path('app/public/' . ltrim($post->image, '/'));

            if (file_exists($path)) {

                $url = app(CloudinaryService::class)
                    ->upload(
                        $path,
                        'cledinfos/posts'
                    );

                $post->update([
                    'image_url' => $url,
                ]);
            }
        }
    }
}