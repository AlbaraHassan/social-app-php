<?php
require_once(__DIR__ . '/BaseService.class.php');
require_once(__DIR__ . '/../dao/ChatDao.class.php');

class ChatService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new ChatDao());
    }
}