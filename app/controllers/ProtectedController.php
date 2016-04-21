<?php

namespace FileStorage\Controllers;

use FileStorage\Core\Mvc\ControllerJson;

class ProtectedController extends ControllerJson {

    public function indexAction()
    {
        $this->response([], 200);
    }

}