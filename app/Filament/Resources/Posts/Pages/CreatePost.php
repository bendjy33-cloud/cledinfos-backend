<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\CloudinaryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        try {

            $post = parent::handleRecordCreation($data);


            if ($post->image) {

                $path = storage_path(
                    'app/public/' . $post->image
                );


                if (file_exists($path)) {

                    $url = app(CloudinaryService::class)->upload(
                        $path,
                        'cledinfos/posts'
                    );


                    $post->updateQuietly([
                        'image_url' => $url,
                    ]);


                    Log::info('Cloudinary upload success', [
                        'url' => $url,
                    ]);
                }
                else {

                    Log::error('Image file not found', [
                        'path' => $path,
                    ]);
                }
            }


            return $post;


        } catch (\Throwable $e) {

            dd(
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
        }
    }
}