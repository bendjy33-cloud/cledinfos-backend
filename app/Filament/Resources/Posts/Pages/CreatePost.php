<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        Log::info('HANDLE RECORD CREATION START');

        $post = parent::handleRecordCreation($data);

        try {

            Log::info('POST CREATED', [
                'id' => $post->id,
                'image' => $post->image,
            ]);

            if (! $post->image) {
                Log::warning('NO IMAGE FOUND');
                return $post;
            }

            $path = storage_path('app/public/' . $post->image);

            Log::info('LOCAL IMAGE', [
                'path' => $path,
                'exists' => file_exists($path),
            ]);

            if (! file_exists($path)) {
                Log::error('LOCAL FILE NOT FOUND');
                return $post;
            }

            Log::info('START CLOUDINARY');

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts'
            );

            Log::info('CLOUDINARY URL', [
                'url' => $url,
            ]);

            $post->image_url = $url;

            Log::info('BEFORE SAVE', [
                'image_url' => $post->image_url,
            ]);

            $saved = $post->save();

            Log::info('SAVE RESULT', [
                'saved' => $saved,
            ]);

            $post->refresh();

            Log::info('IMAGE URL SAVED', [
                'id' => $post->id,
                'image_url' => $post->image_url,
            ]);

        } catch (\Throwable $e) {

            Log::error('Cloudinary upload failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return $post;
    }
}