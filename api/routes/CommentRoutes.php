<?php
require_once(__DIR__ . '/../middleware/Auth.class.php');

Flight::group('/comment', function () {

    Flight::route('POST /', function () {
        $data = json_decode(Flight::request()->getBody(), true);
        $data['createdBy'] = Flight::get('user')['id'];
        return Flight::commentService()->create($data);
    });

    Flight::route('PATCH /', function (){
        $data = json_decode(Flight::request()->getBody(), true);
        $id = Flight::request()->query['id'];
        return Flight::commentService()->update($id,['content'=> $data['content']]);
    });

    Flight::route('GET /', function (){
        $id = Flight::request()->query['id'];
        return Flight::commentService()->get($id);
    });

    Flight::route('GET /all', function (){

        $params = [];
        if (isset(Flight::request()->query['page'])) {
            $params['page'] = Flight::request()->query['page'];
        }

        if (isset(Flight::request()->query['size'])) {
            $params['limit'] = Flight::request()->query['size'];
        }
        return Flight::commentService()->get_all(...$params);

    });

    Flight::route('DELETE /', function (){
        $id = Flight::request()->query['id'];
        return Flight::commentService()->delete($id);
    });

    Flight::route('PATCH /like', function (){
        $id = Flight::request()->query['id'];
        return Flight::commentService()->handleLike($id);
    });

}, [new Auth()]);