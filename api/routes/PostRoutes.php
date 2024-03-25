<?php
require_once(__DIR__ . '/../middleware/Auth.class.php');

Flight::group('/post', function () {

    Flight::route('POST /', function () {
        $data = json_decode(Flight::request()->getBody(), true);
        $data['createdBy'] = Flight::get('user')['id'];
        return Flight::postService()->create($data);
    });

    Flight::route('PATCH /', function (){
        $data = json_decode(Flight::request()->getBody(), true);
        $id = Flight::request()->query['id'];
        return Flight::postService()->update($id,['content'=> $data['content']]);
    });

    Flight::route('GET /', function (){
        $id = Flight::request()->query['id'];
        return Flight::postService()->get($id);
    });

    Flight::route('GET /all', function (){
        return Flight::postService()->get_all();
    });

    Flight::route('DELETE /', function (){
        $id = Flight::request()->query['id'];
        return Flight::postService()->delete($id);
    });

}, [new Auth()]);