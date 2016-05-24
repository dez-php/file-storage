<?php

namespace FileStorage\Controllers\SubControllers;

use FileStorage\Core\Mvc\ControllerWeb;
use FileStorage\Models\Categories;
use FileStorage\Models\Files;

class FilesController extends ControllerWeb
{

    public function indexAction()
    {
        $this->redirect('manager/files/latest')->send();
    }

    public function latestAction()
    {
        $files = Files::latest()->where('user_id', $this->authorizerSession->credentials()->id())->find();
        $this->view->set('files', $files);
    }

    public function protectedAction()
    {
        $files = Files::latest()->where('user_id', $this->authorizerSession->credentials()->id())->where('protected', 1)->find();
        $this->view->set('files', $files);
    }

    public function categoryAction()
    {
        /**
         * @var $category Categories
        */
        $slug = $this->request->getQuery('slug');
        $category = Categories::query()->where('slug', $slug)->first();

        if(! $category->exists()) {
            $this->flash->warning("Category with slug '{$slug}' not found");
            $this->redirect('manager/files/latest')->send();
        }

        $files = Files::latest()
            ->where('category_id', $category->id())
            ->where('user_id', $this->authorizerSession->credentials()->id())
            ->find();

        $this->view->set('files', $files);
    }

}