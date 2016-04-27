<?php

namespace FileStorage\Services\Uploader;

use Dez\DependencyInjection\Container;
use FileStorage\Core\InjectableAware;

abstract class Uploader extends InjectableAware
{

    /**
     * @var null
     */
    protected $source = null;

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
     * @param File $file
     * @return $this
     */
    public function upload($file = null)
    {
        $file->upload($this->getRootDirectory());

        return $this;
    }


}