<?php

namespace App\Services;

class ImageCompressService
{
    public function saveAndCompressImage($imageData, $filePath, $maxSizeKB)
    {
        // Create an image resource from the decoded data
        $img = imagecreatefromstring($imageData);
        if ($img === false) {
            throw new \Exception('Failed to create image from decoded data.');
        }

        // Get original dimensions
        $originalWidth = imagesx($img);
        $originalHeight = imagesy($img);
        $width = $originalWidth;
        $height = $originalHeight;

        // Initial quality setting
        $quality = 85;

        // First try: save with original dimensions and initial quality
        imagejpeg($img, $filePath, $quality);

        // If file is already smaller than max size, we're done
        if (filesize($filePath) <= $maxSizeKB * 1024) {
            imagedestroy($img);
            return;
        }

        // Calculate target dimensions while maintaining aspect ratio
        $scaleFactor = sqrt(($maxSizeKB * 1024) / filesize($filePath));
        $width = max(round($width * $scaleFactor), 300); // Don't go below 800px width
        $height = max(round($height * $scaleFactor), 300); // Don't go below 600px height

        // Resize image if dimensions changed
        if ($width !== $originalWidth || $height !== $originalHeight) {
            $resizedImg = imagecreatetruecolor($width, $height);
            imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
            imagedestroy($img);
            $img = $resizedImg;
        }

        // Binary search for optimal quality
        $minQuality = 60; // Don't go below 60% quality
        $maxQuality = 95;

        while ($minQuality <= $maxQuality) {
            $quality = ceil(($minQuality + $maxQuality) / 2);
            imagejpeg($img, $filePath, $quality);

            $fileSize = filesize($filePath);
            if ($fileSize <= $maxSizeKB * 1024 && $fileSize >= ($maxSizeKB * 1024 * 0.8)) {
                // File size is within 80-100% of target, acceptable
                break;
            }

            if ($fileSize > $maxSizeKB * 1024) {
                $maxQuality = $quality - 1;
            } else {
                $minQuality = $quality + 1;
            }
        }

        imagedestroy($img);
       
    }
}