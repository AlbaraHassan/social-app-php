<?php
require_once(__DIR__.'/../middleware/Auth.class.php');

Flight::group('/post', function (){

    Flight::route('GET /', function (){
        Flight::json(Flight::get('user'));
    });

},[new Auth()]);