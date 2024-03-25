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
        return Flight::json(parent::add($data));
    }

    public function get($id)
    {
        return Flight::json(parent::get_by_id($id));
    }

    public function get_all()
    {
        return Flight::json(parent::get_all());
    }

    public function delete($id)
    {
        return Flight::json(parent::delete($id));
    }

    public function update($id, $entity)
    {
        return Flight::json(parent::update($id, $entity));
    }
}