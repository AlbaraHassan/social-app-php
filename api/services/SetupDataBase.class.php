<?php

require_once(__DIR__ . '/../dao/BaseDao.class.php');
require_once(__DIR__ . '/../enum/SQLTypes.enum.php');

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
        $this->createTableIfNotExists("user", [
            "username" => [VARCHAR(255), NOTNULL],
            "email" => [VARCHAR(255), NOTNULL],
            "password" => [VARCHAR(255), NOTNULL]
        ]);
    }

    private function createPostTable(): void
    {
        $this->createTableIfNotExists("post", [
            "content" => [VARCHAR(255), NOTNULL],
            "createdAt" => [TIMESTAMP, DCT],
            "createdBy" => [INT]
        ], [
            "createdBy" => "user"
        ]);
    }

    private function createCommentTable(): void
    {
        $this->createTableIfNotExists("comment", [
            "content" => [VARCHAR(255), NOTNULL],
            "postId" => [INT],
            "createdAt" => [TIMESTAMP, DCT],
            "createdBy" => [INT]
        ], [
            "createdBy" => "user",
            "postId" => "post"
        ]);
    }

    private function createLikesTable(): void
    {
        $this->createTableIfNotExists("`like`", [
            "userId" => [INT, NOTNULL],
            "contentId" => [INT],
            "createdAt" => [TIMESTAMP, DCT]
        ], [
            "userId" => "user",
            "contentId" => "content",
        ]);
    }

    private function createChatsTable(): void
    {
        $this->createTableIfNotExists("chat", [
            "senderId" => [INT, NOTNULL],
            "userId" => [INT, NOTNULL]
        ], [
            "senderId" => "user",
            "userId" => "user"
        ]);
    }

    private function createMessageTable(): void
    {
        $this->createTableIfNotExists("message", [
            "senderId" => [INT, NOTNULL],
            "chatId" => [INT, NOTNULL],
            "content" => [VARCHAR(512)]
        ], [
            "senderId" => "user",
            "chatId" => "chat"
        ]);
    }

    private function createContentTable(): void
    {
        $this->createTableIfNotExists("content", [
            "type" => [VARCHAR(50), NOTNULL],
            "content" => [TEXT],
            "parentId" => [INT],
            "createdAt" => [TIMESTAMP, DCT],
            "createdBy" => [INT, NOTNULL],
        ], [
            "parentId" => "content",
            "createdBy" => "user"
        ], ["parentId" => "CASCADE"]);
    }

    private function createTableIfNotExists($tableName, $attributes = [], $relations = [], $options = []): void
    {
        $query = "CREATE TABLE IF NOT EXISTS $tableName (";

        $query .= "id INT AUTO_INCREMENT PRIMARY KEY,";

        foreach ($attributes as $attribute => $properties) {
            $type = strtoupper($properties[0]);
            $query .= "$attribute $type";
            for ($i = 1; $i < count($properties); $i++) {
                if (strtoupper($properties[$i]) === 'NOT NULL') {
                    $query .= " NOT NULL";
                }
            }

            $query .= ", ";
        }
        $query = rtrim($query, ", ");
        foreach ($relations as $localKey => $foreignTable) {
            $query .= ", FOREIGN KEY ($localKey) REFERENCES $foreignTable(id)";
            if (isset($options[$localKey]) && $options[$localKey] === "CASCADE") {
                $query .= " ON DELETE CASCADE";
            }
        }
        $query .= ")";

        error_log($query);

        try {
            $this->query($query);
            error_log("$tableName table created successfully!");
        } catch (PDOException $e) {
            error_log("Error creating $tableName table: " . $e->getMessage());
        }
    }
    public function createTables(): void
    {
        $this->createUserTable();
        $this->createChatsTable();
        $this->createMessageTable();
        $this->createContentTable();
        $this->createLikesTable();
    }
}

return SetupDatabase::getInstance();
