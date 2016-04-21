<?php

namespace FileStorage\Controllers;

use FileStorage\Core\Mvc\ControllerJson;

class FileController extends ControllerJson {

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function indexAction()
    {
        $this->response([
            'message' => "Use method '/file/info/_hash' for fetching file info"
        ]);
    }

    public function downloadAction($hash)
    {
        $this->response([
            'message' => "hash: $hash"
        ]);
    }

    public function infoAction($hash)
    {
        $this->response([
            'message' => "info: $hash"
        ]);
    }

    public function fullInfoAction($hash)
    {
        $this->response([
            'message' => "full-info: $hash"
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