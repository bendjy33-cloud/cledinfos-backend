<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Services\CloudinaryService;
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

        Log::info('RECORD', [
            'logo' => $record->logo,
            'logo_url' => $record->logo_url,
        ]);

        if (! $record->logo) {
            Log::warning('NO LOGO FOUND');
            return;
        }

        if ($record->logo_url) {
            Log::info('LOGO ALREADY ON CLOUDINARY');
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

            Log::info('BEFORE CLOUDINARY');

            Log::info('LOGO PATH', [
                'logo' => $record->logo,
                'path' => $path,
                'exists' => file_exists($path),
            ]);

            $url = app(CloudinaryService::class)->upload(
                $path,
                'cledinfos/settings'
            );

            Log::info('AFTER CLOUDINARY', [
                'url' => $url,
            ]);

           $setting = \App\Models\Setting::find($record->id);

            $setting->logo_url = $url;

            $setting->save();

            Log::info('SETTING SAVED', [
                'logo_url' => $setting->fresh()->logo_url,
            ]);


            Storage::disk('public')->delete($record->logo);

            Log::info('DATABASE UPDATED');

        } catch (\Throwable $e) {

            Log::error('CLOUDINARY ERROR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}