<?php

namespace FileStorage\Controllers;

use Dez\Url\Uri;
use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Models\Files;

class FileController extends ControllerJson
{

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function indexAction($hash = null)
    {
        /** @var Files $file */
        $file = Files::query()->where('hash', $hash)->first();

        if (!$file->exists()) {
            $this->error(['message' => "File do not exist or was removed {$hash}"], 404);
        } else {
            $alias = $this->config->path('application.uploader.public_uri');
            $path = "{$alias}/{$file->getRelativePath()}";

            $sizes = [$file->getSize('k'), $file->getSize('m'), $file->getSize('g'), $file->getSize('t')];
            $uri = (new Uri($path))->setSchema('http')->setHost($this->request->getHost());

            $this->response([
                'file' => $uri->full(),
                'sizes' => $sizes
            ]);
        }

    }

}