<?php

Flight::route('POST /auth/login', function () {
    $data = json_decode(Flight::request()->getBody(),true);
    return Flight::authService()->login($data);
});

Flight::route('POST /auth/signup', function () {
    $data = json_decode(Flight::request()->getBody(),true);
    return Flight::authService()->register($data);
});