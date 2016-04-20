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

    public function dlAction($hash)
    {
        $this->response([
            'message' => "hash: $hash"
        ]);
    }

    public function getDirectLinkAction($hash)
    {
        $this->response([
            'auth' => $this->auth,
            'message' => "direct link for: $hash",
            'link' => "$hash/$hash/$hash"
        ]);
    }

}