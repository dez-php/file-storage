<?php

namespace FileStorage\Services;

use Dez\DependencyInjection\Container;
use Dez\Http\Request\File;
use FileStorage\Core\InjectableAware;

class Uploader extends InjectableAware
{

    /**
     * @var string
     */
    protected $destination = null;

    /**
     * @var File
     */
    protected $file = null;

    /**
     * @var int
     */
    protected $allowedMaxSize = 0;

    /**
     * @var array
     */
    protected $allowedMimeTypes = [];

    /**
     * @var array
     */
    protected $allowedExtensions = [];

    /**
     * Uploader constructor.
     * @param null $destination
     */
    public function __construct($destination = null)
    {
        $this->setDi(Container::instance());

        $this->setDestination($destination);
    }

    /**
     * @return $this
     */
    public function configure()
    {
        $this->setAllowedMaxSize((integer) ini_get('post_max_size') - 1);
        return $this;
    }

    /**
     * @return $this
     * @throws UploaderException
     */
    public function validate()
    {
        $allowedSize = $this->getAllowedMaxSize();
        $allowedMimes = $this->getAllowedMimeTypes();
        $allowedExtensions = $this->getAllowedExtensions();

        $file = $this->getFile();

        $size = $file->getSize();
        if ($allowedSize > 0 && $size > $allowedSize) {
            throw new UploaderException("Validation failed. Not allowed file size uploaded {$file->getSize(File::SIZE_MEGABYTES)}M");
        }

        $mime = $file->getMimeType();
        if (count($allowedMimes) > 0 && ! in_array($mime, $allowedMimes)) {
            throw new UploaderException("Validation failed. Not allowed mime type: '{$mime}'");
        }

        $extension = $file->getExtension();
        if (count($allowedExtensions) > 0 && in_array($extension, $allowedExtensions)) {
            throw new UploaderException("Validation failed. Not allowed extension '.{$extension}'");
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getAllowedMaxSize()
    {
        return $this->allowedMaxSize;
    }

    /**
     * @param int $allowedMaxSize
     * @return static
     */
    public function setAllowedMaxSize($allowedMaxSize)
    {
        $this->allowedMaxSize = $allowedMaxSize;

        return $this;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     * @return $this
     * @throws UploaderException
     */
    public function setFile(File $file = null)
    {
        if ($file === null) {
            throw new UploaderException("Pass file object for uploading. NULL passed");
        }

        if (! $file->isUploaded()) {
//            throw new UploaderException("Pass an empty object file");
        }

        if ($file->isFailed()) {
            throw new UploaderException("Transfer the file object with an error '{$file->getErrorDescription()}'");
        }

        $this->file = $file;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllowedMimeTypes()
    {
        return $this->allowedMimeTypes;
    }

    /**
     * @param array $allowedMimeTypes
     * @return static
     */
    public function setAllowedMimeTypes($allowedMimeTypes)
    {
        $this->allowedMimeTypes = $allowedMimeTypes;

        return $this;
    }

    /**
     * @throws UploaderException
     */
    public function upload()
    {
        $this->validate();

        if (!$this->getFile()->moveTo($this->generatePath())) {
            throw new UploaderException("Can not move uploaded file to needed destination");
        }

        return $this;
    }

    /**
     * @return string
     * @throws UploaderException
     */
    public function generatePath()
    {
        $path = $this->getDestination();
        $hash = implode('/', str_split(md5($this->getFile()->getName()), 8));
        $path = "$path/{$this->getFile()->getExtension()}/$hash.{$this->getFile()->getExtension()}";

        $directory = dirname($path);
        if(! file_exists($directory) && ! is_dir($directory)) {
            if(! mkdir($directory, 0755, true)) {
                throw new UploaderException("Can not create destination directory {$directory}");
            }
        }

        return $path;
    }

    /**
     * @return null
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     * @return $this
     * @throws UploaderException
     */
    public function setDestination($destination)
    {
        if (!file_exists($destination) && !is_dir($destination)) {
            throw new UploaderException("Destination directory do not exist");
        }

        $this->destination = $destination;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * @param array $allowedExtensions
     * @return static
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;

        return $this;
    }

}