<?php 
if (!isset($_SESSION['admin'])) {
    header("Location: ../main.php"); 
}


if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION);
    
    header('Location: ../login.php');
}

?>

