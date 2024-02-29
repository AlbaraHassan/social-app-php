<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Auth
{
    public function before($params)
    {
        $token = substr(Flight::request()->getHeader('Authorization'), 7);
        if(empty($token)){
            return Flight::halt(401, json_encode(['message'=>'Missing Token']));
        }
        $user = (array)JWT::decode($token, new Key(ConfigService::getJwtSecret(), 'HS256'));
        if(Flight::authService()->findByEmail($user['email'])){
            return true;
        }
        else{
            return Flight::halt(401, json_encode(['message'=>'Invalid Token']));
        }
    }
}