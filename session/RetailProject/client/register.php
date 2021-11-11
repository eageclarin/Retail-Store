<?php
    include_once '../env/connection.php';
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
<div class="container-sm p-5 my-5 bg-dark text-white" style="max-width:50%;">
    <h2> Register </h2>
    <form action="register.php" method="post" class="form-inline"> 
    <div class="form-group">
        <div class="mb-1 mt-1">
            <label for="username" >Username: </label>
            <input type="text" class="form-control" id="username" name="username"  required>
       </div>
        <div class="mb-1 mt-1">
            <label for="password" >Password: </label>
            <input type="password" class="form-control" id="password" name="password"  required>
        </div>
        <div class="mb-1 mt-1">
            <label for="firstName" >First Name: </label>
            <input type="text" class="form-control" id="firstName" name="firstName"  required>    
        </div>
        <div class="mb-1 mt-1">  
            <label for="lastName" >Last Name: </label>
            <input type="text" class="form-control" id="lastName" name="lastName"  required>
        </div>
        <div class="mb-1 mt-1">
            <label for="email" >Email: </label>
            <input type="text" class="form-control" id="email" name="email"  required>
        </div>
        <div class="col-xs-3">
        <div class="mb-1 mt-1">
            <label for="brgy" >Barangay: </label>
            <input type="text" class="form-control" id="brgy" name="brgy"  required>
        </div>
        </div>
        <div class="col-xs-3">
        <div class="mb-1 mt-1">
            <label for="city" >City: </label>
            <input type="text" class="form-control" id="city" name="city"  required>
        </div>
        </div>
        <div class="mb-1 mt-1">
            <label for="province" >Province: </label>
            <input type="text" class="form-control" id="province" name="province"  required>
        </div>
        <div class="mb-1 mt-1">
            <label for="postal" >Postal Code: </label>
            <input type="text" class="form-control" id="postal" name="postal"  required>
        </div>
        <!--
        Username: <input type="text" name="username" required></br>
        Password: <input type="password" name="password" required></br>
        First name: <input type="text" name="firstName" required> </br>
        Last name: <input type="text" name="lastName" required></br>
        Email:  <input type="text" name="email" required></br>
        Brgy: <input type="text" name="brgy" required></br>
        City: <input type="text" name="city" required></br>
        Province: <input type="text" name="province" required></br>
        Postal Code: <input type="text" name="postal" required></br>
        -->
        <div class="mb-3 mt-3">
            <input type="submit" value="Submit" name="register" class="form-control" style="width:150px">
        </div>
    </div>
    </form>    
</div>


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


            #check if username or email exists




            $password = md5($password1);

            $insert = "INSERT INTO customer (cust_Username, cust_Password, cust_FName, cust_LName, cust_Email, cust_ABrgy, cust_ACity, cust_AProvince, cust_APostal)
            VALUES ('$username', '$password', '$firstName', '$lastName', '$email', '$brgy', '$city', '$province', '$postal');";
            mysqli_query($conn, $insert);
            $_SESSION['CustomerFName']=$username;
            echo $_SESSION['CustomerFName'];
            header('location: ../main.php');
             
            
        }
        mysqli_close($conn);
    ?>

    <!--cancel button/ back to homepage-->
</body>
</html>