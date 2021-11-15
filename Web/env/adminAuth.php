<?php 
if (!empty($_SESSION['admin'])) {

} else {
    
    header("Location: ../main.php"); 
}


if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION);
    header('location: ../main.php');
}
?>