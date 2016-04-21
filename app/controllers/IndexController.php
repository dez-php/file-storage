<?php

namespace FileStorage\Controllers;

use Dez\Mvc\Controller;
use FileStorage\Core\Mvc\ControllerJson;

class IndexController extends ControllerJson {

    public function indexAction()
    {
        $this->response([
            'message' => 'Welcome to File Server',
        ]);
    }

}