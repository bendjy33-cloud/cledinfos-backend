<?php

namespace App\Filament\Resources\Ads\Pages;

use App\Filament\Resources\Ads\AdResource;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateAd extends CreateRecord
{
    protected static string $resource = AdResource::class;

    protected function afterCreate(): void
    {
        $ad = $this->record;

        $cloudinary = app(CloudinaryService::class);

        try {

            /*
            |--------------------------------------------------------------------------
            | IMAGE
            |--------------------------------------------------------------------------
            */

            if ($ad->image && !str_starts_with($ad->image, 'http')) {

                $imagePath = storage_path(
                    'app/public/' . $ad->image
                );

                if (file_exists($imagePath)) {

                    $imageUrl = $cloudinary->upload(
                        $imagePath,
                        'cledinfos/ads'
                    );

                    $oldImage = $ad->image;

                    $ad->update([
                        'image' => $imageUrl,
                    ]);

                    Storage::disk('public')->delete($oldImage);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | VIDEO
            |--------------------------------------------------------------------------
            */

            if ($ad->video && !str_starts_with($ad->video, 'http')) {

                $videoPath = storage_path(
                    'app/public/' . $ad->video
                );

                if (file_exists($videoPath)) {

                    $videoUrl = $cloudinary->upload(
                        $videoPath,
                        'cledinfos/ads/videos'
                    );

                    $oldVideo = $ad->video;

                    $ad->update([
                        'video' => $videoUrl,
                    ]);

                    Storage::disk('public')->delete($oldVideo);
                }
            }

            Log::info('AD CREATE SUCCESS', [
                'ad_id' => $ad->id,
                'image' => $ad->image,
                'video' => $ad->video,
            ]);

        } catch (\Throwable $e) {

            Log::error('AD CREATE CLOUDINARY ERROR', [
                'ad_id' => $ad->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}