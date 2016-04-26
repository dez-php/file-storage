<?php

namespace FileStorage\Controllers;

use FileStorage\Core\Mvc\ControllerWeb;
use FileStorage\Models\Categories;
use FileStorage\Models\Files;

class ManagerController extends ControllerWeb {

    public function beforeExecute()
    {
        parent::beforeExecute();

        if($this->getAction() !== 'index' && $this->authorizerSession->isGuest()) {
            $this->response->redirect($this->url->path('manager/index'))->send();
        }
    }

    public function filesAction($filter = null)
    {
        return $filter;
    }

    public function uploadFileAction()
    {

    }

    public function categoriesAction()
    {
        $this->view->set('categories', Categories::all());
    }
    
    public function latestAction($limit = 100)
    {
        $latest = Files::latest($limit);
        $this->view->set('latest', $latest->find());
    }

    public function dashboardAction()
    {
        $this->response->redirect($this->url->path('manager/latest'))->send();
    }

    public function indexAction()
    {
        $this->view->setMainLayout('auth-index');
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