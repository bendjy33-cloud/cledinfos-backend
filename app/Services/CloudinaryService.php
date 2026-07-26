<?php

namespace App\Services;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('filesystems.disks.cloudinary.cloud'),
                'api_key' => config('filesystems.disks.cloudinary.key'),
                'api_secret' => config('filesystems.disks.cloudinary.secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    public function upload(string $path, string $folder): string
    {
        $result = (new UploadApi())->upload($path, [
            'folder' => $folder,
        ]);

        return $result['secure_url'];
    }
}