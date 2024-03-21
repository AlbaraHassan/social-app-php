<?php
require_once(__DIR__ . '/BaseDao.class.php');
class PostDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('post');
    }


    public function create($data)
    {
        return $this->add($data);
    }
}
