<?php

namespace FileStorage\Controllers;

use Dez\Http\Request\File as FileRequested;
use Dez\Mvc\Controller\MvcException;
use FileStorage\Models\Categories;
use FileStorage\Models\Files;
use FileStorage\Services\Uploader\Drivers\UploadedFile;
use FileStorage\Services\Uploader\File;
use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Services\Uploader\Resource\FileHttp;
use FileStorage\Services\Uploader\Uploader;
use FileStorage\Services\Uploader\UploaderException;

class UploadController extends ControllerJson {

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function indexAction()
    {



        $uploader = new Uploader();
        $uploader->setRoot($this->config->path('application.uploader.filesDirectory'));

        $uploader->setDriver(new UploadedFile());

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
                
                $category = Categories::one($this->request->getPost('category'));
                $uploader->setSubDirectory("{$category->getSlug()}-{$category->hash()}");

                try {
                    $uploaded = $uploader->upload($uploadedFile);

                    $file = new Files();

                    $file->setRelativePath($uploaded->getRelativePath());
                    $file->setHash($uploaded->getHash());
                    $file->setExtension($uploaded->getExtension());
                    $file->setMimeType($uploaded->getMimeType());
                    $file->setCategoryId($category->id());
                    $file->setCreatedAt(time());
                    $file->setSize($uploaded->getSize());

                    if(! $file->save()) {
                        throw new MvcException("Can not be saved");
                    }

                    $this->response([
                        'file' => $file->toObject()
                    ]);
                } catch (\Exception $exception) {
                    $this->error(['message' => $exception->getMessage()]);
                }
            }
        }

    }

    public function linkAction()
    {

    }

}