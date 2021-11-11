<?php
    include_once 'env/connection.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
</head>


<body>
    
<div class="container">
    <h3> Log In </h3>
    <form action="login.php" method="post"> 
        Username: <input type="text" name="username" required></br>
        Password: <input type="password" name="password" required></br>
        <input type="submit" value="Log In" name="login">
    </form>     
    <form action="main.php" method="post" class="form-inline">   
    <input type="submit" value="Return" name="return" class="form-control" style="width:150px">
    </form>
</div>
    <?php 
        if (isset($_POST['login'])) {           #if login button pressed
            $username = $_POST['username'];
            $password = $_POST['password'];

            $password = md5($password);


            #Check if admin or customer
            $admin_query = "SELECT * FROM admin WHERE admin_Username = '$username' AND admin_Password='$password';";
           #$sql = "SELECT * FROM admin;";
            $admin_result = mysqli_query($conn,$admin_query);
            $admin_Check = mysqli_num_rows($admin_result);

            if ($admin_Check>0) {
                while($admin_row = mysqli_fetch_assoc($admin_result)) {
                        $_SESSION['admin'] = $admin_row['admin_ID'];
                        $_SESSION['admin_User'] = $admin_row['admin_Username'];
                        mysqli_close($conn);
                        header("Location: admin/adminHome.php");
                        exit;
                }
                        
            }

            #Username and Password checking
            $sql = "SELECT * FROM customer;"; #fix query, no need for while loop
            $result = mysqli_query($conn,$sql);
            $resultCheck = mysqli_num_rows($result);
            $exists = false;
  
            if ($resultCheck>0){
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['cust_Username']==$username && $row['cust_Password']==$password) {
                        $exists = true;
                        $_SESSION['CustomerID'] = $row['cust_ID'];      #$_SESSION values are accessible in other pages
                        $_SESSION['CustomerFName'] = $row['cust_FName'];
                        mysqli_close($conn);
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
            if (isset($_POST['return'])) {
                header('location: main.php');
            }
            
        }
        mysqli_close($conn);
    ?>

</body>
</html>