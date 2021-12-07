<?php
    include_once 'env/connection.php';
    $item = $branch = $categ = "";

    if (isset($_SESSION['itemID'])) {
        $item = $_SESSION['itemID'];
    }
    if (isset($_SESSION['branch']) || isset($_SESSION['categ'])) {
        $branch = $_SESSION['branch'];
        $categ = $_SESSION['categ'];
    }
?>

<!DOCTYPE html>
<html>
<head>
<title> Log In </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
</head>


<body>
    <!--Login form-->
    <div class="container-sm p-5 my-5 text-gray" style="max-width:50%;">
        <div class="jummbotron">
            <h3 style="color:#343434"> Log In </h3>
            <form action="login.php" method="post"> 
                Username: <input type="text"  class="form-control" name="username" required></br>
                Password: <input type="password"  class="form-control" name="password"  required></br>
                <input type="submit" value="Log In" name="login" class="form-control" style="width:150px;">
            </form>    
            <form action="main.php" method="post" class="form-inline">   
                <div class="mb-2 mt-2">
                    <input type="submit" value="Return" name="return" class="form-control" style="width:150px;">
                </div>
            </form>
            Don't have an account yet? <a href='client/register.php?itemID=<?php echo $item?>&branch=<?php echo $branch ?>&categ=<?php echo $categ ?>'>Register here</a>
        </div>
    </div>

    <?php 
        if (isset($_POST['login'])) {           #if login button is pressed
            $username = $_POST['username'];
            $password = $_POST['password'];

            $password = md5($password);         #hash

            #Check if admin or customer ------------------------------------------------------------------------------
            $admin_query = "SELECT * FROM admin WHERE admin_Username = '$username' AND admin_Password='$password';"; #check if in admin table
            $admin_result = mysqli_query($conn,$admin_query);
            $admin_Check = mysqli_num_rows($admin_result);

            if ($admin_Check>0) {                                               #username and password in admin table
                while($admin_row = mysqli_fetch_assoc($admin_result)) {
                    $_SESSION['admin'] = $admin_row['admin_ID'];                #store in $_SESSION for referencing later
                    $_SESSION['admin_User'] = $admin_row['admin_Username'];
                    mysqli_close($conn);
                    header("Location: admin/adminHome.php");                    #redirect to adminHome.php
                    exit;
                }                    
            }

            $sql = "SELECT * FROM customer;";                                    #check if in customer table
            $result = mysqli_query($conn,$sql);
            $resultCheck = mysqli_num_rows($result);
            $exists = false;                                                     
  
            if ($resultCheck>0){
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['cust_Username']==$username && $row['cust_Password']==$password) {
                        $exists = true;
                        $customerID = $row['cust_ID'];      
                        $_SESSION['username'] = $row['cust_Username'];
                        mysqli_close($conn);

                        header("Location: main.php");                           #Return to main.php
                        exit;
                    }
                }
            }                
            
            if ($exists == false) {                                             #If customer is unregistered
                echo "Wrong username or password";
                unset($_SESSION);
                exit;
            }    
        }

        #if "Return" is pressed: 
        if (isset($_POST['return'])) {
            unset($_SESSION);
            header('location: main.php');
            exit;
        }

        mysqli_close($conn);
    ?>

</body>
</html>