<?php

namespace FileStorage\Core\Mvc;

use Dez\Authorizer\Adapter\Session;
use Dez\Authorizer\Adapter\Token;
use Dez\Http\Response;
use Dez\Mvc\Controller;

/**
 * @property Token authorizerToken
 * @property Session authorizerSession
 */

class ControllerWeb extends Controller {

    public function beforeExecute()
    {
        $this->response->setBodyFormat(Response::RESPONSE_HTML);
        $this->view->setMainLayout('index');
    }

    protected function authId()
    {
        return $this->authorizerSession->credentials()->id();
    }
    
}