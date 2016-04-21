<?php

namespace FileStorage\Models;

use Dez\ORM\Model\Table;

class Categories extends Table {

    protected static $table = 'stored_file_categories';

    /**
     * @return Files[]
     * @throws \Dez\ORM\Exception
     */
    public function files()
    {
        return $this->hasMany(Files::class, 'id', 'category_id');
    }

    /**
     * @return void
     */
    public function beforeSave()
    {
        $this->setSlug(\URLify::filter($this->getName()));
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
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->set('name', $name);

        return $this;
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

}