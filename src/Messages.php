<?php

require_once 'user.php';

class Message extends User {

    private $id;
    private $senderId;
    private $receiverId;
    private $status;
    private $creationDate;
    private $message;

    public function __construct() {
        $this->id = -1;
        $this->senderId = -1;
        $this->receiverId = -1;
        $this->status = -1;
        $this->creationDate = -1;
    }

    function setSenderId($senderId) {
        $this->senderId = $senderId;
    }

    function setReceiverId($receiverId) {
        $this->receiverId = $receiverId;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    function setMessage($message) {
        $this->message = $message;
    }

        
    function getId() {
        return $this->id;
    }

    function getSenderId() {
        return $this->senderId;
    }

    function getReceiverId() {
        return $this->receiverId;
    }

    function getStatus() {
        return $this->status;
    }

    function getCreationDate() {
        return $this->creationDate;
    }
    
    function getMessage() {
        return $this->message;
    }

        
        public function sendMessage() {
        if (self::$conn != null) {
            if ($this->id == -1) {
                $sql = "INSERT INTO Messages (sender_id, receiver_id, status, creation_date, message) VALUES ('$this->senderId', '$this->receiverId', '$this->status', '$this->creationDate', '$this->message')";
                $result = self::$conn->query($sql);
                if ($result == true) {
                    $this->id = self::$conn->insert_id;
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
    
    public function receiveMessageByReceiverId($receiverId) {
        $sql = "SELECT sender_id, status, creation_date, message FROM Messages WHERE receiver_id=$receiverId";
        $result = self::$conn->query($sql);
        if ($result == true) {
            //DOKOŃCZ
        }
    }

}
