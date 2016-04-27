<?php

namespace FileStorage\Services\Uploader\Drivers;

use FileStorage\Services\Uploader\Driver;

class DirectHttpLink extends Driver
{

    private $fileContent;

    /**
     * @param $path
     * @return $this
     */
    public function moveTo($path)
    {
        file_put_contents($path, $this->fileContent);

        return $this;
    }

    /**
     * @return $this
     */
    public function initialize()
    {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->source);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSLVERSION, 3);

        $this->fileContent = curl_exec($curl);
        $info = curl_getinfo($curl);

        $this->setExtension(end(explode('.', $this->source)));
        $this->setName(end(explode('/', $this->source)));
        $this->setMime($info['content_type']);
        $this->setSize($info['size_download']);

        return $this;
    }


}