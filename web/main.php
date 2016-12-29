<?php
require_once '../src/entry.php';
$connection = new PrototypeConnection('warsztaty');
User::$conn = $connection->getConn();
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '../session'));
session_start();
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/style.css"/>
        <title>Entritter</title>
    </head>
    <body>
        <form method="post" action="#">
            <input type="text" name="entry" value="" placeholder="Type your thoughts here"><br/>
            <button type="submit" name="submit" value="entry_submit">Done</button>
        </form>
        <a href="logout.php">wyloguj</a>
        <?php
        //przyjmuję dane z formularza dodające nowy wpis zalogowanemu użytkownikowi
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['entry']) && !empty($_POST['entry'])) {
                $tweet = new Entry();
                $tweet->setText($_POST['entry']);
                $tweet->setUserId($_SESSION['id']);
                $tweet->setCreationDate();
                $tweet->saveToDb();
                header('location:main.php');
            }
        }
        //zbieram wszystkie wpisy zalogowanego użytkownika
        Entry::loadTweetByUserId($_SESSION['id']);
        var_dump($_SESSION['id'])
        ?>      
    </body>
</html>
