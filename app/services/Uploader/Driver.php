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
     * @throws UploaderException
     */
    protected function validateSize($size = 0)
    {
        $config = $this->getUploader()->getValidationConfig();

        if ($config->has('sizes')) {
            $min = (integer) $config->path('sizes.min');
            $max = (integer) $config->path('sizes.max');

            if (!(($min > 0 && $size > $min) && ($max > 0 && $size < $max))) {
                $message = "File size must be great than %s and less than %s. %s passed";
                throw new UploaderException(sprintf($message, Uploader::humanizeSize($min), Uploader::humanizeSize($max), Uploader::humanizeSize($size)));
            }
        }
    }

    /**
     * @param null $extension
     * @throws UploaderException
     */
    protected function validateExtension($extension = null)
    {
        $config = $this->getUploader()->getValidationConfig();

        if ($config->has('extensions')) {
            $blackList = $config->path('extensions.black');
            $whiteList = $config->path('extensions.white');

            if($blackList->count() > 0 && in_array($extension, $blackList->toArray(), true)) {
                $message = sprintf("Uploaded file has extension %s in black-list %s", $extension, implode(', ', $blackList->toArray()));
                throw new UploaderException($message);
            }

            if($whiteList->count() > 0 && ! in_array($extension, $whiteList->toArray(), true)) {
                $message = sprintf("Uploaded file has not allowed extension", $extension, implode(', ', $whiteList->toArray()));
                throw new UploaderException($message);
            }
        }
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