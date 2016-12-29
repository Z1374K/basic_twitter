<?php

require_once 'user.php';

class Comment extends User {

    private $id;
    private $userId;
    private $entryId;
    private $creationDate;
    private $text;

    public function __construct() {
        $this->id = -1;
        $this->userId = 0;
        $this->entryId = 0;
        $this->creationDate = 0;
        $this->text = "";
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setEntryId($entryId) {
        $this->entryId = $entryId;
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

    function getEntryId() {
        return $this->entryId;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function getText() {
        return $this->text;
    }

    static public function loadCommentById($id) {
        $sql = "SELECT * FROM Comments WHERE id=$id ORDER BY creation_date DESC";
        $result = self::$conn->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['user_id'];
            $loadedComment->entryId = $row['entry_id'];
            $loadedComment->text = $row['comment'];
            $loadedComment->creationDate = $row['creation_date'];
            return $loadedComment;
        }

        return null;
    }

    static public function loadAllCommentsByEntryId($entryId) {
        $sql = "SELECT * FROM Comments WHERE entry_id=$entryId ORDER BY creation_date DESC";
        $returnTable = [];
        if ($result = self::$conn->query($sql)) {
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['user_id'];
                $loadedComment->entryId = $row['entry_id'];
                $loadedComment->text = $row['comment'];
                $loadedComment->creationDate = $row['creation_date'];
                $returnTable[] = $loadedComment;
                echo "<div>" . $row['comment'] . "<hr>" . "<p class='dates'>" . $row['creation_date'] . "</p></div>";
            }
        }
        return $returnTable;
    }

    public function saveToDb() {
        if (self::$conn != null) {
            if ($this->id == -1) {
                $sql = "INSERT INTO Comments (user_id, entry_id, comment, creation_date) VALUES ('$this->userId', '$this->entryId', '$this->text', '$this->creationDate')";
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

}
/*
$connect = new PrototypeConnection('warsztaty');
User::$conn = $connect->getConn();
$komentarz = new Comment();
$komentarz->setText("test nr2");
$komentarz->setUserId(2);
$komentarz->setEntryId(27);
$komentarz->setCreationDate();
$komentarz->saveToDb();
*/