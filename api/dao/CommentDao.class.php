<?php
require_once(__DIR__ . '/BaseDao.class.php');
require_once(__DIR__ . '/../context/UserContext.php');

class CommentDao extends BaseDao
{

    private $likes;

    public function __construct()
    {
        parent::__construct('content');
        $this->likes = new BaseDao('`like`');
    }


    public function get_by_id($id)
    {
        return Prisma::sql()->select('c.id', 'c.content',
            alias('u.username', 'createdBy'),
            alias('u.id', 'createdById'),
            Prisma::sql()->select(CNT())
                ->from('`like`')
                ->where(equals('contentId', 'c.id'))
                ->nested()
                ->alias('likes'))
            ->from(alias('content', 'c'))
            ->join(alias('user', 'u'), equals('u.id', 'c.createdBy'))
            ->where(equals('c.type', ':type'), equals('c.id', ':id'))
            ->bind(['id' => $id, 'type' => "comment"])
            ->execute_unique();
    }


    public function create($data)
    {
        $data = $this->add([...$data, "type" => "comment"]);
        return Prisma::sql()->select('c.id', 'c.content',
            alias('u.username', 'createdBy'),
            alias('u.id', 'createdById'))
            ->from(alias('content', 'c'))
            ->join(alias('user', 'u'), equals('u.id', 'c.createdBy'))
            ->where(equals('c.id', ':id'), equals('c.type', '"comment"'))
            ->order('c.createdAt', DESC)
            ->bind(['id' => $data['id']])
            ->execute_unique();
    }

    public function get_all_by_post(string $postId, int $page = 1, int $limit = 10): array|false
    {
        $offset = ($page - 1) * $limit;
        return Prisma::sql()->select('c.id', 'c.content',
            alias('u.username', 'createdBy'),
            alias('u.id', 'createdById'),
            Prisma::sql()->select(CNT())
                ->from('`like`')
                ->where(equals('contentId', 'c.id'))
                ->nested()
                ->alias('likes'))
            ->from(alias('content', 'c'))
            ->join(alias('user', 'u'),equals('u.id', 'c.createdBy'))
            ->where(equals('c.type', ':type'), equals('c.parentId', ':postId'))
            ->order('c.createdAt', DESC)
//             ->limit($limit)
//            ->offset($offset)
            ->bind(["type" => "comment", "postId" => $postId])->execute();
    }


    private function checkIfLiked($object)
    {
        $where = Prisma::sql()->where(
            equals('l.contentId', ':contentId'),
            equals('l.userId', ':userId'));
        $isLiked = Prisma::sql()->select(alias(CNT(), 'isLiked'))
            ->from(alias('`like`', 'l'))
            ->append($where)
            ->bind($object)
            ->execute_unique()['isLiked'];
        return !!$isLiked ? Prisma::sql()->select(alias('l.id', 'id'))
            ->from('`like` l')->
            append($where)->
            bind($object)
            ->execute_unique()['id']
            : !!$isLiked;
    }

    public function handleLike($id)
    {
        $object = ['userId' => User::id(), 'contentId' => $id];
        if ($id = $this->checkIfLiked($object)) {
            $this->likes->delete($id);
            return false;
        }
        return !!$this->likes->add($object);

    }
}
