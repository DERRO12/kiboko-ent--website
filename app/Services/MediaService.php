<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public const MAX_WIDTH = 1920;

    public const THUMB_WIDTH = 640;

    /** Columns that reference media, used to block deleting images still in use. */
    public const USAGE = [
        ['page_sections', 'media_id'], ['page_sections', 'background_media_id'], ['hero_sections', 'media_id'],
        ['services', 'media_id'], ['brands', 'media_id'], ['coverage_locations', 'media_id'], ['insights', 'media_id'],
        ['seo_metadata', 'media_id'],
    ];

    public function upload(UploadedFile $file, string $folder = 'general', ?string $alt = null, ?Media $replace = null): Media
    {
        $ext = $file->extension() === 'jpeg' ? 'jpg' : $file->extension();
        $dir = 'media/'.(Str::slug($folder) ?: 'general').'/'.date('Y/m');
        $name = Str::random(32);
        $src = $file->getRealPath();

        $main = $this->encode($src, $ext, self::MAX_WIDTH) ?? file_get_contents($src);
        Storage::disk('public')->put("$dir/$name.$ext", $main);
        $size = @getimagesizefromstring($main) ?: [null, null];

        $thumbPath = null;
        if (($size[0] ?? 0) > self::THUMB_WIDTH && ($thumb = $this->encode($src, $ext, self::THUMB_WIDTH))) {
            $thumbPath = "$dir/{$name}_thumb.$ext";
            Storage::disk('public')->put($thumbPath, $thumb);
        }

        $data = [
            'folder' => Str::slug($folder) ?: 'general',
            'path' => "$dir/$name.$ext",
            'thumb_path' => $thumbPath,
            'mime' => $file->getMimeType(),
            'size' => strlen($main),
            'width' => $size[0],
            'height' => $size[1],
            'uploaded_by' => auth()->id(),
        ];

        if ($replace) {
            $this->deleteFiles($replace);
            $replace->update($data + ['alt' => $alt ?? $replace->alt]);

            return $replace;
        }

        return Media::create($data + [
            'title' => Str::limit(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), 120, ''),
            'alt' => $alt,
        ]);
    }

    public function usageCount(Media $media): int
    {
        $n = 0;
        foreach (self::USAGE as [$table, $col]) {
            $n += \DB::table($table)->where($col, $media->id)->count();
        }

        return $n + \DB::table('website_settings')->where('type', 'image')->where('value', (string) $media->id)->count();
    }

    public function delete(Media $media): void
    {
        $this->deleteFiles($media);
        $media->delete();
    }

    protected function deleteFiles(Media $media): void
    {
        Storage::disk('public')->delete(array_filter([$media->path, $media->thumb_path]));
    }

    /** Resize down to $max width and re-encode (also strips metadata). */
    protected function encode(string $src, string $ext, int $max): ?string
    {
        if ($ext === 'gif' || ! function_exists('imagecreatefromstring')) {
            return null;
        }
        $img = @imagecreatefromstring(file_get_contents($src));
        if (! $img) {
            return null;
        }
        $w = imagesx($img);
        $h = imagesy($img);
        if ($w > $max) {
            $nh = (int) round($h * $max / $w);
            $dst = imagecreatetruecolor($max, $nh);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagecopyresampled($dst, $img, 0, 0, 0, 0, $max, $nh, $w, $h);
            imagedestroy($img);
            $img = $dst;
        }
        imagesavealpha($img, true);
        ob_start();
        match ($ext) {
            'png' => imagepng($img, null, 8),
            'webp' => imagewebp($img, null, 82),
            default => imagejpeg($img, null, 82),
        };
        imagedestroy($img);

        return ob_get_clean() ?: null;
    }
}
