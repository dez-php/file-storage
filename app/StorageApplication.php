<?php

namespace FileStorage;

use Dez\Authorizer\Adapter\Session;
use Dez\Authorizer\Adapter\Token;
use Dez\Config\Config;
use Dez\Http\Response;
use Dez\Mvc\Application\Configurable;
use Dez\Mvc\Controller\MvcException;
use Dez\Mvc\MvcEvent;

/**
 * @property Token authorizerToken
 * @property Session authorizerSession
*/

class StorageApplication extends Configurable
{

    /**
     * @return $this
     */
    public function initialize()
    {
        if(PHP_SAPI === 'cli-server') {
            $_GET['_route'] = $this->request->getServer('path_info', '/');
        }

        $this->configurationErrors()->configurationRoutes();

        $this->session->start();

        if(file_exists($this->config['application']['production-config'])) {
            $this->config->merge(Config::factory($this->config['application']['production-config']));
        }

        $this->setOrmConnectionName($this->config['db']['connectionName']);

        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Request-Method', '*');
        $this->response->setHeader('Access-Control-Request-Headers', '*');

        return $this;
    }

    private function configurationRoutes()
    {
        $this->router
            ->add('/:hash', [
                'controller' => 'file',
                'action' => 'index',
            ])->regex('hash', '[a-f0-9]{32}');

        $this->router
            ->add('/:hash/:action', [
                'controller' => 'file'
            ])->regex('hash', '[a-f0-9]{32}');

        $this->router
            ->add('/manager/:action/:sub_action', [
                'controller' => 'manager'
            ])->regex('sub_action', '[a-z-_]+');

        $this->router
            ->add('/favicon.ico', [
                'controller' => 'manager',
                'action' => 'generate-favicon'
            ]);

        return $this;
    }

    /**
     * @return $this
     */
    private function configurationErrors()
    {
        set_exception_handler(function (\Exception $exception) {
            if ($this->config->path('application.debug.exceptions') == 1) {
                $message = get_class($exception) . ": {$exception->getMessage()}";
                $this->createSystemErrorResponse($message, 'uncaught_exception', $exception->getFile(),
                    $exception->getLine());
            } else {
                $this->createSystemErrorResponse('Debug mode disabled. Message hidden', 'uncaught_exception', 'null', 0);
            }
        });

        register_shutdown_function(function () {
            $lastPhpError = error_get_last();
            if (null !== $lastPhpError && $lastPhpError['type'] === E_ERROR) {
                if ($this->config->path('application.debug.php_errors') == 1) {
                    $this->createSystemErrorResponse($this->formatPhpError($lastPhpError), 'php_fatal_error',
                        $lastPhpError['file'], $lastPhpError['line']);
                } else {
                    $this->createSystemErrorResponse("Debug mode disabled. Message hidden",
                        $this->friendlyErrorType($lastPhpError['type']), 'null', 0);
                }
            }
        });

        $this->event->addListener(MvcEvent::ON_AFTER_APP_RUN, function () {
            $lastPhpError = error_get_last();
            if (null !== $lastPhpError) {
                throw new MvcException($this->formatPhpError($lastPhpError));
            }
        });

        return $this;
    }

    /**
     * @param $message
     * @param $type
     * @param $file
     * @param $line
     *
     * @return Response
     * @throws \Dez\Http\Exception
     */
    private function createSystemErrorResponse($message, $type, $file, $line)
    {
        $responseData = [
            'status' => 'error',
            'error_type' => $type,
            'response' => [
                'message' => $message
            ],
            'location' => "{$file}:{$line}"
        ];

        $response = new Response($responseData, 503);

        return $response->setDi($this->getDi())->setBodyFormat(Response::RESPONSE_API_JSON)->send();
    }

    /**
     * @param array $lastPhpError
     * @return string
     */
    private function formatPhpError(array $lastPhpError = [])
    {
        $phpVersion = PHP_VERSION;
        $exceptionMessage = "PHP {$phpVersion}\n{$this->friendlyErrorType($lastPhpError['type'])} [{$lastPhpError['message']}]";
        $exceptionMessage = $exceptionMessage . PHP_EOL . "{$lastPhpError['file']}:{$lastPhpError['line']}";

        return $exceptionMessage;
    }

    /**
     * @param $type
     * @return integer
     */
    private function friendlyErrorType($type)
    {
        $types = [];

        $typeNames = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
        ];

        foreach (array_keys($typeNames) as $phpType) {
            if($type & $phpType) {
                $types[] = $typeNames[$phpType];
            }
        }

        return implode(' & ', $types);
    }

    /**
     * @return $this
     */
    public function injection()
    {
        $this->getDi()->set('authorizerToken', function () {
            $authorizerToken = new Token();
            $authorizerToken->setDi($this->getDi());
            return $authorizerToken->initialize();
        });

        $this->getDi()->set('authorizerSession', function () {
            $authorizerSession = new Session();
            $authorizerSession->setDi($this->getDi());
            return $authorizerSession->initialize();
        });

        return $this;
    }

}