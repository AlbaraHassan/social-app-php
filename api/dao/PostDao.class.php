<?php
require_once(__DIR__ . '/BaseDao.class.php');
require_once(__DIR__ . '/../context/UserContext.php');
require_once(__DIR__ . '/../utils/Prisma.php');

class PostDao extends BaseDao
{

    private $likes;

    public function __construct()
    {
        parent::__construct('content');
        $this->likes = new BaseDao('`like`');
    }


    public function create($data)
    {
        $data = $this->add([...$data, "type" => "post"]);
        return Prisma::sql()->select('p.id', 'p.content',
            alias('u.username', 'createdBy'),
            alias('u.id', "createdById"))
            ->from('content p')
            ->join('user u', 'u.id = p.createdBy')
            ->where('p.id = :id', 'AND', 'p.type = "post"')
            ->order('p.createdAt', 'DESC')
            ->bind(["id" => $data['id']])
            ->execute_unique();
    }

    public function get_by_id($id)
    {

        return Prisma::sql()->select('p.id', 'p.content',
            alias('u.username', 'createdById'),
            alias('u.id', 'createdById'),
            Prisma::sql()->select(CNT())
                ->from('`like`')
                ->where('contentId = p.id')
                ->nested()
                ->alias('likes'))
            ->from('content p')
            ->join('user u', 'u.id = p.createdBy')
            ->where('p.type = "post"', 'p.id = :id')
            ->bind(['id' => $id])
            ->execute_unique();
    }

    public function get_all(int $page = 1, int $limit = 10): bool|array
    {
//        $offset = ($page - 1) * $limit;
        return Prisma::sql()
            ->select(
                "p.id",
                "p.content",
                alias('u.username', 'createdBy'),
                alias('u.id', 'createdById'),
                Prisma::sql()->select(CNT())->from('`like`')->
                where(equals('contentId', 'p.id'))
                    ->nested()->alias('likes'),
                Prisma::sql()->select(CNT())->from('`like`')
                    ->where(equals('userId', ':userId'), equals('contentId', 'p.id'))
                    ->nested()->alias('isLiked')            )
            ->from("content p")
            ->join("user u", "u.id = p.createdBy")
            ->where("p.type = 'post'")
            ->order("p.createdAt", DESC)
//            ->limit($limit)
//            ->offset($offset)
            ->bind(['userId'=>User::id()])
            ->execute();
    }


    public function checkIfLiked($object)
    {
        $where = Prisma::sql()->where(
            equals('l.contentId', ':contentId'),
            equals('l.userId', ':userId'));
        $isLiked = Prisma::sql()->select(alias(CNT(), 'isLiked'))
            ->from('`like` l')
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
