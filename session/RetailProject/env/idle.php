<?php
    include_once './connection.php';
    include_once './adminAuth.php';
    session_destroy();
    unset($_SESSION);
    header('location: http://localhost/CMSC-127/session/RetailProject/main.php');
?>