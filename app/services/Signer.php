<?php

namespace FileStorage\Services;

use FileStorage\Core\InjectableAware;

/**
 * Class Signer
 * @package FileStorage\Services
 */
class Signer extends InjectableAware {

    /**
     * @var string
     */
    protected $sing;

    /**
     * @var string
     */
    protected $client;

    protected $crypter;

    /**
     * Signer constructor.
     * @param string $client
     * @param string $sing
     */
    public function __construct($client, $sing)
    {
        $this->client = $client;
        $this->sing = $sing;

        $this->crypter = new CryptString(BaseCoder::instance());
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateSignature()
    {
        $privateKeys = $this->config->path('application.uploader.private_keys');

        if(! $this->client || ! $privateKeys->has($this->client)) {
            throw new \Exception("Client '{$this->client}' not defined or not exist");
        }

        $privateKey = $privateKeys->get($this->client);

        return $this->crypter->encode(md5($privateKey), $privateKey);
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

        $hash = $this->crypter->decode($this->sing, $privateKey);

        return ($hash === md5($privateKey));
    }

}