<?php

namespace FileStorage\Controllers;

use FileStorage\Core\Mvc\ControllerJson;
use FileStorage\Models\Categories;
use FileStorage\Models\Files;

class ProtectedController extends ControllerJson {

    public function indexAction()
    {
        $this->response([], 200);
    }

    public function categoriesAction()
    {
        $this->response([
            'list' => Categories::all()->toArray()
        ], 200);
    }

    public function filesAction()
    {
        $files = Files::all();
        $this->response([
            'count' => $files->count(),
            'list' => array_map(function($item){ return $item['hash']; }, $files->toArray())
        ], 200);
    }

}