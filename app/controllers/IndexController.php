<?php

namespace FileStorage\Controllers;

use Dez\Mvc\Controller;
use FileStorage\Core\Mvc\ControllerJson;

class IndexController extends ControllerJson {

    public function indexAction()
    {
        $this->response([
            'message' => 'Welcome to File Server',
            'request_id' => implode('-', str_split(substr(md5(time()), 0, 16), 4))
        ]);
    }

}