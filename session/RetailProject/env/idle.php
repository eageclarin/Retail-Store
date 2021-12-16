<?php
    include_once './connection.php';
    include_once './adminAuth.php';
    session_destroy();
    unset($_SESSION);
    header('location: ../main.php');
?>