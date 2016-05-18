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

    public function indexAction($hash = null)
    {
        /** @var Files $file */
        $file = Files::query()->where('hash', $hash)->first();

        if (!$file->exists()) {
            $this->error(['message' => "File do not exist or was removed {$hash}"], 404);
        } else {

            $token = 'none';

            if($file->isProtected()) {
                if($this->authorizerToken->isGuest()) {
                    $this->error(['message' => 'Protected file. Use authorized token for access this file'])->send();
                    exit();
                }
                $token = $this->authorizerToken->getModel()->getToken();
            } else {
                $alias = $this->config->path('application.uploader.public_uri');
                $path = sprintf('%s/%s', $alias, $file->getRelativePath());
                $links['direct'] = $this->url->full($path);
            }

            if($file->getStatus() == Files::STATUS_ACTIVE) {
                $links['download'] = $this->url->full(sprintf('file/dl/%s', $file->getHash()), ['token' => $token]);
                $links['delete'] = $this->url->full(sprintf('file/delete/%s', $file->getHash()), ['token' => $token]);
                $links['raw'] = $this->url->full(sprintf('file/view-raw/%s', $file->getHash()), ['token' => $token]);
            } else {
                $links['activate'] = $this->url->full(sprintf('file/activate/%s', $file->getHash()), ['token' => $token]);
            }

            $this->response([
                'links' => $links,
                'file' => $file->toResponse(),
            ]);
        }

    }

    public function dlAction()
    {
        $hash = $this->router->getDirtyMatches()[0];
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

    public function viewRawAction()
    {
        $hash = $this->router->getDirtyMatches()[0];
        $file = Files::item($hash);

        $realpath = $this->preparePath($file);

        $file->increaseViews();

        $this->response->setContentType($file->getMimeType());
        $this->response->sendHeaders();

        readfile($realpath);
        exit();
    }

    public function deleteAction()
    {
        $hash = $this->router->getDirtyMatches()[0];
        $file = Files::hash($hash);

        $this->checkFile($file);

        $this->response([
            'message' => "File #{$file->id()} was deleted",
        ]);
    }

    public function activateAction()
    {
        $hash = $this->router->getDirtyMatches()[0];
        $file = Files::hash($hash);

        $this->checkFile($file);

        $this->response([
            'message' => "File #{$file->id()} was activated",
        ]);
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
            $this->error(['message' => "File not found"], 404)->send();
            exit();
        }

        if($file->isProtected() && $this->authorizerToken->isGuest()) {
            $this->error(['message' => 'Protected file. Use authorized token for access this file'])->send();
            exit();
        }
    }

}