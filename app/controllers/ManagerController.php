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

        $this->view->set('session_progress_name', ini_get('session.upload_progress.name'));
    }

    public function usersAction($subAction = null)
    {
        $this->view->set('content', $this->execute([
            'namespace' => 'FileStorage\\Controllers\\SubControllers\\',
            'controller' => 'users',
            'action' => $subAction
        ], true));
    }

    public function filesAction($subAction = null)
    {
        $this->view->set('content', $this->execute([
            'namespace' => 'FileStorage\\Controllers\\SubControllers\\',
            'controller' => 'files',
            'action' => $subAction
        ], true));
    }

    public function uploadFileAction()
    {
        $this->view->set('categories', Categories::all());
    }

    public function categoriesAction()
    {
        $this->view->set('categories', Categories::all());
    }

    public function createCategoryAction()
    {
        if($this->request->isPost()) {
            $category = new Categories();
            $category->setName($this->request->getPost('name'));
            $category->save();
        } else {
            $this->flash->error('Only for POST method');
        }

        $this->redirect('manager/categories');
    }

    public function deleteCategoryAction($id)
    {
        $category = Categories::one($id);
        $category->deactivate();

        $this->flash->error("Category #{$category->id()} was deleted");
        $this->redirect('manager/categories');
    }

    public function activateCategoryAction($id)
    {
        $category = Categories::one($id);
        $category->activate();

        $this->flash->error("Category #{$category->id()} was activated");
        $this->redirect('manager/categories');
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

    public function serverInfoAction()
    {

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