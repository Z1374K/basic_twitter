<?php

class PrototypeConnection {

    private $ip = '127.0.0.1';
    private $user = 'root';
    private $password = 'coderslab';
    private $database = '';

    /** @var mysqli|null  */
    protected $conn = null;

    public function __construct($database = '') {
        $this->conn = mysqli_connect($this->ip, $this->user, $this->password, $this->database = $database);
        if ($this->conn->connect_error) {
            echo $this->conn->connect_error;
        }
    }

    function __destruct() {
        $this->getConn()->close();
    }

    public function getConn() {
        return $this->conn;
    }

    protected function boolQuery($sql) {
        if (!$this->conn->query($sql)) {
            echo $this->conn->error;
            return false;
        }
        return true;
    }

    function getIp() {
        return $this->ip;
    }

    function getUser() {
        return $this->user;
    }

    function getPassword() {
        return $this->password;
    }

    function getDatabase() {
        return $this->database;
    }

    public function createDatabase($database = 'test') {
        $dbSelected = mysqli_select_db($this->conn, $database);
        if (!$dbSelected) {
            if (!$this->conn->query('CREATE DATABASE ' . $database)) {
                echo $this->conn->error;
                return false;
            } else {
                mysqli_select_db($this->conn, $database);
            }
        }
        return true;
    }

    public function createTable($tableName) {
        if (!$this->conn->query('DESCRIBE ' . $tableName)) {
            $sql = "CREATE TABLE $tableName (id int AUTO_INCREMENT, PRIMARY KEY(id))";
            return $this->boolQuery($sql);
        }
        return true;
    }

    public function deleteFromTable($tableName) {
        $sql = "DELETE FROM " . $tableName;
        return $this->boolQuery($sql);
    }

    public function addColumn($tableName, array $args) {
        $sql = "ALTER TABLE $tableName ADD " . implode(' ', $args);
        return $this->boolQuery($sql);
    }

    public function modifyColumn($tableName, array $args) {
        $sql = "ALTER TABLE $tableName MODIFY COLUMN " . implode(' ', $args);
        return $this->boolQuery($sql);
    }

    public function deleteColumn($tableName, $args) {
        $sql = "ALTER TABLE $tableName DROP COLUMN $args";
        return $this->boolQuery($sql);
    }

    public function deleteTable($name = 'test') {
        $sql = "DROP TABLE " . $name;
        return $this->boolQuery($sql);
    }

    public function deleteDatabase($name = 'test') {
        $sql = "DROP DATABASE " . $name;
        return $this->boolQuery($sql);
    }

}

class UserTable extends PrototypeConnection {

    public function addUser($userName, $userEmail) {
        $sql = "INSERT INTO users (user_name, user_email) VALUES ('$userName', '$userEmail')";
        return $this->boolQuery($sql);
    }

    public function printUsers() {
        $sql = "SELECT * FROM users";
        if ($result = $this->getConn()->query($sql)) {
            foreach ($result as $row) {
                var_dump($row);
            }
        } else {
            echo $this->getConn()->error;
            return false;
        }
        return true;
    }

    public function printOrderedByUsers() {
        $sql = "SELECT * FROM users ORDER BY user_name ASC";
        if ($result = $this->getConn()->query($sql)) {
            foreach ($result as $row) {
                var_dump($row);
            }
        } else {
            echo $this->getConn()->error;
            return false;
        }
        return true;
    }

    public function updateAllUsers() {
        $sql = "UPDATE users SET user_name = " . rand(1000, 9999);
        return $this->boolQuery($sql);
    }

    public function updatePerUsers() {
        $sql = "SELECT * FROM users";
        if ($result = $this->getConn()->query($sql)) {
            foreach ($result as $row) {
                $sql = "UPDATE users SET user_name = " . rand(1000, 9999) . " WHERE id = " . $row['id'];
                $this->boolQuery($sql);
            }
        } else {
            echo $this->getConn()->error;
            return false;
        }
        return true;
    }

}
