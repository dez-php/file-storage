<?php

namespace FileStorage\Controllers\SubControllers;

use Dez\Authorizer\Adapter\Session;
use Dez\Authorizer\Models\CredentialModel;
use Dez\Html\Element\AElement;
use Dez\Http\Response;
use Dez\Validation\Validation;
use FileStorage\Core\Mvc\ControllerWeb;

class UsersController extends ControllerWeb
{

    public function indexAction()
    {
        $this->view->set('users', CredentialModel::all());
    }

    public function updateStatusAction()
    {
        $status = $this->request->getQuery('status');
        $updated = date('Y-m-d H:i:s');
        $userId = $this->request->getQuery('id');

        CredentialModel::one($userId)->setStatus($status)->setUpdatedAt($updated)->save();

        $this->flash->notice("User #{$userId} status was updated on {$status}");

        $this->redirect('manager/users/index')->send();
    }

    public function registerAction()
    {
        if($this->request->isPost()) {

            $validation = new Validation($this->request->getPost());

            $validation->email('email');
            $validation->password('password');

            if($validation->validate()) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                $authorizer = new Session();
                $authorizer->setDi($this->getDi());

                try {
                    $authorizer->register($email, $password);
                    $this->flash->notice("User #{$authorizer->credentials()->id()} was register in system");
                } catch(\Exception $exception) {
                    $this->flash->warning($exception->getMessage());
                }
            } else {
                foreach ($validation->getMessages() as $messages) {
                    foreach($messages as $message) {
                        $this->flash->warning($message->getMessage());
                    }
                }
            }

            $this->redirect('manager/users/index')->send();
        }
    }

    public function profileAction()
    {
        $token = $this->authorizerToken->getModel()
            ->query()
            ->where('unique_hash', $this->authorizerSession->getModel()->getUniqueHash())
            ->where('auth_id', $this->authorizerSession->credentials()->id())
            ->first();

        $this->view->set('auth', $this->authorizerSession);
        $this->view->set('token', $token);
        $this->view->set('ua', $this->request->getUserAgent());
        $this->view->set('ip', $this->request->getClientIP());
        $this->view->set('real_ip', $this->request->getRealClientIP());
    }

    public function generateTokenAction()
    {
        $password = $this->request->getPost('password');

        try {
            $this->authorizerToken
                ->logout()
                ->setEmail($this->authorizerSession->credentials()->getEmail())
                ->setPassword($password)
                ->login();
        } catch (\Exception $exception) {
            $this->flash->warning($exception->getMessage());
        }

        $this->redirect('manager/users/profile')->send();
    }

}