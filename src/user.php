<?php

require_once '../src/PrototypeConnection.php';

class User {

    private $id;
    private $username;
    private $hashedPassword;
    private $email;

    /* @var mysqli|null     */
    public static $conn = null;

    public function __construct() {
        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashedPassword = "";
    }

    public function __destruct() {
        self::$conn = null;
    }
    
    public function setPassword($newPassword) {
        $this->hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function saveToDb() {
        if (self::$conn != null) {
            if ($this->id == -1) {
                $sql = "INSERT INTO Users (username, email, hashed_password) values ('$this->username', '$this->email', '$this->hashedPassword')";

                $result = self::$conn->query($sql);

                if ($result == true) {
                    $this->id = self::$conn->insert_id;
                    return true;
                } else {
                    echo self::$conn->error;
                }
            } else {
                $sql = "UPDATE Users set username = '$this->username', email = '$this->email', hashed_password = '$this->hashedPassword' where id = $this->id";

                $result = self::$conn->query($sql);

                if ($result == true) {
                    return true;
                }
            }
        } else {
            echo "Brak polaczenia<br>";
        }
        return false;
    }

    static public function loadUserById($id) {
        $sql = "SELECT * FROM Users WHERE id=$id";
        $result = self::$conn->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        
        return null;
    }

    static public function loadAllUsers() {
        $sql = "SELECT * FROM Users";
        $returnTable = [];
        if ($result = self::$conn->query($sql)) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->email = $row['email'];
                $returnTable[] = $loadedUser;
            }
        }
        return $returnTable;
    }

    public function delete() {
        if ($this->id != -1) {
            if (self::$conn->query("DELETE FROM Users WHERE id=$this->id")) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

}
/*
$connection = new PrototypeConnection('warsztaty');
User::$conn = $connection->getConn();


  $usr1 = new User();
  $usr1->setUsername('Maryoosh');
  $usr1->setEmail('maryoosh@example.com');
  $usr1->setPassword('qwe123');
  $usr1->saveToDb();

  var_dump(User::loadAllUsers());
 

$usr1 = User::loadUserById(8);
$usr1->setUsername('K.Roll');
$usr1->saveToDb();
$usr1->delete();
 * 
 */