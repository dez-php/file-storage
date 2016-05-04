<?php

namespace FileStorage\Services\Uploader;

class FileInfo extends \SplFileInfo implements \JsonSerializable {

    protected $name;

    protected $relativePath;

    protected $mimeType;

    protected $extension;

    /**
     * FileInfo constructor.
     * @param $name
     * @param $file
     */
    public function __construct($name, $file)
    {
        parent::__construct($file);

        $this->setExtension(pathinfo($name, PATHINFO_EXTENSION));
        $this->setName($name);
    }

    /**
     * @param string $algorithm
     * @return mixed
     */
    public function getHashFile($algorithm = 'md5')
    {
        return hash_file($algorithm, $this->getPathname());
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return md5($this->getHashFile('md5') . microtime());
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNameWithExtension()
    {
        return ! (boolean) $this->extension ? $this->getName() : sprintf('%s.%s', $this->getName(), $this->getExtension());
    }

    /**
     * @param mixed $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        if(null === $this->mimeType) {
            $this->mimeType = Mimes::mime($this->getExtension());
            if(null === $this->mimeType) {
                $info = new \finfo(FILEINFO_MIME_TYPE);
                $this->mimeType = $info->file($this->getPathname());
            }
        }

        return $this->mimeType;
    }

    /**
     * @param string $extension
     * @return mixed
     */
    public function setExtension($extension)
    {
        $this->extension = strtolower($extension);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return mixed
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }

    /**
     * @param mixed $relativePath
     * @return static
     */
    public function setRelativePath($relativePath)
    {
        $this->relativePath = $relativePath;

        return $this;
    }

    /**
     * @return \stdClass
     */
    function jsonSerialize()
    {
        $info = new \stdClass();

        $info->name = $this->getName();
        $info->fileName = $this->getNameWithExtension();
        $info->mimeType = $this->getMimeType();
        $info->extension = $this->getExtension();
        $info->size = $this->getSize();
        $info->relativePath = $this->getRelativePath();
        $info->filePath = $this->getPathname();

        return $info;
    }

}