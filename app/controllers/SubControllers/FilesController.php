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
        $files = Files::latest()->find();
        $this->view->set('files', $files);
    }

    public function protectedAction()
    {
        $files = Files::latest()->where('protected', 1)->find();
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

        $this->view->set('files', $category->files());
    }

}