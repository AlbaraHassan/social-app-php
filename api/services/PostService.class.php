<?php
require_once(__DIR__ . '/BaseService.class.php');
require_once(__DIR__ . '/../dao/PostDao.class.php');

class PostService extends BaseService
{

    public function __construct()
    {
        parent::__construct(new PostDao());
    }

    public function create($data)
    {
        return Flight::json($this->dao->create($data));
    }

}