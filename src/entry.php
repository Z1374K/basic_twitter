<?php

require_once 'user.php';
require 'Comment.php';

class Entry extends User {

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->userId = 0;
        $this->text = '';
        $this->creationDate = 0;
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function saveToDb() {
        if (self::$conn != null) {
            if ($this->id == -1) {
                $sql = "INSERT INTO Entries (user_id, entry, creation_date) values ('$this->userId', '$this->text', '$this->creationDate')";
                $result = self::$conn->query($sql);
                if ($result == true) {
                    $this->id = self::$conn->insert_id; // $this->id możnaby zamienić na $this->userId, gdyby coś nie hulało tak jak trza.
                    return true;
                } else {
                    echo self::$conn->error;
                }
            }
        } else {
            echo "Brak polaczenia<br>";
        }
        return false;
    }

    static public function loadTweetById($id) {
        $sql = "SELECT * FROM Entries WHERE id=$id ORDER BY creation_date DESC";
        $result = self::$conn->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedTweet = new Entry();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['user_id'];
            $loadedTweet->text = $row['entry'];
            $loadedTweet->creationDate = $row['creation_date'];
            return $loadedTweet;
        }

        return null;
    }

    static public function loadTweetByUserId($userId) {
        $sql = "SELECT id, entry, creation_date FROM Entries WHERE user_id= $userId ORDER BY creation_date DESC";
        $result = self::$conn->query($sql);
        if ($result->num_rows > 1) {
            foreach ($result as $row) {
                echo "<div><span>{$row['entry']}</span><br/><hr><p class='dates'>{$row['creation_date']}</p>";
                $connect = new PrototypeConnection('warsztaty');
                User::$conn = $connect->getConn();
                Comment::loadAllCommentsByEntryId($row['id']);
                echo "</div>";
            }
        }

        return null;
    }

    static public function loadAllTweets() {
        $sql = "SELECT * FROM Entries ORDER BY creation_date DESC";
        $returnTable = [];
        if ($result = self::$conn->query($sql)) {
            foreach ($result as $row) {
                $loadedTweet = new Entry();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['entry'];
                $loadedTweet->creationDate = $row['creation_date'];
                $returnTable[] = $loadedTweet;
            }
        }
        return $returnTable;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setCreationDate() {
        $this->creationDate = date('Y-m-d', time());
    }

    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }

    function getText() {
        return $this->text;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

}

//$connect = new PrototypeConnection('warsztaty');
//User::$conn = $connect->getConn();
//$wpis = new Entry();
//$wpis->setText("I am an Id 2 user again");
//$wpis->setUserId(2);
//$wpis->setCreationDate();
//$wpis->saveToDb();
//var_dump($wpis->loadAllTweets());