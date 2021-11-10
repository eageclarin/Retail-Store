<?php
    include_once 'env/connection.php';
?>

<!DOCTYPE html>
<html>
<body>
    this is the main page..
    <?php 
    if (!empty($_SESSION['CustomerID'])) {
        echo $_SESSION['CustomerID'];
    } else {
        echo '<a href="login.php">Log In</a>';
    }
        
    
    ?>
    
    <!--Insert Items here (show items, filter by branch,category) >> who? HAHA -->
</body>
</html>
