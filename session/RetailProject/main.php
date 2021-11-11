<?php
    include_once 'env/connection.php';
?>

<!DOCTYPE html>
<html>
<body>
    this is the main page..
    <?php 
    if (!empty($_SESSION['CustomerID'])) { //Checks if customer is logged in
        echo $_SESSION['CustomerID'];
    } else {
        echo '<a href="login.php">Log In</a>';
    }
        
    
    ?>
    
    <!--Insert Items here (show items, filter by branch,category) >> Eigram  -->
</body>
</html>
