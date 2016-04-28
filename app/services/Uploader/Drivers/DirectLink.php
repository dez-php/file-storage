<?php

namespace FileStorage\Services\Uploader\Drivers;

use FileStorage\Services\Uploader\Driver;
use FileStorage\Services\Uploader\FileInfo;
use FileStorage\Services\Uploader\Mimes;
use FileStorage\Services\Uploader\UploaderException;

class DirectLink extends Driver
{

    private $curl;

    /**
     * @param $source
     * @return $this
     * @throws UploaderException
     */
    public function upload($source)
    {
        if(! filter_var($source, FILTER_VALIDATE_URL)) {
            throw new UploaderException("Url not valid. Must been direct http-link on file");
        }

        curl_setopt($this->curl, CURLOPT_URL, $source);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_SSLVERSION, 3);

        $content = curl_exec($this->curl);
        $info = curl_getinfo($this->curl);
        $error = curl_error($this->curl);

        curl_close($this->curl);

        if($error !== '') {
            throw new UploaderException("cURL error: {$error}");
        }

        $contentType = $info['content_type'];
        $extensions = Mimes::extensions($contentType);
        
        if(null === $extensions) {
            throw new UploaderException("No extensions found for content-type: {$contentType}");
        }

        $hash = md5($content);
        $extension = current($extensions);

        $filepath = sprintf('%s/%s.%s', $this->getUploader()->destinationPath(), $hash, $extension);

        if(! file_put_contents($filepath, $content)) {
            throw new UploaderException("File could not been move to final destination");
        }

        $file = new FileInfo($hash, $filepath);

        $file
            ->setExtension($extension)
            ->setRelativePath($this->getUploader()->relativeFilePath($file->getNameWithExtension()));

        return $file;
    }

    /**
     * @return $this
     */
    public function initialize()
    {
        $this->curl = curl_init();

        return $this;
    }


}