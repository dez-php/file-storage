<?php

namespace FileStorage\Services\Uploader;

use Dez\DependencyInjection\Container;
use FileStorage\Core\InjectableAware;

class Uploader extends InjectableAware
{

    /**
     * @var null
     */
    protected $driver = null;

    protected $rootDirectory = null;
    
    public function __construct()
    {
        $this->setDi(Container::instance());
    }

    /**
     * @return $this
     */
    public function configure()
    {
        return $this;
    }

    /**
     * @return $this
     * @throws UploaderException
     */
    public function validate()
    {
        
    }

    /**
     * @param null $source
     * @return static
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }

    /**
     * @param string $rootDirectory
     * @return static
     */
    public function setRootDirectory($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;

        return $this;
    }

    /**
     * @param Driver $driver
     * @return $this
     */
    public function upload($driver = null)
    {
        $driver->upload($this->getRootDirectory());

        return $this;
    }


}