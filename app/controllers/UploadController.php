<?php

namespace FileStorage\Controllers;

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

        if(! $this->signer->validate()) {
            $this->signatureFailure();
        }

        $isProtected = (boolean) $this->request->getPost('protected', 0);
        $uploadType = $this->request->getPost('upload_type', 'local');

        $root = $isProtected
            ? $this->config->path('application.uploader.directories.private')
            : $this->config->path('application.uploader.directories.public');

        $uploader = new Uploader();
        $uploader->setRoot($root);

        if($uploadType === 'direct-link') {
            $uploader->setDriver(new DirectLink());
            $uploadSource = $this->request->getPost('direct_link', false);
        } else {
            $uploader->setDriver(new UploadedFile());
666            $uploadedFiles = $this->request->getUploadedFiles();
            $uploadSource = current($uploadedFiles);
        }

        $category = Categories::one($this->request->getPost('category_id', 1));
        
        if(! $category->exists()) {
            throw new MvcException("Category do not exist");
        }
        
        $uploader->setSubDirectory("{$category->getSlug()}/{$category->hash()}");

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
            $file->setCreatedAt(time());
            $file->setSize($uploaded->getSize());
            $file->setProtected((integer) $isProtected);

            if(! $file->save()) {
                throw new MvcException("Can not be saved");
            }

            $this->response([
                'file' => $file->toObject(),
            ]);
        } catch (\Exception $exception) {
            $this->error(['message' => $exception->getMessage()]);
        }

    }

    public function downloadFileProgressAction()
    {
        $uploader = new Uploader();
        $uploader->setRoot($this->config->path('application.uploader.directories.public'));

        $alias = $this->config->path('application.uploader.public_uri');
        $path = sprintf('%s/%s', $alias, $uploader->downloadProgressFile());

        $uri = (new Uri($path))->setSchema('http')->setHost($this->request->getHost());

        $this->response([
            'url' => $uri->full(),
        ]);
    }

    public function linkAction()
    {

    }

}