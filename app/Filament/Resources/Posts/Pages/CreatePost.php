<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function handleRecordCreation(array $data): Model
{
    Log::info('HANDLE RECORD CREATION START');

    try {

        $post = parent::handleRecordCreation($data);

        Log::info('PARENT HANDLE SUCCESS', [
            'id' => $post->id,
        ]);

        return $post;

    } catch (\Throwable $e) {

        Log::error('PARENT HANDLE FAILED', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        throw $e;
    }
}
}