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

    public function deletedAction()
    {

    }

    public function protectedAction()
    {

    }

    public function categoryAction()
    {
        /**
         * @var $category Categories
        */
        $slug = $this->request->getQuery('slug');
        $category = Categories::query()->where('slug', $slug)->first();

        $this->view->set('files', $category->files());
    }

}