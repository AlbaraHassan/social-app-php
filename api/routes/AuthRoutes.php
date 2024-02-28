<?php

use Firebase\JWT\JWT;


Flight::route('POST /auth/login', function () {
    $request_body = Flight::request()->getBody();
    $json_data = json_decode($request_body, true);
    $userDao = new UserDao();
    $user = $userDao->getByEmail($json_data['email']);
    if(!$user){
        Flight::halt(404, json_encode(['message'=>'User Does Not Exist']));
    }
    if ($user['email'] == $json_data['email'] && hash('sha256', $json_data['password']) == $user['password']) {
        $jwtPayload = ['email' => $user['email'], 'name' => $user['username']];
        $jwt = JWT::encode($jwtPayload, ConfigService::getJwtSecrete(), 'HS256');
        Flight::json(['token' => $jwt, 'name' => $user['username']]);
    } else {
        Flight::halt(401, json_encode(['message' => 'Incorrect Credentials']));
    }
});