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

    public function get_all(): bool|array
    {
        return $this->query('SELECT p.id, p.content, u.username as "createdBy", u.id as "createdById"
FROM post p
JOIN user u ON u.id = p.createdBy
ORDER BY p.createdAt DESC;
');
    }
}
