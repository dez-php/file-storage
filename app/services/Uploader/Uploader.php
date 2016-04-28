<?php

namespace FileStorage\Services\Uploader;

use Dez\DependencyInjection\Container;
use FileStorage\Core\InjectableAware;

/**
 * Class Uploader
 * @package FileStorage\Services\Uploader
 */
class Uploader extends InjectableAware
{

    /**
     * @var Driver
     */
    protected $driver = null;

    /**
     * @var string
     */
    protected $root = null;

    /**
     * @var string
     */
    protected $subDirectory = null;

    /**
     * Uploader constructor.
     * @param string $root
     */
    public function __construct($root = null)
    {
        $this->setDi(Container::instance());

        if(null !== $root) {
            $this->setRoot($root);
        }
    }

    /**
     * @return $this
     */
    public function configure()
    {
        return $this;
    }

    /**
     * @return Driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param Driver $driver
     * @return static
     */
    public function setDriver(Driver $driver)
    {
        $driver->setUploader($this);
        $this->driver = $driver;

        return $this;
    }

    /**
     * @param null $source
     * @return FileInfo
     * @throws UploaderException
     */
    public function upload($source = null)
    {
        if(null === ($driver = $this->getDriver())) {
            throw new UploaderException("Initialize driver for uploader");
        }
        
        return $driver->upload($source);
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param string $root
     * @return $this
     * @throws UploaderException
     */
    public function setRoot($root)
    {
        if(! file_exists($root) || ! is_dir($root) || ! is_writable($root)) {
            throw new UploaderException("Root directory do not exists or not writable");
        }

        $this->root = $root;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubDirectory()
    {
        return $this->subDirectory;
    }

    /**
     * @param string $subDirectory
     * @return static
     */
    public function setSubDirectory($subDirectory)
    {
        $this->subDirectory = $subDirectory;

        return $this;
    }

    /**
     * @return mixed
     */
    public function destinationPath()
    {
        $destination = rtrim(sprintf('%s/%s', rtrim($this->getRoot(), '/'), rtrim($this->getSubDirectory())), '/');
        $this->makeDirectory($destination, 0777, true);

        return realpath($destination);
    }

    /**
     * @param string $file
     * @return string
     */
    public function relativeFilePath($file)
    {
        return sprintf('%s/%s', trim($this->getSubDirectory(), '/'), $file);
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