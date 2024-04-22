<?php
require_once(__DIR__ . '/../middleware/Auth.class.php');

Flight::group('/chat', function () {

}, [new Auth()]);