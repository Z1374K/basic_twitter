<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '../session'));
session_start();
if(isset($_SESSION['login'])){
    unset($_SESSION['login']);
    echo "wylogowano pomyślnie";
    header('location:login.php');
}

