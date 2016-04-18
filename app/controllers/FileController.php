<?php

namespace FileStorage\Controllers;

use FileStorage\Core\Mvc\ControllerJson;

class FileController extends ControllerJson {

    public function indexAction()
    {
        $this->response([
            'message' => 'Use hash-code for fetching file info'
        ]);
    }

    public function itemAction($hash)
    {
        $this->response([
            'message' => "hash: $hash"
        ]);
    }

}