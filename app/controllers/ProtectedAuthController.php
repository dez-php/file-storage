<?php

namespace FileStorage\Controllers;

use Dez\Auth\AuthException;
use Dez\Validation\Validation;
use FileStorage\Core\Mvc\ControllerJson;

class ProtectedAuthController extends ControllerJson {

    public function indexAction()
    {
        $this->response([
            'message' => 'This is protected area'
        ]);
    }

    public function statusAction()
    {

        try{
            $this->auth->identifyToken($this->request->getQuery('token'));
            $this->response([
                'auth-status' => 'authorized',
                'auth-data' => [
                    'user' => $this->auth->user()->toArray() + [
                        'token' => $this->auth->user()->tokens()->first()->toArray()
                    ],
                ]
            ]);
        } catch (\Exception $exception) {
            $this->error([
                'auth-status' => 'guest',
            ]);
        }

    }

    public function getTokenAction()
    {
        $data = [
            'email' => $this->request->getFromArray($this->router->getDirtyMatches(), 0, null),
            'password' => $this->request->getFromArray($this->router->getDirtyMatches(), 1, null),
        ];

        $validation = new Validation($data);

        $validation->email('email', 'Wrong e-mail format');
        $validation->password('password', null, 'Wrong password');
        $validation->validate();

        if($validation->isFailure()) {
            $this->error([
                'messages' => $validation->getMessages()
            ]);
        } else {
            try {
                $this->response([
                    'token' => $this->auth->generateToken($data['email'], $data['password'])
                ]);
            } catch (AuthException $e) {
                $this->error([
                    'message' => $e->getMessage()
                ]);
            } catch (\Exception $e) {
                $this->error([
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

}