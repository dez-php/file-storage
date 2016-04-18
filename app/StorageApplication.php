<?php

namespace FileStorage;

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
        set_exception_handler(function (\Exception $exception) {
            $this->createSystemErrorResponse($exception->getMessage(), 'uncaught_exception', $exception->getFile(), $exception->getLine());
        });

        register_shutdown_function(function(){
            $lastPhpError = error_get_last();
            if(null !== $lastPhpError && $lastPhpError['type'] === E_ERROR) {
                $this->createSystemErrorResponse($lastPhpError['message'], 'php_fatal_error', $lastPhpError['file'], $lastPhpError['line']);
            }
        });

        $this->event->addListener(MvcEvent::ON_AFTER_APP_RUN, function(){
            $lastPhpError = error_get_last();
            if(null !== $lastPhpError) {
                throw new MvcException($this->formatPhpError($lastPhpError));
            }
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function injection()
    {
        return $this;
    }

    /**
     * @param array $lastPhpError
     * @return string
     */
    private function formatPhpError(array $lastPhpError = [])
    {
        $phpVersion = PHP_VERSION;
        $exceptionMessage = "PHP {$phpVersion} Error type: {$lastPhpError['type']} with message: [{$lastPhpError['message']}]";
        $exceptionMessage = $exceptionMessage . PHP_EOL . "{$lastPhpError['file']}:{$lastPhpError['line']}";

        return $exceptionMessage;
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

}