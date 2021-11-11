<?php
    include_once '../env/connection.php';
?>

<!DOCTYPE html>
<html>
<body>
    
    <h3> Register </h3>
    <form action="register.php" method="post"> 
        Username: <input type="text" name="username" required></br>
        Password: <input type="password" name="password" required></br>
        First name: <input type="text" name="firstName" required>
        Last name: <input type="text" name="lastName" required></br>
        Email:  <input type="text" name="email" required></br>
        Brgy: <input type="text" name="brgy" required></br>
        City: <input type="text" name="city" required></br>
        Province: <input type="text" name="province" required></br>
        Postal Code: <input type="text" name="postal" required></br>
        <input type="submit" value="Register" name="register">
    </form>     
  
    <?php 
        if (isset($_POST['register'])) {           #if register button pressed
            $username = mysqli_real_escape_string($conn,$_POST['username']);
            $password1 = mysqli_real_escape_string($conn,$_POST['password']);
            $firstName = mysqli_real_escape_string($conn,$_POST['firstName']);
            $lastName = mysqli_real_escape_string($conn,$_POST['lastName']);
            $email = mysqli_real_escape_string($conn,$_POST['email']);
            $brgy = mysqli_real_escape_string($conn,$_POST['brgy']);
            $city = mysqli_real_escape_string($conn,$_POST['city']);
            $province = mysqli_real_escape_string($conn,$_POST['province']);
            $postal = mysqli_real_escape_string($conn,$_POST['postal']);


            $password = md5($password1);

            $insert = "INSERT INTO customer (cust_Username, cust_Password, cust_FName, cust_LName, cust_Email, cust_ABrgy, cust_ACity, cust_AProvince, cust_APostal)
            VALUES ('$username', '$password', '$firstName', '$lastName', '$email', '$brgy', '$city', '$province', '$postal');";
            mysqli_query($conn, $insert);
            $_SESSION['CustomerFName']=$username;
            echo $_SESSION['CustomerFName'];
            #$_SESSION['CustomerID']
            #header('location: ../main.php');
             
            
        }
        mysqli_close($conn);
    ?>

</body>
</html>