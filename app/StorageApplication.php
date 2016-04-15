<?php

namespace FileStorage;

use Dez\Mvc\Application\Configurable;

class StorageApplication extends Configurable {

    public function initialize()
    {
        return $this;
    }

    public function injection()
    {
        return $this;
    }

}