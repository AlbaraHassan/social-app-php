<?php
require_once(__DIR__ . '/../middleware/Auth.class.php');

Flight::group('/post', function () {

    Flight::route('POST /', function () {
        $data = json_decode(Flight::request()->getBody(), true);
        $data['createdBy'] = Flight::get('user')['id'];
        return Flight::postService()->create($data);
    });

}, [new Auth()]);