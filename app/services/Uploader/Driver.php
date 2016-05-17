<?php

namespace FileStorage\Services\Uploader;

use Dez\Validation\Validation;

abstract class Driver
{

    /**
     * @var null
     */
    protected $uploader = null;

    /**
     * Driver constructor.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * @return $this
     */
    abstract public function initialize();

    /**
     * @param $source
     * @return $this
     */
    abstract public function upload($source);

    /**
     * @param int $size
     * @return $this
     * @throws UploaderException
     */
    protected function validateSize($size = 0)
    {
        $config = $this->getUploader()->getValidationConfig();

        if ($config->has('sizes')) {
            $min = (integer) $config->path('sizes.min');
            $max = (integer) $config->path('sizes.max');

            $isValid = ($min > 0 && $size > $min) && ($max > 0 && $size < $max);

            if (! $isValid) {
                $message = "File size must be great than %s and less than %s. %s passed";

                throw new UploaderException(sprintf(
                    $message,
                    Uploader::humanizeSize($min),
                    Uploader::humanizeSize($max),
                    Uploader::humanizeSize($size)
                ));
            }
        }

        return $this;
    }

    /**
     * @param string $type
     * @param string $value
     * @return $this
     * @throws UploaderException
     */
    protected function validateFileType($type = null, $value = null)
    {
        $config = $this->getUploader()->getValidationConfig();

        if ($config->has($type)) {
            $blackList = $config->path("{$type}.black");
            $whiteList = $config->path("{$type}.white");

            $isValid = (!($blackList->count() > 0 && in_array($value, $blackList->toArray(), true))
                && !($whiteList->count() > 0 && !in_array($value, $whiteList->toArray(), true)));

            if (! $isValid) {
                throw new UploaderException(sprintf("Uploaded file type has not allowed '%s'", $value));
            }
        }

        return $this;
    }

    /**
     * @return Uploader
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @param Uploader $uploader
     * @return static
     */
    public function setUploader(Uploader $uploader)
    {
        $this->uploader = $uploader;

        return $this;
    }

}