<?php

namespace FileStorage\Services\Uploader;

abstract class FileResource {

    protected $name = '';
    
    protected $size = 0;
    
    protected $mime = '';
    
    protected $extension;

    protected $source = null;

    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @param $path
     * @return $this
     */
    abstract public function moveTo($path);

    /**
     * @return $this
     */
    abstract public function process();

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return static
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param string $mime
     * @return static
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     * @return static
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

}