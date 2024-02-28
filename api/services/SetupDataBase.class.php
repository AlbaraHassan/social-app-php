<?php

require_once(__DIR__ . '/../dao/BaseDao.class.php');

class SetupDatabase extends BaseDao
{

    private static $instance;
    public function __construct(){
        parent::__construct("user");
    }
    public static function getInstance(){
        if (!isset(self::$instance)) {
            self::$instance = new SetupDatabase();
        }
        return self::$instance;    }
    public function createUserTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )";

        try {
            $this->query($query);
            error_log("User table created successfully!");
        } catch (PDOException $e) {
            error_log("Error creating user table: " . $e->getMessage());
        }
    }
}

return SetupDatabase::getInstance();
