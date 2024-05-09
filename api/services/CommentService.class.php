<?php
require_once(__DIR__ . '/BaseService.class.php');
require_once(__DIR__ . '/../dao/CommentDao.class.php');

class CommentService extends BaseService
{

    public function __construct()
    {
        parent::__construct(new CommentDao());
    }

    public function create($data)
    {
        return Flight::json($this->dao->create($data));
    }

    public function get($id)
    {
        return Flight::json($this->dao->get_by_id($id));
    }

    public function get_all_by_post(string $postId, int $page = 1, int $limit = 10)
    {
        return Flight::json($this->dao->get_all_by_post($postId,$page, $limit));
    }

    public function delete($id)
    {
        return Flight::json(parent::delete($id));
    }

    public function update($id, $entity)
    {
        return Flight::json(parent::update($id, $entity));
    }

    public function handleLike($id)
    {
        return Flight::json($this->dao->handleLike($id));
    }
}