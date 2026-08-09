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

        if (! $post->image) {
            Log::warning('NO IMAGE FOUND');

            return;
        }

        $path = storage_path(
            'app/public/' . $post->image
        );

        Log::info('CHECK POST IMAGE', [
            'path' => $path,
            'exists' => file_exists($path),
        ]);

        if (! file_exists($path)) {
            Log::error('POST IMAGE FILE NOT FOUND');

            return;
        }

        try {
            Log::info('UPLOAD UPDATED POST IMAGE TO CLOUDINARY');

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/posts'
            );

            Log::info('UPDATED POST IMAGE UPLOADED', [
                'url' => $url,
            ]);

            $post->update([
                'image_url' => $url,
            ]);

            $post->refresh();

            Log::info('POST IMAGE URL UPDATED', [
                'id' => $post->id,
                'image_url' => $post->image_url,
            ]);

            Storage::disk('public')->delete($post->image);

            Log::info('LOCAL POST IMAGE DELETED', [
                'image' => $post->image,
            ]);
        } catch (\Throwable $e) {
            Log::error('CLOUDINARY POST EDIT ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}