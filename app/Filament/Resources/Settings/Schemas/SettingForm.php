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
        $record = $this->record;

        if (! $record->logo) {
            return;
        }

        $path = storage_path('app/public/' . $record->logo);

        if (! file_exists($path)) {
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
        } catch (\Throwable $e) {
            Log::error('LOGO CLOUDINARY ERROR', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}