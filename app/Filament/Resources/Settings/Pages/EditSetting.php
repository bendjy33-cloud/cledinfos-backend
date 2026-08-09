<?php
namespace App\Filament\Resources\Settings\Pages;
use App\Filament\Resources\Settings\SettingResource;
use App\Services\CloudinaryService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;
    protected function getHeaderActions(): array
    {
        return [DeleteAction::make(),];
    }
    protected function afterSave(): void
    {
        $setting = $this->record;
        Log::info('SETTING EDIT START', ['id' => $setting->id, 'logo' => $setting->logo, 'logo_url' => $setting->logo_url,]);
        if (!$setting->logo) {
            Log::warning('NO LOGO FOUND');
            return;
        }
        $path = storage_path('app/public/' . $setting->logo);
        Log::info('CHECK SETTING LOGO', ['path' => $path, 'exists' => file_exists($path),]);
        if (!file_exists($path)) {
            Log::error('SETTING LOGO FILE NOT FOUND');
            return;
        }
        try {
            Log::info('UPLOAD UPDATED LOGO TO CLOUDINARY');
            $url = app(CloudinaryService::class)->upload($path, 'cledinfos/settings');
            Log::info('UPDATED LOGO UPLOADED', ['url' => $url,]);
            $setting->update(['logo_url' => $url,]);
            $setting->refresh();
            Log::info('SETTING LOGO URL UPDATED', ['id' => $setting->id, 'logo_url' => $setting->logo_url,]);
        } catch (\Throwable $e) {
            Log::error('CLOUDINARY SETTING EDIT ERROR', ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine(),]);
        }
    }
}