<?php

require_once('../vendor/autoload.php');
require_once('./services/ConfigService.class.php');
require_once('./dao/UserDao.class.php');
require_once('./routes/AuthRoutes.php');
require_once('./services/AuthService.class.php');
$setupDataBase = require_once('./services/SetupDataBase.class.php');
$setupDataBase->createUserTable();

Flight::register('authService', 'AuthService');

Flight::start();
