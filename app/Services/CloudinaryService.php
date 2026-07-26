<?php

namespace App\Services;

use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    public function upload(string $path, string $folder): string
    {
        try {

            $config = [
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud.cloud_name'),
                    'api_key' => config('cloudinary.cloud.api_key'),
                    'api_secret' => config('cloudinary.cloud.api_secret'),
                ],
            ];

            $upload = new UploadApi($config);

            $result = $upload->upload($path, [
                'folder' => $folder,
            ]);

            return $result['secure_url'];

        } catch (\Throwable $e) {

            Log::error('Cloudinary Upload Error', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}