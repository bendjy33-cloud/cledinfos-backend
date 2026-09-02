<?php

namespace App\Filament\Resources\Ads\Pages;

use App\Filament\Resources\Ads\AdResource;
use App\Services\CloudinaryService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditAd extends EditRecord
{
    protected static string $resource = AdResource::class;

    protected function afterSave(): void
    {
        $ad = $this->record;

        try {
            $cloudinary = app(CloudinaryService::class);

            /*
            |--------------------------------------------------------------------------
            | IMAGE
            |--------------------------------------------------------------------------
            */

            if (
                $ad->image &&
                !str_starts_with($ad->image, 'http')
            ) {
                $oldImage = $ad->image;

                $imagePath = storage_path(
                    'app/public/' . $oldImage
                );

                if (file_exists($imagePath)) {

                    $imageUrl = $cloudinary->upload(
                        $imagePath,
                        'cledinfos/ads'
                    );

                    $ad->update([
                        'image' => $imageUrl,
                    ]);

                    // Delete temporary local file
                    Storage::disk('public')->delete(
                        $oldImage
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | VIDEO
            |--------------------------------------------------------------------------
            */

            if (
                $ad->video &&
                !str_starts_with($ad->video, 'http')
            ) {
                $oldVideo = $ad->video;

                $videoPath = storage_path(
                    'app/public/' . $oldVideo
                );

                if (file_exists($videoPath)) {

                    $videoUrl = $cloudinary->upload(
                        $videoPath,
                        'cledinfos/ads/videos'
                    );

                    $ad->update([
                        'video' => $videoUrl,
                    ]);

                    // Delete temporary local file
                    Storage::disk('public')->delete(
                        $oldVideo
                    );
                }
            }

            Log::info('AD UPDATE SUCCESS', [
                'ad_id' => $ad->id,
                'image' => $ad->image,
                'video' => $ad->video,
            ]);

        } catch (\Throwable $e) {

            Log::error('AD UPDATE CLOUDINARY ERROR', [
                'ad_id' => $ad->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}