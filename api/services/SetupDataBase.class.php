<?php

require_once(__DIR__ . '/../dao/BaseDao.class.php');

class SetupDatabase extends BaseDao
{

    private static SetupDatabase $instance;

    public function __construct()
    {
        parent::__construct("user");
    }

    public static function getInstance(): SetupDatabase
    {
        if (!isset(self::$instance)) {
            self::$instance = new SetupDatabase();
        }
        return self::$instance;
    }

    private function createUserTable(): void
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

    private function createPostTable(): void
    {
        $query = "CREATE TABLE IF NOT EXISTS post (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    content VARCHAR(255) NOT NULL,
                    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    createdBy INT,
                    FOREIGN KEY (createdBy) REFERENCES user(id)
                 )";
        try {
            $this->query($query);
            error_log("Post table created successfully!");
        } catch (PDOException $e) {
            error_log("Error creating user table: " . $e->getMessage());
        }
    }

    private function createCommentTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS comment (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    content VARCHAR(255) NOT NULL,
                    postId INT,
                    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    createdBy INT,
                    FOREIGN KEY (createdBy) REFERENCES user(id),
                    FOREIGN KEY (postId) REFERENCES post(id)
                 )";
        try {
            $this->query($query);
            error_log("Comment table created successfully!");
        } catch (PDOException $e) {
            error_log("Error creating user table: " . $e->getMessage());
        }
    }

    private function createLikesTable()
    {
        $query = "CREATE TABLE `like` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  userId INT NOT NULL,
  postId INT NULL,
  commentId INT NULL,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (userId) REFERENCES user(id),
  FOREIGN KEY (postId) REFERENCES post(id),
  FOREIGN KEY (commentId) REFERENCES comment(id)
)";
        try {
            $this->query($query);
            error_log("Likes table created successfully!");
        } catch (PDOException $e) {
            error_log("Error creating user table: " . $e->getMessage());
        }
    }

    public function createTables()
    {
        $this->createUserTable();
        $this->createPostTable();
        $this->createCommentTable();
        $this->createLikesTable();
    }
}

return SetupDatabase::getInstance();
