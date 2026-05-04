<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Compress and save image using GD library
     */
    public static function compressAndSave($file, $path, $filename, $quality = 60)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $imagePath = $file->getRealPath();

        // Create image from file
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'png':
                $image = imagecreatefrompng($imagePath);
                // Handle transparency for PNG
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'webp':
                $image = imagecreatefromwebp($imagePath);
                break;
            default:
                // Fallback to direct store if unsupported
                return $file->storeAs($path, $filename, 'public');
        }

        // Create temporary file path
        $tempPath = tempnam(sys_get_temp_dir(), 'img');
        
        // Save compressed image to temp file
        if ($extension === 'png') {
            // PNG quality is 0-9
            imagepng($image, $tempPath, round(9 * ($quality / 100)));
        } else if ($extension === 'webp') {
            imagewebp($image, $tempPath, $quality);
        } else {
            imagejpeg($image, $tempPath, $quality);
        }

        // Store to disk
        $storedPath = Storage::disk('public')->putFileAs($path, new \Illuminate\Http\File($tempPath), $filename);

        // Cleanup
        imagedestroy($image);
        unlink($tempPath);

        return $storedPath;
    }
}
