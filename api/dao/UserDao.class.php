<?php
require_once (__DIR__ . '/BaseDao.class.php');
class UserDao extends BaseDao{
    public function __construct()
    {
     parent::__construct('user');
    }

    public function getByEmail($email)
    {
        return Prisma::sql()->select('*')
            ->from('user')
            ->where(equals('email', ':email'))
            ->bind(['email'=>$email])
            ->execute_unique();
    }

    public function create($data)
    {
        return $this->add($data);
    }

}