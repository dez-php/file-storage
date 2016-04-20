<?php

namespace FileStorage\Controllers;

use Dez\Mvc\Controller;
use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Services\Uploader;

class UploaderController extends ControllerJson {
    
    public function indexAction()
    {
        $root = $this->config->path('application.uploader.filesDirectory');

        $uploadedFiles = [];
        $uploader = new Uploader($root);

        if($this->request->hasFiles()) {
            foreach ($this->request->getUploadedFiles('user') as $file) {
                $uploader->setFile($file)->upload();
            }
        }

        $this->response([
            'files' => $uploadedFiles,
        ]);

    }
    
}