<?php

namespace FileStorage\Services\Uploader;

abstract class Driver {

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
    abstract public function initialize();

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

    /**
     * @return string
     */
    protected function createFileHash()
    {
        return sha1($this->getName() . $this->getSize() . $this->getMime() . $this->getCategory()) . ".{$this->getExtension()}";
    }

    /**
     * @param null $directoryPath
     * @param int $access
     * @param bool $recursively
     * @return $this
     * @throws UploaderException
     */
    protected function makeDirectory($directoryPath = null, $access = 0777, $recursively = true)
    {
        if(! is_dir($directoryPath) && ! mkdir($directoryPath, $access, $recursively)) {
            throw new UploaderException("Cannot create directory '{$directoryPath}'");
        }

        return $this;
    }

}