<?php
    include_once 'env/connection.php';
?>

<!DOCTYPE html>
<html>
<body>
    <!--<?php include "header.html"?> -->
    <!-- ito yun -->
    
    <h3> Log In </h3>
    <form action="login.php" method="post">
        Username: <input type="text" name="username"></br>
        Password: <input type="password" name="password"></br>
        <input type="submit" value="Log In" name="login">
    </form>     
  
    <?php 
        if (isset($_POST['login'])) {
            $Cu_Username = $_POST['username'];
            $Cu_Password = $_POST['password'];

            $sql = "SELECT * FROM customer;";
                    $result = mysqli_query($conn,$sql);
                    $resultCheck = mysqli_num_rows($result);
                    $exists = false;

                    #To add: check if admin or customer
                    if ($resultCheck>0){
                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($row['cust_Username']==$Cu_Username && $row['cust_Password']==$Cu_Password) {
                                echo "[go to main.php]??";
                                $exists = true;
                                $_SESSION['CustomerID'] = $row['cust_ID'];
                                $_SESSION['CustomerFname'] = $row['cust_Fname'];
                                header("Location: main.php");
                                exit;
                            }
                        }
                    }
            
            if ($exists == false) {
                    session_destroy();
                    header("Location: main.php");
                    exit;
                }
        }

        
    
    

    mysqli_close($conn);

    
    
    ?>

</body>
</html>