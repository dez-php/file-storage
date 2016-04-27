<?php

namespace FileStorage\Services\Uploader\Resource;

use Dez\Http\Request\File;
use FileStorage\Services\Uploader\FileResource;
use FileStorage\Services\Uploader\UploaderException;

class FileRequest extends FileResource {

    /**
     * @param $path
     * @return $this
     */
    public function moveTo($path)
    {
        /**
         * @var File $source
         */
        $source = $this->source;

        $source->moveTo($path);

        return $this;
    }

    /**
     * @return $this
     * @throws UploaderException
     */
    public function process()
    {
        /**
         * @var File $source
         */
        $source = $this->source;

        if(! $source->isUploaded()) {
            throw new UploaderException("FileRequestResource: {$source->getErrorDescription()}");
        }

        $this->setName($source->getName());
        $this->setSize($source->getSize());
        $this->setMime($source->getMimeType());
        $this->setExtension($source->getExtension());

        return $this;
    }


}