<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;


    protected function afterCreate(): void
    {
        $post = $this->record;


        Log::info('AFTER CREATE START', [
            'id' => $post->id,
            'image' => $post->image,
        ]);


        if (! $post->image) {

            Log::warning('NO IMAGE');

            return;
        }


        $path = storage_path(
            'app/public/' . $post->image
        );


        Log::info('CHECK FILE', [
            'path' => $path,
            'exists' => file_exists($path),
        ]);


        if (! file_exists($path)) {

            Log::error('FILE NOT FOUND');

            return;
        }


        try {

            Log::info('UPLOAD CLOUDINARY START');


            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts'
            );


            Log::info('CLOUDINARY DONE', [
                'url' => $url,
            ]);


            $post->update([
                'image_url' => $url,
            ]);


            $post->refresh();


            Log::info('DATABASE SAVED', [
                'id' => $post->id,
                'image_url' => $post->image_url,
            ]);


        } catch (\Throwable $e) {


            Log::error('CLOUDINARY ERROR', [
                'message' => $e->getMessage(),
            ]);

        }

    }
}