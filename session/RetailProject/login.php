<?php
    include_once 'env/userConnection.php';
    $item = $branch = $categ = "";
?>

<!DOCTYPE html>
<html>
<head>
<title> Log In </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html,body {
        height: 100%;
        }

        body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #E6E9F0;
        }

        .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
        }

        .form-signin .checkbox {
        font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
        z-index: 2;
        }

        .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        }

      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
</head>


<body class="text-center">
    <main class="form-signin">
    <form action="login.php" method="post">
        <img class="mb-4" src="img/logo2.png" alt="" height="150">
        <h1 class="h3 mb-3 fw-normal">Log In</h1>

        <div class="form-floating">
        <input type="text"  class="form-control" id="username" name="username" required>
        <label for="username">Username</label>
        </div>
        <div class="form-floating">
        <input type="password"  class="form-control" name="password" if="password" required>
        <label for="password">Password</label>
        </div>
    
        <button class="w-100 btn btn-lg btn-primary mb-1" name="login" type="submit">Sign in</button>
        <a href="main.php"> <button type="submit"name="return" class="w-100 btn btn-lg text-primary border-primary">Return</button> </a>
    </form>
        Don't have an account yet? <a href='client/register.php'>Register here</a>
        <p class="mt-5 mb-3 text-muted">&copy; 2021</p>
    
    </main>

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
                    $_SESSION['confirm_err']=0;
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
                        $_SESSION['userID'] = $row['cust_ID'];      
                        $_SESSION['username'] = $row['cust_Username'];
                        mysqli_close($conn);

                        header("Location: main.php");                           #Return to main.php
                        exit;
                    }
                }
            }                
            
            if ($exists == false) {                                             #If customer is unregistered
                echo '<div class="container-sm p-1 my-1 bg-danger text-white" style="max-width:50%;">
                Wrong username or password.
                </div>';
                unset($_SESSION);
                exit;
            }    
        }

        mysqli_close($conn);
    ?>

</body>
</html>