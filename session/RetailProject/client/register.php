<?php
    require '../env/userConnection.php';
    $item = $branch = $categ = $warning = "";

    if (isset($_GET['itemID'])) {
        $item = $_GET['itemID'];
    }

    if (isset($_GET['branch']) && isset($_GET['categ'])) {
        $branch = $_GET['branch'];
        $categ = $_GET['categ'];
    }

    if (isset($_POST['register'])) {           #if register button pressed
        $username = mysqli_real_escape_string($conn,$_POST['username']);
        $password1 = mysqli_real_escape_string($conn,$_POST['password']);
        $firstName = mysqli_real_escape_string($conn,$_POST['firstName']);
        $lastName = mysqli_real_escape_string($conn,$_POST['lastName']);
        $contact = $_POST['contact'];
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $brgy = mysqli_real_escape_string($conn,$_POST['brgy']);
        $city = mysqli_real_escape_string($conn,$_POST['city']);
        $province = mysqli_real_escape_string($conn,$_POST['province']);
        $postal = mysqli_real_escape_string($conn,$_POST['postal']);


        #check if username or email exists
        $check_query = "SELECT * FROM customer where cust_Username = '$username' OR cust_Email = '$email' LIMIT 1;";
        $result = mysqli_query($conn, $check_query);
        $resultCheck = mysqli_num_rows($result);

        
        if ($resultCheck==0){               #if username or email does not exist, insert new record
            $password = md5($password1);    #hash

            $insert = "INSERT INTO customer (cust_Username, cust_Password, cust_FName, cust_LName, cust_Contact, cust_Email, cust_ABrgy, cust_ACity, cust_AProvince, cust_APostal)
            VALUES ('$username', '$password', '$firstName', '$lastName','$contact', '$email', '$brgy', '$city', '$province', '$postal');";
            $sqlInsert = mysqli_query($conn, $insert);
            if ($sqlInsert) {
                echo "<script> location.replace('../login.php'); </script>";
            } else {
                echo mysqli_error($conn);
            }
        } else {                            #else, notify user
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['cust_Username']==$username && $row['cust_Email']==$email) {
                    $warning = "Username and Email already exist";
                    break;
                } elseif ($row['cust_Username']==$username) {
                    $warning = "Username already exist";
                    break;
                } elseif ($row['cust_Email']==$email) {
                    $warning = "Email already exist";
                    break;
                }
            }
        }    
    }

    if (isset($_POST['back'])) {            #if cancel is pressed
       echo "<script> location.replace('../login.php'); </script>";
    }
?>

<!DOCTYPE html>
<html>
<head>
<title> Register </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="client.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="client.js"></script>
 
    <style>
        body {
            overflow: hidden;
            height: 100%;
            width: 100%;
            background: rgb(196,53,49);
            background: linear-gradient(144deg, rgba(196,53,49,1) 0%, rgba(218,55,50,1) 26%, rgba(228,123,120,1) 78%);
        }
        .field-icon {
            float: right;
            margin-left: 19%;
            margin-top: -25px;
            position: fixed;
            z-index: 2;
        }
    </style>
</head>

<body>
    <!-- Registration form -->
    <section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row justify-content-center align-items-center h-100">
        <div class="col-12 col-lg-9 col-xl-7">
            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <img src="../img/logo.png" width="50%">
                    </div>
                    <div class="col-md-6 mb-4">
                    <h3><p class="text-end">Registration Form</p></h3>
                    </div>
                </div>
                
                <form id="form" action="register.php" method="post" class="form-inline">

                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"  required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div>
                            <input type="password" class="form-control" id="password" name="password"  required> <span id="toggle" onclick="toggle('password')" class="fa fa-fw fa-eye field-icon toggle-password"> </span>
                        </div>
                        <label for="password" class="form-label">Password</label>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"  required><span id="toggle" onclick="toggle('confirmPassword')" class="fa fa-fw fa-eye field-icon toggle-password"> </span>
                        </div>
                        <label for="password" class="form-label">Confirm Password</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="firstName" name="firstName"  required>   
                            <label class="form-label" for="firstName" >First Name</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="lastName" name="lastName"  required>
                            <label class="form-label" for="lastName" >Last Name</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="contact" name="contact"  required>
                            <label class="form-label" for="contact" >Contact Number</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="email" name="email" required>
                            <label class="form-label" for="email" >Email: </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="brgy" name="brgy"  required>
                            <label class="form-label" for="brgy" >Barangay: </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="city" name="city"  required>
                            <label class="form-label" for="city" >City: </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="province" name="province"  required>
                            <label class="form-label" for="province" >Province: </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="postal" name="postal"  required>
                            <label class="form-label" for="postal" >Postal Code: </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2">
                    <input type="submit" value="Submit" name="register" class="btn btn-primary">
                    <a href="../login.php"><button type="button" class="btn text-primary border-primary">Cancel</button></a>&nbsp; <span class="text-danger"> <?php echo $warning ?> </span> 
                </div>

                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
    </section>
</body>
</html>