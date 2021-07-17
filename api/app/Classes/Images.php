<?php
namespace App\Classes;

use Intervention\Image\Facades\Image;

class Images
{
    /**
     * Check image url headers to verify if the image exists
     *
     * @param string|null $imageUrl
     * @return boolean
     */
    public static function exists(?string $imageUrl): bool
    {
        if (is_null($imageUrl)) {
            return false;
        }

        return !((@get_headers($imageUrl)[0] == 'HTTP/1.1 404 Not Found' or (bool) filter_var($imageUrl, FILTER_VALIDATE_URL) == false));
    }

    /**
     * Store an image locally, if it exists
     *
     * @param string|null $imageUrl
     * @return string
     */
    public static function store(?string $imageUrl): string
    {
        if (self::exists($imageUrl)) {
            $imagePath = public_path('images/' . basename($imageUrl));

            Image::make($imageUrl)->save($imagePath);

            return asset('images/' . basename($imageUrl));
        }

        return '';
    }
}
