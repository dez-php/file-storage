<?php

namespace FileStorage\Controllers;

use Dez\Authorizer\Adapter\Token;
use Dez\Validation\Rules\Email;
use Dez\Validation\Validation;
use FileStorage\Core\Mvc\ControllerJson;

/**
 * @property Token authorizerToken
 */

class ProtectedAuthController extends ControllerJson {

    public function indexAction()
    {
        $this->response(['message' => 'This is protected area for authorizer'], 405);
    }

    public function statusAction()
    {
        $this->response([
            'status' => $this->authorizerToken->isGuest()
                ? 'guest'
                : $this->authorizerToken->credentials()->getEmail()
        ]);
    }

    public function registerUserAction()
    {
        if($this->authorizerToken->isGuest()) {
            $this->error(['message' => 'Access denied. Restricted area!']);
        } else {
            $validator = new Validation($this->request->getQuery());

            $validator->required('email')->add(new Email());
            $validator->password('password');

            if(! $validator->validate()) {
                $this->error([
                    'message' => 'Validation failure',
                    'messages' => $validator->getMessages(),
                ], 401);
            } else {
                try {
                    $authorizer = new Token();
                    $authorizer->register($this->request->getQuery('email'), $this->request->getQuery('password'));
                    $this->response([
                        'message' => 'User account was registered',
                    ]);
                } catch (\Exception $exception) {
                    $this->error(['message' => $exception->getMessage()], 401);
                }
            }
        }
    }

    public function getTokenAction()
    {
        $token = $this->authorizerToken->getModel()
            ->query()
            ->where('unique_hash', $this->authorizerSession->getModel()->getUniqueHash())
            ->where('auth_id', $this->authorizerSession->credentials()->id())
            ->first();

        $this->response(['token' => $token->getToken()]);
    }

    public function createTokenAction()
    {
        $validator = new Validation($this->request->getQuery());

        $validator->required('email')->add(new Email());
        $validator->password('password');

        if(! $validator->validate()) {
            $this->error([
                'message' => 'Validation failure',
                'messages' => $validator->getMessages(),
            ], 401);
        } else {
            try {
                $token = $this->authorizerToken
                    ->logout()
                    ->setEmail($this->request->getQuery('email'))
                    ->setPassword($this->request->getQuery('password'))
                    ->login()
                    ->token();

                $this->response(['token' => $token]);
            } catch (\Exception $exception) {
                $this->error(['message' => $exception->getMessage()], 401);
            }
        }
    }

}