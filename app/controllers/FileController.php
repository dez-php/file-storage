<?php

namespace FileStorage\Controllers;

use Dez\Url\Uri;
use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Models\Files;
use FileStorage\Services\Emoji;

class FileController extends ControllerJson
{

    public function beforeExecute()
    {
        parent::beforeExecute();
    }

    public function indexAction($hash)
    {
        /** @var Files $file */
        $file = Files::query()->where('hash', $hash)->first();

        $this->checkFile($file);

        if(! $file->isProtected()) {
            $alias = $this->config->path('application.uploader.public_uri');
            $path = sprintf('%s/%s', $alias, $file->getRelativePath());
            $links['direct'] = $this->url->full($path);
        }

        $links['download'] = $this->url->full("{$file->getHash()}/dl");
        $links['raw'] = $this->url->full("{$file->getHash()}/raw");
        $links['detailed'] = $this->url->full("{$file->getHash()}/detailed");

        $this->response(['links' => $links,]);

    }

    public function linksAction($hash)
    {
        $this->indexAction($hash);
    }

    public function detailedAction($hash)
    {
        /** @var Files $file */
        $file = Files::query()->where('hash', $hash)->first();
        $this->checkFile($file);
        $this->response($file->toResponse());
    }

    public function dlAction($hash)
    {
        $file = Files::item($hash);

        $realpath = $this->preparePath($file);
        
        $file->increaseDownloads();

        $name = sprintf('%s_%s.%s', \URLify::filter($file->getName()), $file->getHash(), $file->getExtension());

        $this->response->setContentType($file->getMimeType());
        $this->response->setHeader('Content-Disposition', "attachment; filename=$name");
        $this->response->setHeader('Content-Length', $file->getSize());
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->sendHeaders();

        readfile($realpath);
        exit();
    }

    public function rawAction($hash)
    {
        $file = Files::item($hash);

        $realpath = $this->preparePath($file);

        $file->increaseViews();

        $this->response->setContentType($file->getMimeType());
        $this->response->sendHeaders();

        readfile($realpath);
        exit();
    }

    public function removeAction($hash)
    {
        if(! $this->authorizerToken->isGuest()) {
            $file = Files::hash($hash);

            $realpath = $this->preparePath($file);

            $file->delete();
            unlink($realpath);

            $this->response([
                'message' => "File #{$file->id()} was deleted",
            ]);
        } else {
            $this->error([
                'message' => 'Use token for can access this action'
            ]);
        }
    }

    private function preparePath(Files $file)
    {
        $this->checkFile($file);

        $root = $file->isProtected()
            ? $this->config->path('application.uploader.directories.private')
            : $this->config->path('application.uploader.directories.public');

        $realpath = realpath(sprintf('%s/%s', $root, $file->getRelativePath()));

        if(false === $realpath) {
            $this->error(['message' => 'File was broken. Can not resolve real path of file'])->send();
            exit();
        }

        return $realpath;
    }

    private function checkFile(Files $file)
    {
        if(! $file->exists()) {
            $this->error(['message' => "File not found or was removed"], 404)->send();
            exit();
        }
    }

}