<?php
require_once (__DIR__.'/BaseDao.php');
class UserDao extends BaseDao{
    public function __construct()
    {
     parent::__construct('user');
    }

    public function getByEmail($email)
    {
        return $this->query_unique("Select * from user where email = '$email'");
    }

}