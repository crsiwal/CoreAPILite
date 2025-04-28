<?php
namespace App\Libraries;

use System\Libraries\Request;

class Photos
{
    const RETURN_SIZES = ['original', 'large', 'medium', 'small', 'thumbnail'];

    /**
     * Format a single photo path with base URL
     *
     * @param string $path The relative path of the photo
     * @return string The full URL of the photo
     */
    public function formatSinglePhotoPath($path)
    {
        if (! $path) {
            return null;
        }
        return Request::getBaseUrl() . '/uploads/' . $path;
    }

    /**
     * Format photo paths with base URL
     *
     * @param array $photo Photo data array
     * @param array $returnSizes Array of sizes to return
     * @return array Formatted photo data
     */
    public function formatPhotoPaths($photo, $returnSizes = self::RETURN_SIZES)
    {
        if (isset($photo['file_path'])) {
            $paths          = json_decode($photo['file_path'], true);
            $formattedPaths = [];
            foreach ($returnSizes as $size) {
                if (isset($paths[$size])) {
                    $formattedPaths[$size] = Request::getBaseUrl() . '/uploads/' . $paths[$size];
                }
            }
            $photo['file_path'] = $formattedPaths;
        }
        return $photo;
    }
}
