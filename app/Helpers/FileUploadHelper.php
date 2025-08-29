<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadHelper
{
    private static $disk = 'public';
    private static $path = 'uploads';
    private static $webp_quality = 90; // High quality for WebP conversion

    /**
     * Upload and store a file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string|null $name
     * @param string|null $disk
     * @return string
     */
    public static function uploadFile(UploadedFile $file, $path = null, $name = null, $disk = null, $change = true)
    {
        // Validate the file type and size
        self::validateFile($file);

        // Set path
        $path = is_null($path) ? self::$path : $path;
        
        // Set Disk
        $disk = is_null($disk) ? self::$disk : $disk;

        // Check if this is an image that can be converted to WebP
        if (self::isConvertibleImage($file) and $change ) {
            // Convert to WebP and store with original dimensions and high quality
            return self::convertAndSaveAsWebP($file, $path, $name, $disk);
        } else {
            // For non-image files or already WebP images, use the original handling
            $filename = is_null($name) ? self::generateFilename($file) : self::setName($name, $file);
            $file->storeAs($path, $filename, $disk);
            return $path . '/' . $filename;
        }
    }

    /**
     * Validate the file's type and size.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @throws \InvalidArgumentException
     */
    protected static function validateFile(UploadedFile $file)
    {
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'image/webp',
            'image/svg+xml',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/x-icon',
            'image/vnd.microsoft.icon',
        ];
        $maxSize = 2048455; // Maximum file size (KB)

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid file type.');
        }

        if ($file->getSize() > $maxSize * 1024) {
            throw new \InvalidArgumentException('File size exceeds the maximum limit.');
        }
    }

    /**
     * Check if the file is an image that should be converted to WebP
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    protected static function isConvertibleImage(UploadedFile $file)
    {
        $convertibleTypes = [
            'image/jpeg',
            'image/png',
            'image/jpg',
            'image/gif',
        ];
        
        return in_array($file->getMimeType(), $convertibleTypes);
    }

    /**
     * Convert an image to WebP format and save it while preserving quality and dimensions
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string|null $name
     * @param string $disk
     * @return string
     */
    protected static function convertAndSaveAsWebP(UploadedFile $file, $path, $name = null, $disk)
    {
        // Generate the base filename (without extension)
        $baseFilename = is_null($name) 
            ? Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time()
            : Str::slug($name);
            
        // Add WebP extension
        $filename = $baseFilename . '.webp';
        
        // Create Image from uploaded file
        $img = Image::make($file->getRealPath());
        
        // Preserve original dimensions - no resizing
        $width = $img->width();
        $height = $img->height();
        
        // Create a temporary path for the WebP file
        $tempPath = storage_path('app/temp/' . $filename);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        // Save as WebP with high quality
        $img->encode('webp', self::$webp_quality)->save($tempPath);
        
        // Store the WebP file
        Storage::disk($disk)->put($path . '/' . $filename, file_get_contents($tempPath));
        
        // Clean up
        @unlink($tempPath);
        
        // Return the path
        return $path . '/' . $filename;
    }

    public static function getFileUrl($filename, $path = null, $disk = null)
    {
        // Set path
        $path = is_null($path) ? self::$path : $path;

        // Set disk
        $disk = is_null($disk) ? self::$disk : $disk;

        // Check if file exists
        if (!Storage::disk($disk)->exists($path . '/' . $filename)) {
            throw new \InvalidArgumentException('File does not exist.');
        }

        // Return the full URL of the file
        return Storage::disk($disk)->url($path . '/' . $filename);
    }

    /**
     * Generate a unique file name.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    protected static function generateFilename(UploadedFile $file)
    {
        // Get the file extension
        $extension = $file->getClientOriginalExtension();

        // Create a unique file name
        return Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
    }

    /**
     * Set a custom file name.
     *
     * @param string $name
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    protected static function setName($name, UploadedFile $file)
    {
        // Get the file extension
        $extension = $file->getClientOriginalExtension();

        // Create a custom file name
        return Str::slug($name) . '.' . $extension;
    }

    /**
     * Delete a file.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public static function deleteFile($path, $disk = 'public')
    {
        return Storage::disk($disk)->delete($path);
    }
}