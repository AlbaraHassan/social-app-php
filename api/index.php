<?php

require '../vendor/autoload.php';

use Firebase\JWT\JWT;

Flight::route('POST /login', function () {
    $request_body = Flight::request()->getBody();
    $json_data = json_decode($request_body, true);

    if ($json_data['email'] == 'albara.m.hassan@gmail.com' && hash('sha256', $json_data['password']) == '37268335dd6931045bdcdf92623ff819a64244b53d0e746d438797349d4da578') {
        $jwtPayload = ['email' => $json_data['email'],'name' => 'Albara'];

        $jwt = JWT::encode($jwtPayload, 's3crts3crt', 'HS256');

        Flight::json(['token' => $jwt, 'name' => 'Albara']);
    } else {
        Flight::halt(401, json_encode(['message' => 'Incorrect Credentials']));
    }
});

Flight::start();
