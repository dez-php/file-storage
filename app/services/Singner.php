<?php

namespace FileStorage\Services;

use FileStorage\Core\InjectableAware;

/**
 * Class Singner
 * @package FileStorage\Services
 */
class Singner extends InjectableAware {

    /**
     * @var string
     */
    protected $sing;

    /**
     * @var string
     */
    protected $client;

    /**
     * Singner constructor.
     * @param string $client
     * @param string $sing
     */
    public function __construct($client, $sing)
    {
        $this->client = $client;
        $this->sing = $sing;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $privateKeys = $this->config->path('application.uploader.private_keys');

        if($privateKeys->count() == 0 || ! $privateKeys->has($this->client)) {
            return false;
        }

        $privateKey = $privateKeys->get($this->client);
        $hash = md5($this->client . $privateKey);
        $sign = sha1(implode($hash, $this->request->getPost()));

        return $sign === $this->sing;
    }

}