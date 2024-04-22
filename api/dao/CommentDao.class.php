<?php
require_once(__DIR__ . '/BaseDao.class.php');

class CommentDao extends BaseDao
{

    private $likes;

    public function __construct()
    {
        parent::__construct('comment');
        $this->likes = new BaseDao('`like`');
    }


    public function create($data)
    {
        $data = $this->add($data);
        return $this->query_unique('SELECT c.id, c.content, u.username as "createdBy", u.id as "createdById"
FROM comment c
JOIN user u ON u.id = c.createdBy
    WHERE c.id = ' . $data['id'] . '
ORDER BY c.createdAt DESC');
    }

    public function get_all(int $page = 1, int $limit = 10): bool|array
    {
        $offset = ($page - 1) * $limit;

        $sql = 'SELECT c.id, c.content, u.username as "createdBy", u.id as "createdById"
            FROM comment c
            JOIN user u ON u.id = c.createdBy
            ORDER BY c.createdAt DESC';

//        $sql .= " LIMIT $limit OFFSET $offset";  //TODO: PAGINATION

        return $this->query($sql);
    }

    public function getLikes($id)
    {
        $sql = 'SELECT COUNT(*) FROM `like` l WHERE l.commentId = $id';
        return $this->query_unique($sql);
    }

    private function checkIfLiked($object)
    {
        $where = 'WHERE l.commentId = :commentId && l.userId = :userId';
        $checkerSQL = 'SELECT COUNT(*) as isLiked FROM `like` l ' . $where;
        $isLiked = $this->likes->query_unique($checkerSQL, $object)['isLiked'];
        return !!$isLiked ? $this->likes->query_unique('SELECT l.id as id from `like` l '.$where, $object)['id']: !!$isLiked;
    }

    public function handleLike($id)
    {
        $object = ['userId' => Flight::get('user')['id'], 'commentId' => $id];
        if ($id = $this->checkIfLiked($object)) {
            $this->likes->delete($id);
            return false;
        }
        return !!$this->likes->add($object);

    }
}
