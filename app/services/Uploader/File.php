<?php

namespace FileStorage\Services\Uploader;

class File {
    
    protected $name;

    protected $protected = 0;

    protected $size;

    protected $category;

    protected $uploaded = false;

    protected $resource = null;

    protected $extension = '';

    protected $mime = '';

    protected $relativePath = null;

    /**
     * File constructor.
     * @param FileResource $resource
     */
    public function __construct(FileResource $resource)
    {
        $this->resource = $resource->process();

        $this
            ->setName($this->getResource()->getName())
            ->setSize($this->getResource()->getSize())
            ->setExtension($this->getResource()->getExtension())
            ->setMime($this->getResource()->getMime())
        ;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
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
    public function getProtected()
    {
        return $this->protected;
    }

    /**
     * @param int $protected
     * @return static
     */
    public function setProtected($protected)
    {
        $this->protected = $protected;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return static
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return static
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
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
     * @return boolean
     */
    public function isUploaded()
    {
        return $this->uploaded;
    }

    /**
     * @return FileResource|null
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return null
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }

    /**
     * @param null $root
     * @return $this
     * @throws UploaderException
     */
    public function upload($root = null)
    {
        if(! $this->isUploaded()) {
            $this->relativePath = "{$this->getCategory()}/{$this->createFileHash()}";
            $path = "{$root}/{$this->getRelativePath()}";

            $this->makeDirectory(dirname($path))->getResource()->moveTo($path);
            $this->uploaded = true;
        }

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