<?php

namespace FileStorage;

use Dez\Authorizer\Adapter\Token;
use Dez\Config\Config;
use Dez\Http\Response;
use Dez\Mvc\Application\Configurable;
use Dez\Mvc\Controller\MvcException;
use Dez\Mvc\MvcEvent;

class StorageApplication extends Configurable
{

    /**
     * @return $this
     */
    public function initialize()
    {
        $this->configurationErrors()->configurationRoutes();

        if(! file_exists($this->config['application']['production-config'])) {
            $this->config->merge(Config::factory($this->config['application']['production-config']));
        }

        $this->setOrmConnectionName($this->config['db']['connectionName']);

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
            'message' => $message,
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
        $return = '';

        if ($type & E_ERROR) {
            $return .= '& E_ERROR ';
        }

        if ($type & E_WARNING) {
            $return .= '& E_WARNING ';
        }

        if ($type & E_PARSE) {
            $return .= '& E_PARSE ';
        }

        if ($type & E_NOTICE) {
            $return .= '& E_NOTICE ';
        }

        if ($type & E_CORE_ERROR) {
            $return .= '& E_CORE_ERROR ';
        }

        if ($type & E_CORE_WARNING) {
            $return .= '& E_CORE_WARNING ';
        }

        if ($type & E_COMPILE_ERROR) {
            $return .= '& E_COMPILE_ERROR ';
        }

        if ($type & E_COMPILE_WARNING) {
            $return .= '& E_COMPILE_WARNING ';
        }

        if ($type & E_USER_ERROR) {
            $return .= '& E_USER_ERROR ';
        }

        if ($type & E_USER_WARNING) {
            $return .= '& E_USER_WARNING ';
        }

        if ($type & E_USER_NOTICE) {
            $return .= '& E_USER_NOTICE ';
        }

        if ($type & E_STRICT) {
            $return .= '& E_STRICT ';
        }

        if ($type & E_RECOVERABLE_ERROR) {
            $return .= '& E_RECOVERABLE_ERROR ';
        }

        if ($type & E_DEPRECATED) {
            $return .= '& E_DEPRECATED ';
        }

        if ($type & E_USER_DEPRECATED) {
            $return .= '& E_USER_DEPRECATED ';
        }

        return trim(substr($return, 2));
    }

    /**
     * @return $this
     */
    public function injection()
    {
        $this->getDi()->set('auth', function () {
            $authorizerToken = new Token();
            $authorizerToken->setDi($this->getDi());

            return $authorizerToken->initialize();
        });

        return $this;
    }

}