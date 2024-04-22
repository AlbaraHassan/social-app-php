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
        $data = $this->add($data);
        return $this->query_unique('SELECT p.id, p.content, u.username as "createdBy", u.id as "createdById"
FROM post p
JOIN user u ON u.id = p.createdBy
    WHERE p.id = ' . $data['id'] . '
ORDER BY p.createdAt DESC');
    }

    public function get_all(int $page = 1, int $limit = 10): bool|array
    {
        $offset = ($page - 1) * $limit;

        $sql = 'SELECT p.id, p.content, u.username as "createdBy", u.id as "createdById"
            FROM post p
            JOIN user u ON u.id = p.createdBy
            ORDER BY p.createdAt DESC';

//        $sql .= " LIMIT $limit OFFSET $offset";  //TODO: PAGINATION

        return $this->query($sql);
    }
}
