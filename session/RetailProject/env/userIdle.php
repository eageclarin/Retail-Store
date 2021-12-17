<?php
    include_once './connection.php';
    if (!isset($_SESSION['username'])) {
        header("Location: ../main.php"); 
    }
    session_destroy();
    unset($_SESSION);
    header('location: ../login.php');
?>