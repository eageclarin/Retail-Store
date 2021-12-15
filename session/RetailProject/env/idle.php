<?php
    include_once './adminAuth.php';

    session_destroy();
    unset($_SESSION);
    header('location: http://localhost/CMSC_P3/session/RetailProject/main.php');
?>