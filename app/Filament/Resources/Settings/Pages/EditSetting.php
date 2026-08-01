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

        if (! $record->logo) {
            Log::warning('No logo found.');
            return;
        }

        // Si logo a deja sou Cloudinary, pa re-upload li
        if ($record->logo_url) {
            return;
        }

        $path = storage_path('app/public/' . $record->logo);

        Log::info('Checking file', [
            'path' => $path,
            'exists' => file_exists($path),
        ]);

        if (! file_exists($path)) {
            Log::warning('Logo file not found.');
            return;
        }

        try {

            $upload = Cloudinary::upload($path, [
                'folder' => 'cledinfos/settings',
            ]);

            $record->update([
                'logo_url' => $upload->getSecurePath(),
            ]);

            Storage::disk('public')->delete($record->logo);

            Log::info('Cloudinary upload SUCCESS', [
                'url' => $upload->getSecurePath(),
            ]);

        } catch (\Throwable $e) {

            Log::error('Cloudinary ERROR', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}