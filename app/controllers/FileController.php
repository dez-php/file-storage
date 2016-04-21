<?php

namespace FileStorage\Controllers;

use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Models\Files;

class FileController extends ControllerJson {

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function indexAction($hash)
    {
        /** @var Files $file */
        $file = Files::query()->where('hash', $hash)->first();

        if(! $file->exists()) {
            $this->error([
                'message' => "File do not exist or was removed {$hash}"
            ], 404);
        } else {
            if($file->isProtected()) {

            }
        }

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