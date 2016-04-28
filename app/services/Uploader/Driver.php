<?php

namespace FileStorage\Services\Uploader;

abstract class Driver {

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

    /**
     * @param $source
     * @return $this
     */
    abstract public function upload($source);

    /**
     * @return $this
     */
    abstract public function initialize();

}