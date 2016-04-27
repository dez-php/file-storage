<?php

namespace FileStorage\Controllers;

use Dez\Http\Request\File as FileRequested;
use FileStorage\Services\Uploader\File;
use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Services\Uploader\Resource\FileHttp;
use FileStorage\Services\Uploader\Uploader;

class UploadController extends ControllerJson {

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function indexAction()
    {



        $uploader = new Uploader();
        $uploader->setRootDirectory($this->config->path('application.uploader.filesDirectory'));

        var_dump($uploader); die;

        $uploadedFiles = $this->request->getUploadedFiles();

        if(0 == count($uploadedFiles)) {
            $this->error([
                'message' => 'Bad request. No files for upload'
            ]);
        } else {
            if(count($uploadedFiles) > 1) {
                $this->error([
                    'message' => 'Allowed only one file per one upload request'
                ]);
            } else {
                /** @var FileRequested $uploadedFile */
                $uploadedFile = current($uploadedFiles);

                if($uploadedFile->isUploaded()) {
                    $file = [
                        $uploadedFile->getKey(),
                        $uploadedFile->getName(),
                        $uploadedFile->getSize(),
                        $uploadedFile->getRealMimeType(),
                        $uploadedFile->getTemporaryName(),
                        $uploadedFile->getExtension()
                    ];

                    $this->response([
                        'file' => $file,
                        'request' => $this->request->getPost()
                    ]);
                } else {
                    $this->error([
                        'message' => $uploadedFile->getErrorDescription()
                    ]);
                }
            }
        }

    }

    public function linkAction()
    {

    }

}