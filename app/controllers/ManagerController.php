<?php

namespace FileStorage\Controllers;

use FileStorage\Core\Mvc\ControllerWeb;

class ManagerController extends ControllerWeb {

    public function indexAction()
    {
        if($this->request->isPost()) {
            try {
                $this->authorizerSession
                    ->setEmail($this->request->getPost('email'))
                    ->setPassword($this->request->getPost('password'))
                    ->login()
                ;
                $this->flash->success('Welcome on board');
            } catch (\Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
            $this->response->redirect($this->url->path('manager/index'))->send();
        }
    }

    public function closeSessionAction()
    {
        $this->authorizerSession->logout();
        $this->flash->notice('You are logged out');
        $this->response->redirect($this->url->path('manager/index'))->send();
    }

}