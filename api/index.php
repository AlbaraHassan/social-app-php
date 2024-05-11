<?php

require_once('vendor/autoload.php');
require_once('./services/ConfigService.class.php');
require_once('./dao/UserDao.class.php');
require_once('./routes/AuthRoutes.php');
require_once('./routes/PostRoutes.php');
require_once('./routes/ChatRoutes.php');
require_once('./routes/CommentRoutes.php');
require_once('./services/AuthService.class.php');
require_once('./services/PostService.class.php');
require_once('./services/CommentService.class.php');
require_once('./services/ChatService.class.php');
$SetupDataBase = require_once('./services/SetupDataBase.class.php');
$SetupDataBase->createTables();

Flight::register('authService', 'AuthService');
Flight::register('postService', 'PostService');
Flight::register('commentService', 'CommentService');
Flight::register('chatService', 'ChatService');

Flight::start();
