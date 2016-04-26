<?php

namespace FileStorage\Models;

use Dez\ORM\Model\QueryBuilder;
use Dez\ORM\Model\Table;

class Files extends Table {
    
    protected static $table = 'stored_files';

    /**
     * @param int $limit
     * @return QueryBuilder
     */
    public static function latest($limit = 100)
    {
        return static::query()->order('created_at', 'desc')->limit($limit);
    }

    /**
     * @return Categories
     * @throws \Dez\ORM\Exception
     */
    public function category()
    {
        return $this->hasOne(Categories::class, 'category_id');
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
    public function getSize()
    {
        return $this->get('size');
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
    
}