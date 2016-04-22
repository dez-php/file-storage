<?php

namespace FileStorage\Controllers;

use Dez\Authorizer\Adapter\Token;
use FileStorage\Core\Mvc\ControllerJson;

/**
 * @property Token authorizerToken
 */

class ProtectedAuthController extends ControllerJson {

    public function indexAction()
    {
        $this->response([
            'message' => 'This is protected area for authorizer'
        ], 405);
    }

    public function statusAction()
    {
        $this->response([
            'status' => $this->authorizerToken->isGuest() ? 'guest' : $this->authorizerToken->credentials()->getEmail()
        ]);
    }

    public function createNewUserAction()
    {
        $this->response([
            'status' => 'Not implemented yet'
        ], 501);
    }

    public function getTokenAction()
    {
        try {
            $token = $this->authorizerToken
                ->setEmail($this->request->getQuery('email'))
                ->setPassword($this->request->getQuery('password'))
                ->login()
                ->token()
            ;

            $this->response([
                'token' => $token
            ]);
        } catch (\Exception $exception) {
            $this->error([
                'message' => $exception->getMessage()
            ], 401);
        }
    }

}