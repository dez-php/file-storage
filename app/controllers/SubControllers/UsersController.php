<?php

namespace FileStorage\Controllers\SubControllers;

use Dez\Http\Response;
use FileStorage\Core\Mvc\ControllerWeb;
use FileStorage\Services\Emoji;

class UsersController extends ControllerWeb
{

    public function indexAction()
    {

    }

    public function registerAction()
    {
        die(Emoji::POUTING_FACE);
    }

    public function profileAction()
    {
        $this->view->set('auth', $this->authorizerSession);
        $this->view->set('token', $this->authorizerToken->getModel()->query()->where('unique_hash',
            $this->authorizerSession->getModel()->getUniqueHash())->first());
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