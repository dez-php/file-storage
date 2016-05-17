<?php

namespace FileStorage\Controllers\SubControllers;

use Dez\Html\Element\AElement;
use Dez\Http\Response;
use FileStorage\Core\Mvc\ControllerWeb;

class UsersController extends ControllerWeb
{

    public function indexAction()
    {

    }

    public function registerAction()
    {
        $token = $this->authorizerToken->getModel()
            ->query()
            ->where('unique_hash', $this->authorizerSession->getModel()->getUniqueHash())
            ->where('auth_id', $this->authorizerSession->credentials()->id())
            ->first();
        
        if(! $token->exists()) {
            $link = new AElement($this->url->path('manager/users/profile'), 'Generate token');
            $this->flash->warning("{$link} before access this page");
        }
    }

    public function profileAction()
    {
        $token = $this->authorizerToken->getModel()
            ->query()
            ->where('unique_hash', $this->authorizerSession->getModel()->getUniqueHash())
            ->where('auth_id', $this->authorizerSession->credentials()->id())
            ->first();

        $this->view->set('auth', $this->authorizerSession);
        $this->view->set('token', $token);
        $this->view->set('ua', $this->request->getUserAgent());
        $this->view->set('ip', $this->request->getClientIP());
        $this->view->set('real_ip', $this->request->getRealClientIP());
    }

    public function generateTokenAction()
    {
        $password = $this->request->getPost('password');

        try {
            $this->authorizerToken
                ->logout()
                ->setEmail($this->authorizerSession->credentials()->getEmail())
                ->setPassword($password)
                ->login();
        } catch (\Exception $exception) {
            $this->flash->warning($exception->getMessage());
        }

        $this->redirect('manager/users/profile')->send();
    }

}