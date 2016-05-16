<?php

namespace FileStorage\Services\Uploader\Validation;

use Dez\Config\Config;
use Dez\Validation\Message;
use Dez\Validation\Rule;
use Dez\Validation\Validation;
use FileStorage\Services\Uploader\Uploader;

class SizeRange extends Rule {

    /**
     * @var Config
     */
    protected $config;

    /**
     * SizeRange constructor.
     * @param Config $config
     * @param array $options
     */
    public function __construct(Config $config, array $options)
    {
        parent::__construct($options);

        $this->config = $config;
    }

    /**
     * @param null $field
     * @param Validation $validation
     * @return bool
     */
    public function validate($field = null, Validation $validation)
    {
        $value      = $this->getValue($field);

        $min      = $this->config->path('sizes.min');
        $max      = $this->config->path('sizes.max');

        if($value > $max || $value < $min) {
            $message    = $this->getOption('message', $this->getDefaultMessage());

            $validation->appendMessage(new Message($field, $message, [
                'min' => Uploader::humanizeSize($min),
                'max' => Uploader::humanizeSize($max)
            ]));

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getDefaultMessage()
    {
        return 'File size length of must be between :min and :max.';
    }


}