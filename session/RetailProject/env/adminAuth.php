<?php 
if (!isset($_SESSION['admin'])) {
    header("Location: http://localhost/CMSC-127/session/RetailProject/login.php"); 
}


if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION);
    
    header('location: http://localhost/CMSC-127/session/RetailProject/main.php');
}

?>

