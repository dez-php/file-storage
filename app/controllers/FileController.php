<?php

namespace FileStorage\Controllers;

use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Models\Files;

class FileController extends ControllerJson {

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function indexAction($hash = null)
    {
        /** @var Files $file */
        $file = Files::query()->where('hash', $hash)->first();

        if(! $file->exists()) {
            $this->error([
                'message' => "File do not exist or was removed {$hash}"
            ], 404);
        } else {
            if($file->isProtected()) {
                // @TODO ...
            }
        }

    }

}