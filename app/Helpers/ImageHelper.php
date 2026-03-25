<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the full URL for an image path.
     * Handles both full external URLs and local storage paths.
     *
     * @param string|null $path
     * @param string|null $default
     * @return string
     */
    public static function getUrl(?string $path, ?string $default = 'apple-touch-icon.png'): string
    {
        if (empty($path)) {
            return asset($default);
        }

        // If it starts with http or https, return it as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Otherwise, return it from the public storage
        return asset('storage/' . ltrim($path, '/'));
    }
}
