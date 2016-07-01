<?php

namespace FileStorage\Services\Uploader\Drivers;

use FileStorage\Services\MimeTypes;
use FileStorage\Services\Uploader\Driver;
use FileStorage\Services\Uploader\FileInfo;
use FileStorage\Services\Uploader\UploaderException;

class DirectLink extends Driver
{

    /**
     * @param $source
     * @return $this
     * @throws UploaderException
     */
    public function upload($source)
    {
        if (!filter_var($source, FILTER_VALIDATE_URL)) {
            throw new UploaderException("Url '{$source}' not valid. Must been direct http-link on file");
        }

        $headers = get_headers($source, true);

        $size = $headers['Content-Length'];
        $contentType = trim(explode(';', $headers['Content-Type'])[0]);

        $extension = MimeTypes::getExtension($contentType);

        if (null === $extension) {
            throw new UploaderException("No extensions found for content-type: {$contentType}");
        }

        $this->validateSize($size)
            ->validateFileType('extensions', $extension)
            ->validateFileType('mimes', $contentType);

        file_put_contents($this->getUploader()->downloadProgressPath(), 0);

        $hash = md5($size . $contentType . $source);
        $filepath = sprintf('%s/%s.%s', $this->getUploader()->destinationPath(), $hash, $extension);

        $reader = fopen($source, 'rb');
        $writer = fopen($filepath, 'w+');

        $downloaded = 0;
        $chunkSize = 8192;

        while (!feof($reader)) {
            $downloaded += fwrite($writer, fread($reader, $chunkSize));
            file_put_contents($this->getUploader()->downloadProgressPath(), 100 * ($downloaded / $size));
            set_time_limit(10);
        }

        if($size > $downloaded) {
            @ unlink($filepath);
            throw new UploaderException("File is broken. Maybe something wrong with internet connection");
        }

        unlink($this->getUploader()->downloadProgressPath());

        fclose($reader);
        fclose($writer);

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
        return $this;
    }


}