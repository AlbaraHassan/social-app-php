<?php

require_once(__DIR__ . '/../dao/UserDao.class.php');
require_once(__DIR__ . '/../services/BaseService.class.php');

use Firebase\JWT\JWT;

class AuthService extends BaseService
{

    public function __construct()
    {
        parent::__construct(new UserDao());
    }

    public function findByEmail($email)
    {
        return $this->dao->getByEmail($email);

    }

    public function login($data)
    {
        $user = $this->findByEmail($data['email']);
        if (!$user) {
            return Flight::halt(404, json_encode(['message' => 'User Does Not Exist']));
        }
        if ($user['email'] == $data['email'] && hash('sha256', $data['password']) == $user['password']) {
            $jwtPayload = ['email' => $user['email'], 'name' => $user['username']];
            $jwt = JWT::encode($jwtPayload, ConfigService::getJwtSecret(), 'HS256');
            return Flight::json(['token' => $jwt, 'name' => $user['username']]);
        } else {
            return Flight::halt(401, json_encode(['message' => 'Incorrect Credentials']));
        }

    }

    public function register($data)
    {
        if ($this->dao->getByEmail($data['email'])) {
            return Flight::halt(404, json_encode(['message' => 'User Already Exists']));
        }

        $user = $this->dao->create(['email' => $data['email'], 'password' => hash('sha256', $data['password']), 'username' => $data['username']]);
        $jwtPayload = ['email' => $user['email'], 'name' => $user['username']];
        $jwt = JWT::encode($jwtPayload, ConfigService::getJwtSecrete(), 'HS256');
        return Flight::json(['token' => $jwt, 'name' => $user['username']]);
    }
}