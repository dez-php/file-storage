<?php

namespace FileStorage\Services\Uploader\Drivers;

use Dez\Http\Request\File;
use FileStorage\Services\MimeTypes;
use FileStorage\Services\Uploader\Driver;
use FileStorage\Services\Uploader\FileInfo;
use FileStorage\Services\Uploader\UploaderException;

class UploadedFile extends Driver {

    /**
     * @param File $source
     * @return $this
     * @throws UploaderException
     */
    public function upload($source)
    {
        if(! ($source instanceof File)) {
            $sourceType = gettype($source);
            throw new UploaderException("Source must been object instance of '". File::class ."' '{$sourceType}' given");
        }

        if($source->getExtension() !== '') {
            $extension = $source->getExtension();
            $contentType = MimeTypes::getMimeTypes($extension);
            if($contentType === null) {
                $contentType = trim(explode(';', $source->getRealMimeType())[0]);
            } else {
                $contentType = end($contentType);
            }
        } else {
            $contentType = trim(explode(';', $source->getRealMimeType())[0]);
            $extension = MimeTypes::getExtension($contentType);
        }

        $this->validateSize($source->getSize())
            ->validateFileType('extensions', $extension)
            ->validateFileType('mimes', $contentType);

        if(! $source->isUploaded()) {
            throw new UploaderException("File with errors: {$source->getErrorDescription()}");
        }

        $temp = new FileInfo($source->getName(), $source->getTemporaryName());

        if(! $temp->isFile()) {
            throw new UploaderException("File is not file");
        }

        $temp->setName($temp->getHash())->setExtension($extension);
        $filepath = sprintf('%s/%s', $this->getUploader()->destinationPath(), $temp->getNameWithExtension());

        if(! $source->moveTo($filepath)) {
            throw new UploaderException("File could not been move to final destination");
        }

        $file = (new FileInfo($temp->getName(), $filepath))
            ->setExtension($temp->getExtension())
            ->setRelativePath($this->getUploader()->relativeFilePath($temp->getNameWithExtension()));
        $temp = null;

        return $file;
    }

    /**
     * @return $this
     * @throws UploaderException
     */
    public function initialize()
    {

    }


}