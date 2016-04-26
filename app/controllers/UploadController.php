<?php

namespace FileStorage\Controllers;

use Dez\Http\Request\File;
use FileStorage\Core\Mvc\ControllerJson;

class UploadController extends ControllerJson {

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function indexAction()
    {

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
                /** @var File $uploadedFile */
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