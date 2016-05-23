<?php

namespace FileStorage\Services;

/**
 * Class Image
 * @package FileStorage\Services
 */
class Image
{

    const WATERMARK_TILE = 1;

    const WATERMARK_RIGHT_BOTTOM = 1;

    const WATERMARK_LEFT_BOTTOM = 1;

    const WATERMARK_RIGHT_TOP = 1;

    const WATERMARK_LEFT_TOP = 1;

    const WATERMARK_MIDDLE = 1;

    /**
     * @var resource
     */
    private $image;

    /**
     * @var resource
     */
    private $original;

    /**
     * @var resource
     */
    private $watermark;

    /**
     * Image constructor.
     * @param null $filename
     */
    public function __construct($filename = null)
    {
        if (null !== $filename) {
            $this->load($filename);
        }
    }

    /**
     * Image destructor.
     */
    public function __destruct()
    {
        $this->destroy();
    }

    /**
     * @param $filename
     * @return $this
     * @throws \Exception
     */
    public function load($filename)
    {
        if (!file_exists($filename)) {
            throw new \Exception("Image file '{$filename}' could not be found");
        }

        $imageInfo = getimagesize($filename);

        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromgif($filename);
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefrompng($filename);
                break;
            default:
                throw new \Exception('Unsupported image type');
                break;
        }

        $this->original = $this->image;

        return $this;
    }

    /**
     * @return $this
     */
    public function resetToOriginal()
    {
        if (is_resource($this->original)) {
            $this->image = $this->original;
        }

        return $this;
    }

    /**
     * @param $filename
     * @return $this
     * @throws \Exception
     */
    public function loadWatermark($filename)
    {
        if (!file_exists($filename)) {
            throw new \Exception("Watermark file '{$filename}' could not be found");
        }

        $watermarkInfo = getimagesize($filename);

        if ($watermarkInfo[2] == IMAGETYPE_PNG) {
            $this->watermark = imagecreatefrompng($filename);
        } else {
            throw new \Exception('Watermark must have only png type');
        }

        return $this;
    }

    /**
     * @param int $imagetype
     * @return $this
     */
    public function output($imagetype = IMAGETYPE_JPEG)
    {
        switch ($imagetype) {
            case IMAGETYPE_JPEG:
                header('Content-type: image/jpeg');
                imagejpeg($this->image);
                break;
            case IMAGETYPE_GIF:
                header('Content-type: image/gif');
                imagegif($this->image);
                break;
            case IMAGETYPE_PNG:
                header('Content-type: image/png');
                imagepng($this->image);
                break;
            default:
                header('Content-type: image/jpeg');
                imagejpeg($this->image);
                break;
        }

        return $this;
    }

    /**
     * @param $filename
     * @param int $permission
     * @param int $own
     * @return Image
     * @throws \Exception
     */
    public function saveGif($filename, $permission = 0777, $own = -1)
    {
        return $this->save($filename, IMAGETYPE_GIF, -1, $permission, $own);
    }

    /**
     * @param $filename
     * @param int $imagetype
     * @param int $quality
     * @param int $permission
     * @param int $owner
     * @return $this
     * @throws \Exception
     */
    public function save($filename, $imagetype = IMAGETYPE_JPEG, $quality = 50, $permission = 0777, $owner = -1)
    {
        switch ($imagetype) {
            case IMAGETYPE_JPEG:
                imagejpeg($this->image, $filename, $quality);
                break;
            case IMAGETYPE_GIF:
                imagegif($this->image, $filename);
                break;
            case IMAGETYPE_PNG:
                imagepng($this->image, $filename);
                break;
            default:
                throw new \Exception('Unsupported image type');
                break;
        }

        $directory = dirname($filename);

        if (! file_exists($directory) && ! mkdir($directory, 0777, true)) {
            throw new \Exception("Can not create destination directory '{$directory}'");
        }

        if (! chmod($filename, $permission)) {
            throw new \Exception("Can not change permissions for target file");
        }

        if (is_string($owner) && ! chown($filename, $owner)) {
            throw new \Exception("Can not change owner '{$owner}' for target file");
        }

        return $this;
    }

    /**
     * @param $filename
     * @param int $permission
     * @param int $own
     * @return Image
     * @throws \Exception
     */
    public function savePng($filename, $permission = 0777, $own = -1)
    {
        return $this->save($filename, IMAGETYPE_PNG, -1, $permission, $own);
    }

    /**
     * @param $filename
     * @param int $quality
     * @param int $permission
     * @param int $own
     * @return Image
     * @throws \Exception
     */
    public function saveJpeg($filename, $quality = 50, $permission = 0777, $own = -1)
    {
        return $this->save($filename, IMAGETYPE_JPEG, $quality, $permission, $own);
    }

    /**
     * @param $width
     * @return Image
     * @throws \Exception
     */
    public function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;

        return $this->resize($width, $height);
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return imagesx($this->image);
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return imagesy($this->image);
    }

    /**
     * @param int $width
     * @param int $height
     * @return $this
     * @throws \Exception
     */
    public function resize($width = -1, $height = -1)
    {
        if (0 >= (int)$width || 0 >= (int)$height) {
            throw new \Exception('Width and height can not have negative value');
        }

        $blankImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($blankImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(),
            $this->getHeight());
        $this->image = $blankImage;

        return $this;
    }

    /**
     * @param $height
     * @return Image
     * @throws \Exception
     */
    public function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;

        return $this->resize($width, $height);
    }

    /**
     * @param int $scale
     * @return Image
     * @throws \Exception
     */
    public function scale($scale = 50)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getHeight() * $scale / 100;

        return $this->resize($width, $height);
    }

    /**
     * @param int $height
     * @return Image
     * @throws \Exception
     */
    public function cropHeight($height = -1)
    {
        return $this->crop($this->getWidth(), $height);
    }

    /**
     * @param int $width
     * @param int $height
     * @return $this
     * @throws \Exception
     */
    public function crop($width = -1, $height = -1)
    {

        if (0 >= (int)$width || 0 >= (int)$height) {
            throw new \Exception('Width and height can not have negative value');
        }

        $blankImage = imagecreatetruecolor($width, $height);
        imagefill($blankImage, 0, 0, 0xFFFFFF);

        imagecopy($blankImage, $this->image, 0, 0, 0, 0, $width, $height);

        $this->image = $blankImage;

        return $this;

    }

    /**
     * @param int $width
     * @return Image
     * @throws \Exception
     */
    public function cropWidth($width = -1)
    {
        return $this->crop($width, $this->getHeight());
    }

    /**
     * @param int $position
     * @return $this
     */
    public function addWatermark($position = self::WATERMARK_RIGHT_BOTTOM)
    {
        $margin = 10;
        $imageWidth = $this->getWidth();
        $imageHeight = $this->getHeight();
        $watermarkWidth = imagesx($this->watermark);
        $watermarkHeight = imagesy($this->watermark);

        switch ($position) {

            case static::WATERMARK_LEFT_TOP:

                $x = $margin;
                $y = $margin;

                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);

                break;

            case static::WATERMARK_RIGHT_TOP:

                $x = $imageWidth - $margin - $watermarkWidth;
                $y = $margin;

                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);

                break;

            case static::WATERMARK_LEFT_BOTTOM:

                $x = $margin;
                $y = $imageHeight - $margin - $watermarkHeight;

                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);

                break;

            case static::WATERMARK_RIGHT_BOTTOM:

                $x = $imageWidth - $margin - $watermarkWidth;
                $y = $imageHeight - $margin - $watermarkHeight;

                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);

                break;

            case static::WATERMARK_MIDDLE:

                $x = $imageWidth - (($imageWidth / 2) + ($watermarkWidth / 2));
                $y = $imageHeight - (($imageHeight / 2) + ($watermarkHeight / 2));

                imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);

                break;

            case static::WATERMARK_TILE:

                $inline = ceil($imageWidth / $watermarkWidth);
                $lines = ceil($imageHeight / $watermarkHeight);

                for ($i = 0; $i < $lines; $i++) {
                    for ($j = 0; $j < $inline; $j++) {
                        $x = $j * $watermarkWidth;
                        $y = $i * $watermarkHeight;

                        imagecopy($this->image, $this->watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
                    }
                }

                break;

        }

        return $this;
    }

    /**
     * @return $this
     */
    public function destroy()
    {
        imagedestroy($this->image);
        imagedestroy($this->original);
        imagedestroy($this->watermark);

        return $this;
    }

}