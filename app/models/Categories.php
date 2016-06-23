<?php

namespace FileStorage\Models;

use Dez\ORM\Model\QueryBuilder;
use Dez\ORM\Model\Table;

class Categories extends Table {

    const STATUS_ACTIVE = 'active';

    const STATUS_DELETED = 'deleted';

    protected static $table = 'stored_file_categories';

    /**
     * @return Files[]
     * @throws \Dez\ORM\Exception
     */
    public function files()
    {
        return $this->hasMany(Files::class, 'category_id', 'id');
    }

    /**
     * @param int $user_id
     * @return QueryBuilder
     * @throws \Dez\ORM\Exception
     */
    public static function owned($user_id = 0)
    {
        return static::query()->where('user_id', $user_id);
    }

    /**
     * @return boolean|integer
     */
    public function deactivate()
    {
        $this->setStatus(static::STATUS_DELETED)->save();
    }

    /**
     * @return boolean|integer
     */
    public function activate()
    {
        $this->setStatus(static::STATUS_ACTIVE)->save();
    }

    /**
     * @return string
     */
    public function hash()
    {
        return md5($this->id());
    }

    /**
     * @return void
     */
    public function beforeSave()
    {
        $this->setSlug(\URLify::filter($this->getName()));
        $this->setCreatedAt(time());

        if($this->getStatus() === null) {
            $this->setStatus(static::STATUS_ACTIVE);
        }
    }

    /**
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->set('user_id', $user_id);

        return $this;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->set('slug', $slug);

        return $this;
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
     * @param null $status
     * @return $this
     */
    public function setStatus($status = null)
    {
        return $this->set('status', $status);
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
     * @return integer
     */
    public function getUserId()
    {
        return $this->get('user_id');
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->get('slug');
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->get('created_at');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->get('status');
    }

    /**
     * @return array
     */
    public function toResponse()
    {
        return [
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'created' => [
                'timestamp' => $this->getCreatedAt(),
                'formatted' => date('d F, Y H:i:s', $this->getCreatedAt()),
            ]
        ];
    }

}