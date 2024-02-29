<?php
require_once(__DIR__.'/../middleware/Auth.class.php');

Flight::group('/post', function (){

    Flight::route('GET /', function (){
        echo 'NOOO';
    });

},[new Auth()]);