<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileCompressionService
{
    /**
     * Compress and save an image file using PHP GD.
     *
     * @param UploadedFile $file
     * @param string $destinationDir Absolute path to the destination directory
     * @param string $prefix Filename prefix
     * @param int $maxWidth Maximum width in pixels
     * @param int $maxHeight Maximum height in pixels
     * @param int $quality JPEG/WebP quality (0-100)
     * @return string The generated filename
     */
    public static function compressAndUploadImage(
        UploadedFile $file,
        string $destinationDir,
        string $prefix = 'img',
        int $maxWidth = 1200,
        int $maxHeight = 1200,
        int $quality = 82
    ): string {
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (empty($extension)) {
            $extension = 'jpg';
        }
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
        $targetPath = rtrim($destinationDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        // If GD extension is not available, perform regular move
        if (!extension_loaded('gd')) {
            $file->move($destinationDir, $filename);
            return $filename;
        }

        try {
            $filePath = $file->getPathname();
            $imageInfo = @getimagesize($filePath);

            if (!$imageInfo) {
                $file->move($destinationDir, $filename);
                return $filename;
            }

            $mime = $imageInfo['mime'];
            $origW = $imageInfo[0];
            $origH = $imageInfo[1];

            // Load source image
            $source = null;
            switch ($mime) {
                case 'image/jpeg':
                case 'image/pjpeg':
                    $source = @imagecreatefromjpeg($filePath);
                    break;
                case 'image/png':
                case 'image/x-png':
                    $source = @imagecreatefrompng($filePath);
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $source = @imagecreatefromwebp($filePath);
                    }
                    break;
                case 'image/gif':
                    $source = @imagecreatefromgif($filePath);
                    break;
            }

            if (!$source) {
                $source = @imagecreatefromstring(file_get_contents($filePath));
            }

            if (!$source) {
                $file->move($destinationDir, $filename);
                return $filename;
            }

            // Correct EXIF orientation for JPEG (e.g. mobile phone camera photos)
            if (($mime === 'image/jpeg' || $mime === 'image/pjpeg') && function_exists('exif_read_data')) {
                $exif = @exif_read_data($filePath);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $rotated = @imagerotate($source, 180, 0);
                            if ($rotated) {
                                imagedestroy($source);
                                $source = $rotated;
                            }
                            break;
                        case 6:
                            $rotated = @imagerotate($source, -90, 0);
                            if ($rotated) {
                                imagedestroy($source);
                                $source = $rotated;
                            }
                            break;
                        case 8:
                            $rotated = @imagerotate($source, 90, 0);
                            if ($rotated) {
                                imagedestroy($source);
                                $source = $rotated;
                            }
                            break;
                    }
                    $origW = imagesx($source);
                    $origH = imagesy($source);
                }
            }

            // Calculate resized dimensions while preserving aspect ratio
            $targetW = $origW;
            $targetH = $origH;

            if ($origW > $maxWidth || $origH > $maxHeight) {
                $ratio = min($maxWidth / $origW, $maxHeight / $origH);
                $targetW = max(1, (int) round($origW * $ratio));
                $targetH = max(1, (int) round($origH * $ratio));
            }

            $targetImg = imagecreatetruecolor($targetW, $targetH);

            // Handle transparency for PNG, WebP, GIF
            if (in_array($mime, ['image/png', 'image/x-png', 'image/webp', 'image/gif'])) {
                imagealphablending($targetImg, false);
                imagesavealpha($targetImg, true);
                $transparent = imagecolorallocatealpha($targetImg, 255, 255, 255, 127);
                imagefilledrectangle($targetImg, 0, 0, $targetW, $targetH, $transparent);
            }

            // High quality resample
            imagecopyresampled($targetImg, $source, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);

            // Save compressed output
            $saved = false;
            switch ($mime) {
                case 'image/jpeg':
                case 'image/pjpeg':
                    $saved = @imagejpeg($targetImg, $targetPath, $quality);
                    break;
                case 'image/png':
                case 'image/x-png':
                    // PNG compression: 0 (none) to 9 (max compression)
                    $saved = @imagepng($targetImg, $targetPath, 7);
                    break;
                case 'image/webp':
                    if (function_exists('imagewebp')) {
                        $saved = @imagewebp($targetImg, $targetPath, $quality);
                    }
                    break;
                case 'image/gif':
                    $saved = @imagegif($targetImg, $targetPath);
                    break;
            }

            if (!$saved) {
                // Default fallback to imagejpeg if format specific save failed
                $saved = @imagejpeg($targetImg, $targetPath, $quality);
            }

            @imagedestroy($source);
            @imagedestroy($targetImg);

            if (!$saved || !file_exists($targetPath)) {
                $file->move($destinationDir, $filename);
            }

            return $filename;
        } catch (\Throwable $e) {
            // Fail safe fallback to regular upload if anything unexpected occurs
            $file->move($destinationDir, $filename);
            return $filename;
        }
    }

    /**
     * Compress and upload document or image file.
     * If the document is an image (e.g. scan of KK, SKTM, slip gaji), it is compressed as a clear high-res image.
     * If it is a PDF or other document, it is moved safely.
     *
     * @param UploadedFile $file
     * @param string $destinationDir
     * @param string $prefix
     * @return string The generated filename
     */
    public static function compressAndUploadDocument(
        UploadedFile $file,
        string $destinationDir,
        string $prefix = 'dok'
    ): string {
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        $mime = $file->getMimeType();
        $isImage = str_starts_with($mime, 'image/') || in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp']);

        if ($isImage) {
            // Compress image document with high resolution for readability (1600x1600, quality 82)
            return self::compressAndUploadImage(
                $file,
                $destinationDir,
                $prefix,
                1600,
                1600,
                82
            );
        }

        // For PDF or other documents, save with unique name
        $extension = strtolower($file->getClientOriginalExtension());
        if (empty($extension)) {
            $extension = 'pdf';
        }
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
        $file->move($destinationDir, $filename);

        return $filename;
    }
}
