<?php

namespace FileStorage\Services\Uploader\Drivers;

use FileStorage\Services\Uploader\Driver;
use FileStorage\Services\Uploader\FileInfo;
use FileStorage\Services\Uploader\Mimes;
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
            throw new UploaderException("Url not valid. Must been direct http-link on file");
        }

        file_put_contents($this->getUploader()->downloadProgressPath(), 0);
        $headers = get_headers($source, true);

        $size = $headers['Content-Length'];
        $contentType = $headers['Content-Type'];

        $this->validateSize($size);

        $extensions = Mimes::extensions($contentType);
        $extension = current($extensions);

        if (null === $extensions) {
            throw new UploaderException("No extensions found for content-type: {$contentType}");
        }

        $hash = md5($size . $contentType . $source);

        $filepath = sprintf('%s/%s.%s', $this->getUploader()->destinationPath(), $hash, $extension);

        $reader = fopen($source, 'rb');
        $writer = fopen($filepath, 'w+');

        $downloaded = 0;
        $chunkSize = (integer) min($size / 100, 8192);

        while (!feof($reader)) {
            $downloaded += fwrite($writer, fread($reader, $chunkSize));
            file_put_contents($this->getUploader()->downloadProgressPath(), 100 * ($downloaded / $size));
        }

        if($size > $downloaded) {
            @ unlink($filepath);
            throw new UploaderException("File is broken. Maybe something wrong with internet");
        }

        file_put_contents($this->getUploader()->downloadProgressPath(), 0);

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