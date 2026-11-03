<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Optimasi dan simpan gambar (Auto-Resize, EXIF Orientation Fix, & WebP/JPEG Compression)
     *
     * @param UploadedFile $file Berkas upload dari request
     * @param string $folder Direktori penyimpanan di dalam disk (contoh: 'students')
     * @param int $maxDimension Maksimal lebar atau tinggi dalam pixel (default: 800px)
     * @param int $quality Kualitas kompresi 1-100 (default: 82)
     * @param string $disk Storage disk Laravel (default: 'public')
     * @return string Path relatif file yang tersimpan
     */
    public static function optimizeAndStore(
        UploadedFile $file,
        string $folder = 'students',
        int $maxDimension = 800,
        int $quality = 82,
        string $disk = 'public'
    ): string {
        // Jika extension GD tidak tersedia, gunakan fungsi bawaan Laravel
        if (!extension_loaded('gd') || !function_exists('imagecreatefromstring')) {
            return $file->store($folder, $disk);
        }

        try {
            $realPath = $file->getRealPath();
            $imageContent = @file_get_contents($realPath);
            if ($imageContent === false) {
                return $file->store($folder, $disk);
            }

            // Buat canvas gambar dari data biner
            $sourceImage = @imagecreatefromstring($imageContent);
            if (!$sourceImage) {
                return $file->store($folder, $disk);
            }

            // Perbaiki orientasi EXIF (khusus foto potret/selfie kamera smartphone)
            if (function_exists('exif_read_data')) {
                try {
                    $exif = @exif_read_data($realPath);
                    if (!empty($exif['Orientation'])) {
                        switch ($exif['Orientation']) {
                            case 3:
                                $rotated = imagerotate($sourceImage, 180, 0);
                                if ($rotated) {
                                    imagedestroy($sourceImage);
                                    $sourceImage = $rotated;
                                }
                                break;
                            case 6:
                                $rotated = imagerotate($sourceImage, -90, 0);
                                if ($rotated) {
                                    imagedestroy($sourceImage);
                                    $sourceImage = $rotated;
                                }
                                break;
                            case 8:
                                $rotated = imagerotate($sourceImage, 90, 0);
                                if ($rotated) {
                                    imagedestroy($sourceImage);
                                    $sourceImage = $rotated;
                                }
                                break;
                        }
                    }
                } catch (\Throwable $e) {
                    // Abaikan parsing EXIF jika gambar tidak memiliki metadata
                }
            }

            $origWidth = imagesx($sourceImage);
            $origHeight = imagesy($sourceImage);

            // Hitung ukuran proporsional baru
            $newWidth = $origWidth;
            $newHeight = $origHeight;

            if ($origWidth > $maxDimension || $origHeight > $maxDimension) {
                if ($origWidth >= $origHeight) {
                    $newWidth = $maxDimension;
                    $newHeight = (int) round(($origHeight / $origWidth) * $maxDimension);
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = (int) round(($origWidth / $origHeight) * $maxDimension);
                }
            }

            // Buat canvas gambar dengan ukuran baru
            $targetImage = imagecreatetruecolor($newWidth, $newHeight);

            // Pengaturan transparansi jika format sumber memiliki alpha channel
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
            $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
            imagefilledrectangle($targetImage, 0, 0, $newWidth, $newHeight, $transparent);

            // Resample dengan interpolasi halus
            imagecopyresampled(
                $targetImage,
                $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $origWidth, $origHeight
            );

            imagedestroy($sourceImage);

            // Output preferensi: WebP (modern, super ringan) atau JPEG berkualitas tinggi
            $supportsWebp = function_exists('imagewebp');
            $filename = Str::random(40);

            ob_start();
            if ($supportsWebp) {
                imagewebp($targetImage, null, $quality);
                $ext = 'webp';
            } else {
                imagejpeg($targetImage, null, $quality);
                $ext = 'jpg';
            }
            $optimizedData = ob_get_clean();

            imagedestroy($targetImage);

            if (empty($optimizedData)) {
                return $file->store($folder, $disk);
            }

            $storagePath = trim($folder, '/') . '/' . $filename . '.' . $ext;
            Storage::disk($disk)->put($storagePath, $optimizedData);

            return $storagePath;
        } catch (\Throwable $e) {
            // Fallback aman ke penyimpanan default
            return $file->store($folder, $disk);
        }
    }
}
