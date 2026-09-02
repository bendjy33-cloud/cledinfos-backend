<?php

namespace App\Services;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    public function upload(string $path, string $folder): string
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Detect resource type
        |--------------------------------------------------------------------------
        */

        $extension = strtolower(
            pathinfo($path, PATHINFO_EXTENSION)
        );

        $videoExtensions = [
            'mp4',
            'webm',
            'ogg',
            'mov',
            'avi',
            'mkv',
            'm4v',
        ];

        $resourceType = in_array(
            $extension,
            $videoExtensions,
            true
        )
            ? 'video'
            : 'image';

        /*
        |--------------------------------------------------------------------------
        | Upload to Cloudinary
        |--------------------------------------------------------------------------
        */

        $result = (new UploadApi())->upload($path, [
            'folder' => $folder,
            'resource_type' => $resourceType,
        ]);

        return $result['secure_url'];
    }
}