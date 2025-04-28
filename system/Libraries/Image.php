<?php
namespace System\Libraries;

class Image
{
    private $source;
    private $image;
    private $width;
    private $height;
    private $type;

    public function __construct($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('Image file not found');
        }

        $this->source = $filePath;
        list($this->width, $this->height, $this->type) = getimagesize($filePath);

        switch ($this->type) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefrompng($filePath);
                break;
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromgif($filePath);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function resize($width, $height)
    {
        $newImage = imagecreatetruecolor($width, $height);

        // Preserve transparency for PNG
        if ($this->type == IMAGETYPE_PNG) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $width, $height, $transparent);
        }

        imagecopyresampled(
            $newImage, $this->image,
            0, 0, 0, 0,
            $width, $height,
            $this->width, $this->height
        );

        $this->image = $newImage;
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function save($path, $quality = 90)
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        switch ($this->type) {
            case IMAGETYPE_JPEG:
                imagejpeg($this->image, $path, $quality);
                break;
            case IMAGETYPE_PNG:
                imagepng($this->image, $path, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($this->image, $path);
                break;
        }

        return $this;
    }

    public function __destruct()
    {
        if ($this->image) {
            imagedestroy($this->image);
        }
    }
} 