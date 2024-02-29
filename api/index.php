<?php

require_once('../vendor/autoload.php');
require_once('./services/ConfigService.class.php');
require_once('./dao/UserDao.class.php');
require_once('./routes/AuthRoutes.php');
require_once('./routes/PostRoutes.php');
require_once('./services/AuthService.class.php');
$SetupDataBase = require_once('./services/SetupDataBase.class.php');
$SetupDataBase->createUserTable();
$SetupDataBase->createPostTable();

Flight::register('authService', 'AuthService');

Flight::start();
