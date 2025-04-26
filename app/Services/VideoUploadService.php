<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class VideoUploadService
{
    protected $cloudinary;

    public function __construct()
    {
        // Configure Cloudinary using environment variables
        $config = Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);

        $this->cloudinary = new Cloudinary($config);
    }

    public function uploadVideo(UploadedFile $video)
    {
        try {
            // Store the video temporarily in public storage
            $tempPath = $video->store('temp/videos', 'public');
            $fullPath = Storage::disk('public')->path($tempPath);

            // Upload to Cloudinary
            $result = $this->cloudinary->uploadApi()->upload($fullPath, [
                'resource_type' => 'video',
                'folder' => 'recipe_videos'
            ]);

            // Clean up the temporary file
            Storage::disk('public')->delete($tempPath);

            // Return the Cloudinary URL
            return $result['secure_url'];
        } catch (\Exception $e) {
            // Clean up the temporary file if it exists
            if (isset($tempPath)) {
                Storage::disk('public')->delete($tempPath);
            }
            throw $e;
        }
    }

    public function uploadImage(UploadedFile $image)
    {
        try {
            // Store the image temporarily in public storage
            $tempPath = $image->store('temp/images', 'public');
            $fullPath = Storage::disk('public')->path($tempPath);

            // Upload to Cloudinary
            $result = $this->cloudinary->uploadApi()->upload($fullPath, [
                'resource_type' => 'image',
                'folder' => 'recipe_thumbnails'
            ]);

            // Clean up the temporary file
            Storage::disk('public')->delete($tempPath);

            // Return the Cloudinary URL
            return $result['secure_url'];
        } catch (\Exception $e) {
            // Clean up the temporary file if it exists
            if (isset($tempPath)) {
                Storage::disk('public')->delete($tempPath);
            }
            throw $e;
        }
    }
} 