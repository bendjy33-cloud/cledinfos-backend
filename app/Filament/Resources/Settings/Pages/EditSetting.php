<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        Log::info('Logo value', [
            'logo' => $record->logo,
        ]);

        if (! $record->logo) {
            Log::warning('No logo found.');
            return;
        }

        $path = storage_path('app/public/' . $record->logo);

        Log::info('Checking file', [
            'path' => $path,
            'exists' => file_exists($path),
        ]);

        if (! file_exists($path)) {
            Log::warning('Local logo file not found.');
            return;
        }

        try {
            $upload = Cloudinary::upload($path, [
                'folder' => 'cledinfos/settings',
            ]);

            Log::info('Cloudinary upload success', [
                'url' => $upload->getSecurePath(),
            ]);

            $record->update([
                'logo_url' => $upload->getSecurePath(),
            ]);

            Storage::disk('public')->delete($record->logo);

            Log::info('Database updated and local file deleted.');
        } catch (\Throwable $e) {
            Log::error('CLOUDINARY ERROR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}