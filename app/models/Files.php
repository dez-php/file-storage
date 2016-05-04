<?php

namespace FileStorage\Models;

use Dez\ORM\Model\QueryBuilder;
use Dez\ORM\Model\Table;
use FileStorage\Services\Uploader\Uploader;

class Files extends Table {

    const STATUS_ACTIVE = 'active';

    const STATUS_DELETED = 'deleted';
    
    protected static $table = 'stored_files';

    /**
     * @param int $limit
     * @return QueryBuilder
     */
    public static function latest($limit = 100)
    {
        return static::query()->where('status', Files::STATUS_ACTIVE)->order('created_at', 'desc')->limit($limit);
    }

    /**
     * @param $hash
     * @return Files
     */
    public static function item($hash)
    {
        return Files::query()->where('hash', $hash)->where('status', static::STATUS_ACTIVE)->first();
    }

    /**
     * @param $hash
     * @return Files
     */
    public static function hash($hash)
    {
        return Files::query()->where('hash', $hash)->first();
    }

    /**
     * @return $this
     */
    public function deactivate()
    {
        $this->setStatus(static::STATUS_DELETED)->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function activate()
    {
        $this->setStatus(static::STATUS_ACTIVE)->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function increaseDownloads()
    {
        $this->setDownloads($this->getDownloads() + 1)->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function increaseViews()
    {
        $this->setViews($this->getViews() + 1)->save();

        return $this;
    }

    /**
     * @return Categories
     * @throws \Dez\ORM\Exception
     */
    public function category()
    {
        return $this->hasOne(Categories::class, 'id', 'category_id');
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->get('category_id');
    }

    /**
     * @param $category_id
     * @return $this
     */
    public function setCategoryId($category_id)
    {
        $this->set('category_id', $category_id);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->get('name') == '' ? 'none' : $this->get('name');
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->set('name', $name);

        return $this;
    }

    /**
     * @param string $unit
     * @return mixed
     */
    public function getSize($unit = null)
    {
        $unit = strtoupper($unit);
        $scales = [
            'K' => 1024,
            'M' => (1024 * 1024),
            'G' => (1024 * 1024 * 1024),
            'T' => (1024 * 1024 * 1024 * 1024),
        ];

        $scale = isset($scales[$unit]) ? $scales[$unit] : 1;

        return bcdiv($this->get('size'), $scale, 6) . $unit;
    }

    /**
     * @param $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->set('size', $size);

        return $this;
    }

    /**
     * @return string
     */
    public function getMd5File()
    {
        return $this->get('md5_file');
    }

    /**
     * @param $md5
     * @return $this
     */
    public function setMd5File($md5)
    {
        $this->set('md5_file', $md5);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->get('hash');
    }

    /**
     * @param $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->set('hash', $hash);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelativePath()
    {
        return $this->get('relative_path');
    }

    /**
     * @param $relative_path
     * @return $this
     */
    public function setRelativePath($relative_path)
    {
        $this->set('relative_path', $relative_path);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->get('extension');
    }

    /**
     * @param $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->set('extension', $extension);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->get('mime_type');
    }

    /**
     * @param $mime_type
     * @return $this
     */
    public function setMimeType($mime_type)
    {
        $this->set('mime_type', $mime_type);

        return $this;
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return (boolean) $this->get('protected');
    }

    /**
     * @param int $protected
     * @return $this
     */
    public function setProtected($protected = 0)
    {
        $this->set('protected', (integer) $protected);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->get('created_at');
    }

    /**
     * @param $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->set('created_at', $created_at);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->get('views');
    }

    /**
     * @param $views
     * @return $this
     */
    public function setViews($views)
    {
        $this->set('views', $views);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDownloads()
    {
        return $this->get('views');
    }

    /**
     * @param $downloads
     * @return $this
     */
    public function setDownloads($downloads)
    {
        $this->set('downloads', $downloads);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->get('status');
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->set('status', $status);

        return $this;
    }

    /**
     * @return array
     */
    public function toResponse()
    {
        return [
            'name' => $this->getName(),
            'mime' => $this->getMimeType(),
            'size' => Uploader::humanizeSize($this->getSize()),
            'md5_file' => $this->getMd5File(),
            'is_protected' => $this->isProtected(),
            'category' => $this->category()->toResponse(),
            'status' => $this->getStatus(),
            'created' => [
                'timestamp' => $this->getCreatedAt(),
                'formatted' => date('d F, Y H:i:s', $this->getCreatedAt()),
            ]
        ];
    }

}