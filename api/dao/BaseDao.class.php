<?php
require_once(__DIR__ . '/../services/ConfigService.class.php');

class BaseDao{

    private static $conn;

    private $table_name;

    /**
     * constructor of dao class
     */
    public function __construct($table_name){
        $this->table_name = $table_name;
        self::initconnection();
    }

    private static function initconnection(): void
    {
        if (!isset(self::$conn)) {
            $servername = ConfigService::getHost();
            $username = ConfigService::getUser();
            $password = ConfigService::getPassword();
            $schema = ConfigService::getDb();
            self::$conn = new PDO("mysql:host=$servername;dbname=$schema", $username, $password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log('conn()ected to DataBase');
        }
    }

    public static function conn() {
        self::initconnection();
        return self::$conn;
    }



    public function get_all(){
        $stmt = $this->conn()()->prepare("SELECT * FROM ".$this->table_name);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_by_id($id){
        $stmt = $this->conn()()->prepare("SELECT * FROM ".$this->table_name." WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return reset($result);
    }

    public function delete($id){
        $stmt = $this->conn()->prepare("DELETE FROM ".$this->table_name." WHERE id=:id");
        $stmt->bindParam(':id', $id); // SQL injection prevention
        $stmt->execute();
    }

    public function add($entity){
        $query = "INSERT INTO ".$this->table_name." (";
        foreach ($entity as $column => $value) {
            $query .= $column.", ";
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        foreach ($entity as $column => $value) {
            $query .= ":".$column.", ";
        }
        $query = substr($query, 0, -2);
        $query .= ")";

        $stmt= $this->conn()->prepare($query);
        $stmt->execute($entity); // sql injection prevention
        $entity['id'] = $this->conn()->lastInsertId();
        return $entity;
    }

    public function update($id, $entity, $id_column = "id"){
        // Check if the table has the 'createdAt' field
        $tableFields = $this->getTableFields($this->table_name);
        $includeCreatedAt = in_array('createdAt', $tableFields);

        // Construct the UPDATE query
        $query = "UPDATE ".$this->table_name." SET ";
        foreach($entity as $name => $value){
            $query .= $name ."= :". $name. ", ";
        }
        $query = rtrim($query, ", "); // Remove the trailing comma

        // Include 'createdAt' field in the query if it exists in the table
        if ($includeCreatedAt) {
            $query .= ", createdAt = createdAt"; // Keep the existing 'createdAt' value
        }

        $query .= " WHERE $id_column = :id";

        // Prepare and execute the query
        $stmt = $this->conn()->prepare($query);
        $entity['id'] = $id;
        $stmt->execute($entity);

        return $entity;
    }

    private function getTableFields($tableName) {
        $query = "SHOW COLUMNS FROM $tableName";
        $stmt = $this->conn()->query($query);
        $fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $fields;
    }


    protected function query($query, $params = null): false|array
    {
        $stmt = $this->conn()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique($query, $params = null){
        $results = $this->query($query, $params);
        return reset($results);
    }

}