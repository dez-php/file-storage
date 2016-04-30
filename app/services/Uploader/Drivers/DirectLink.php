<?php

namespace FileStorage\Services\Uploader\Drivers;

use FileStorage\Services\Uploader\Driver;
use FileStorage\Services\Uploader\FileInfo;
use FileStorage\Services\Uploader\Mimes;
use FileStorage\Services\Uploader\Uploader;
use FileStorage\Services\Uploader\UploaderException;

class DirectLink extends Driver
{

    private $fh;

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

        $headers = get_headers($source, true);

        $size = $headers['Content-Length'];
        $contentType = $headers['Content-Type'];

        $extensions = Mimes::extensions($contentType);

        if (null === $extensions) {
            throw new UploaderException("No extensions found for content-type: {$contentType}");
        }

        $hash = md5($size . $contentType . $source);
        $extension = current($extensions);

        $filepath = sprintf('%s/%s.%s', $this->getUploader()->destinationPath(), $hash, $extension);

        $reader = fopen($source, 'rb');
        $writer = fopen($filepath, 'w+');
        $status = fopen($this->getUploader()->downloadProgressFile(), 'w+');

        $downloaded = 0;
        $count = 0;

        while (!feof($reader)) {
            $downloaded += fwrite($writer, fread($reader, 8192), 8192);

            if(++$count % 10000 == 0) {
                rewind($status);
                fwrite($status, round(100 * ($downloaded / $size), 2) . '%');
            }
        }

        fclose($reader);
        fclose($writer);
        fclose($status);

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