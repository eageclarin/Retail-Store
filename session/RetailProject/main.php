<?php
    include_once 'env/connection.php';
?>

<!DOCTYPE html>
<html>
<body>
    this is the main page..

    
    <?php 
    if (!empty($_SESSION['CustomerFName'])) { //Checks if customer is logged in
        echo $_SESSION['CustomerFName'];
        echo " <form action='main.php' method='post' class='form-inline'>   
        <input type='submit' value='Logout' name='logout' class='form-control' style='width:150px'>
        </form>";
    } else {
        echo '<a href="login.php">Log In</a>';
        echo '<a href="client/register.php">Register</a>';
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location: main.php');
    }
    
    
    ?>   
    <!--Insert Items here (show items, filter by branch,category) >> Eigram  -->
</body>
</html>
