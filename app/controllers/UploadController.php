<?php

namespace FileStorage\Controllers;

use Dez\Http\Request\File;
use Dez\Mvc\Controller\MvcException;
use Dez\Url\Uri;
use FileStorage\Models\Categories;
use FileStorage\Models\Files;
use FileStorage\Services\Uploader\Drivers\DirectLink;
use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Services\Uploader\Drivers\UploadedFile;
use FileStorage\Services\Uploader\Uploader;

class UploadController extends ControllerJson {

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function dumpAction()
    {
        $this->response([
            'get' => $this->request->getQuery(),
            'post' => $this->request->getPost(),
            'has_files' => $this->request->hasFiles(),
            'server' => $this->request->getServer(),
        ]);
    }
    
    public function indexAction()
    {

        if($this->authorizerToken->isGuest()) {
            $this->error(['message' => 'Use token for can access to upload files'])->send(); exit();
        }

        $isProtected = (boolean) $this->request->getPost('protected', 0);
        $uploadType = $this->request->getPost('upload_type', 'local');

        $root = $isProtected
            ? $this->config->path('application.uploader.directories.private')
            : $this->config->path('application.uploader.directories.public');

        $uploader = new Uploader();
        $uploader->setRoot($root);

        if($uploadType === 'direct_link') {
            $uploader->setDriver(new DirectLink());
            $uploadSource = $this->request->getPost($uploadType, false);
        } else {
            $uploader->setDriver(new UploadedFile());
            $uploadedFiles = $this->request->getUploadedFiles();
            $uploadSource = current($uploadedFiles);
        }

        $category = Categories::one($this->request->getPost('category_id', 1));

        if(! $category->exists()) {
            throw new MvcException("Requested category do not exist");
        }

        $yearHash = substr(md5(date('Y')), 0, 8);
        $monthHash = substr(md5(date('m')), 0, 8);
        $dayHash = substr(md5(date('d')), 0, 8);
        $categoryHash = substr(md5($category->id()), 0, 8);
        $ownerHash = substr(md5($category->getUserId()), 0, 8);

        $uglyPath = "{$ownerHash}/{$categoryHash}/{$yearHash}/{$monthHash}/{$dayHash}/";
        $uploader->setSubDirectory($uglyPath);

        try {
            $uploaded = $uploader->upload($uploadSource);

            $file = new Files();

            $file->setName($this->request->getPost('name'));
            $file->setRelativePath($uploaded->getRelativePath());
            $file->setHash($uploaded->getHash());
            $file->setMd5File($uploaded->getHashFile('md5'));
            $file->setExtension($uploaded->getExtension());
            $file->setMimeType($uploaded->getMimeType());
            $file->setCategoryId($category->id());
            $file->setUserId($this->authorizerToken->credentials()->id());
            $file->setCreatedAt(time());
            $file->setSize($uploaded->getSize());
            $file->setProtected((integer) $isProtected);

            if(! $file->save()) {
                throw new MvcException("Can not be saved");
            }

            $this->response([
                'uploaded_file_uid' => $file->getHash(),
            ]);
        } catch (\Exception $exception) {
            $this->error(['message' => $exception->getMessage()]);
        }
    }

    public function updateAction()
    {

    }

    public function downloadFileProgressAction()
    {
        $uploader = new Uploader();
        $uploader->setRoot($this->config->path('application.uploader.directories.public'));

        $alias = $this->config->path('application.uploader.public_uri');
        $path = sprintf('%s/%s', $alias, $uploader->downloadProgressFile());

        fclose(fopen($uploader->downloadProgressPath(), 'w'));

        $uri = (new Uri($path))->setSchema('http')->setHost($this->request->getHost());

        $this->response(['url' => $uri->full(),]);
    }

}