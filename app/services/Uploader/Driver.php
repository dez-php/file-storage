<?php

namespace FileStorage\Services\Uploader;

use Dez\Validation\Validation;
use FileStorage\Services\Uploader\Validation\SizeRange;

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
    
    protected function validate(array $data = [])
    {
        $config = $this->getUploader()->getValidationConfig();
        $validation = new Validation($data);

        if($config->has('sizes')) {
            $validation->add('size', new SizeRange($config, []));
        }

        if(! $validation->validate()) {
            die(var_dump($validation->getMessages()));
        }
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