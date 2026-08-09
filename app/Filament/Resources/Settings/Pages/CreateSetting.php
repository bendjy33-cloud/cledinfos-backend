<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function afterCreate(): void
    {
        $setting = $this->record;

        Log::info('SETTING CREATE START', [
            'id' => $setting->id,
            'logo' => $setting->logo,
        ]);

        if (! $setting->logo) {
            Log::warning('NO LOGO FOUND');

            return;
        }

        $path = storage_path(
            'app/public/' . $setting->logo
        );

        Log::info('CHECK LOGO FILE', [
            'path' => $path,
            'exists' => file_exists($path),
        ]);

        if (! file_exists($path)) {
            Log::error('LOGO FILE NOT FOUND');

            return;
        }

        try {
            Log::info('UPLOAD LOGO TO CLOUDINARY');

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/settings'
            );

            Log::info('CLOUDINARY LOGO UPLOADED', [
                'url' => $url,
            ]);

            $setting->update([
                'logo_url' => $url,
            ]);

            $setting->refresh();

            Log::info('SETTING UPDATED', [
                'id' => $setting->id,
                'logo_url' => $setting->logo_url,
            ]);

            Storage::disk('public')->delete($setting->logo);

            Log::info('LOCAL LOGO DELETED', [
                'logo' => $setting->logo,
            ]);
        } catch (\Throwable $e) {
            Log::error('CLOUDINARY LOGO ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}