<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudinaryService;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        Log::info('afterSave START');

        $record = $this->record;

        Log::info('LOGO VALUE', [
            'logo' => $record->logo,
        ]);

        if (! $record->logo) {
            Log::warning('No logo found.');
            return;
        }

        // Si deja upload sou Cloudinary
        if ($record->logo_url) {
            return;
        }

        $path = storage_path('app/public/' . $record->logo);

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
                'cledinfos/settings'
            );

            Log::info('CLOUDINARY DONE', [
                'url' => $url,
            ]);

           $record->update([
                'logo_url' => $url,
            ]);

            $record->refresh();

            Log::info('DATABASE UPDATED', [
                'logo' => $record->logo,
                'logo_url' => $record->logo_url,
            ]);


        } catch (\Throwable $e) {

            Log::error('CLOUDINARY ERROR', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}