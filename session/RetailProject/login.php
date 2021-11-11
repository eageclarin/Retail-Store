<?php
    include_once 'env/connection.php';
?>

<!DOCTYPE html>
<html>
<body>
    
    <h3> Log In </h3>
    <form action="login.php" method="post"> 
        Username: <input type="text" name="username"></br>
        Password: <input type="password" name="password"></br>
        <input type="submit" value="Log In" name="login">
    </form>     
  
    <?php 
        if (isset($_POST['login'])) {           #if login button pressed
            $Cu_Username = $_POST['username'];
            $Cu_Password = $_POST['password'];

            $Cu_Password = md5($Cu_Password);
            #Username and Password checking
            $sql = "SELECT * FROM customer;"; #fix query, no need for while loop
            $result = mysqli_query($conn,$sql);
            $resultCheck = mysqli_num_rows($result);
            $exists = false;

            #To add: check if admin or customer
            if ($resultCheck>0){
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['cust_Username']==$Cu_Username && $row['cust_Password']==$Cu_Password) {
                        echo "[go to main.php]??";
                        $exists = true;
                        $_SESSION['CustomerID'] = $row['cust_ID'];      #$_SESSION values are accessible in other pages
                        $_SESSION['CustomerFName'] = $row['cust_FName'];
                        header("Location: main.php");                   #Return to main.php
                        exit;
                    }
                }
            }                
            
            if ($exists == false) {         #If customer is unregistered
                echo "Wrong username or password";
                echo "Don't have an account yet? <a href='client/register.php'>Register here</a>" ;
                exit;
            }

            #if "back to homepage" is pressed: 
            
        }
        mysqli_close($conn);
    ?>

</body>
</html>