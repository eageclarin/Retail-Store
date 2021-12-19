<?php
    require '../env/userConnection.php';
    $updated = $color = ""; $itle = "Edit Profile";

    if(isset($_SESSION)) {
        $name = $_SESSION['username'];
        $id = $_SESSION['userID'];

        $title = $name." | Edit Profile";
    }

    
    //query customer details
    $cust_query ="SELECT *FROM customer WHERE cust_ID = $id";
    $cust_result = mysqli_query($conn,$cust_query);
    $cust_Check = mysqli_num_rows($cust_result);
            
    if ($cust_Check>0){
        while ($cust_row = mysqli_fetch_assoc($cust_result)){
            $username = $cust_row['cust_Username'];
            $password = $cust_row['cust_Password'];
            $firstName = $cust_row['cust_FName'];
            $lastName = $cust_row['cust_LName'];
            $contact = $cust_row['cust_Contact'];
            $email = $cust_row['cust_Email'];
            $brgy = $cust_row['cust_ABrgy'];
            $city = $cust_row['cust_ACity'];
            $province = $cust_row['cust_AProvince'];
            $postal = $cust_row['cust_APostal'];
        }
     }else{
            header('location: ../main.php');
    }   
    
    if (isset($_GET['updated']) == 'yes') {
        $color = "success";
        $updated = "Profile Updated";
    } else if (isset($_GET['updated']) == 'no') {
        $color = "danger";
        $updated = "Username already Exists";
    }

?>

<!DOCTYPE html>
<html>
<head>
<title> <?php echo $title ?> </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
  <script src="client.js"></script>
  <link rel="stylesheet" href="client.css"/>
 
    <style>
        body {
            height: 115%;
            width: 100%;
            background: rgb(196,53,49);
            background: linear-gradient(144deg, rgba(196,53,49,1) 0%, rgba(218,55,50,1) 26%, rgba(228,123,120,1) 78%);
        }
        .field-icon {
            float: right;
            margin-left: 87%;
            margin-top: -25px;
            position: absolute;
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
                <div class="row mb-3">
                <div style="display:flex; padding-right:10px; padding-bottom:10px;">
                    <img src="https://github.com/mdo.png" alt="mdo" width="50" height="50" class="pr-5 rounded-circle">
                    <h2 style="padding-left:10px;"> <?php echo $firstName." ". $lastName?> </h2>
                </div>
                </div>
                
                <form id="form" action="profile.php" method="post" class="form-inline"> 

                <div class="row">
                    <div class="col-md-12 mb-2">
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $username?>">
                        <label class="form-label" for="username">Username</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $firstName?>">   
                            <label class="form-label" for="firstName" >First Name</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $lastName?>">
                            <label class="form-label" for="lastName" >Last Name</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $contact?>">
                            <label class="form-label" for="contact" >Contact Number</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email?>">
                            <label class="form-label" for="email" >Email </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="brgy" name="brgy" value="<?php echo $brgy?>">
                            <label class="form-label" for="brgy" >Barangay </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $city?>">
                            <label class="form-label" for="city" >City </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="province" name="province" value="<?php echo $province?>">
                            <label class="form-label" for="province" >Province </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-outline">
                            <input type="text" class="form-control" id="postal" name="postal" value="<?php echo $postal?>">
                            <label class="form-label" for="postal" >Postal Code </label>
                        </div>
                    </div>

                    <a data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="text-decoration:underline; cursor:pointer">Change Password</a>
                </div>

                <div class="mt-4 pt-2">
                    <input type="submit" value="Update" name="cust_update" class="btn btn-primary">
                    <input type="submit" value="Exit" name="exit" class="btn border-primary text-primary">&nbsp; <span class="text-<?php echo $color ?>"> <?php echo $updated ?> </span> 
                </div>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
    </section>
    <!-- change Password dialog box-->
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form" action="profile.php" method="post" class="form-inline"> 
            <div class="modal-body">
                
                    <div class="mb-1 mt-1">
                        <label for="oldPassword" >Enter current password: </label>
                        <div>
                            <input type="password" class="form-control" id="oldPassword" name="oldPassword"  value="<?php echo $password?>"> <span id="toggle" onclick="toggle('oldPassword')" class="fa fa-fw fa-eye field-icon toggle-password"> </span>
                        </div>
                    </div> 
                    <div class="mb-1 mt-1">
                        <label for="password" >Password: </label>
                        <div>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required> <span id="toggle" onclick="toggle('newPassword')" class="fa fa-fw fa-eye field-icon toggle-password"> </span>
                        </div>
                    </div> 
                    <div class="mb-1 mt-1">
                        <label for="confirmPassword" >Confirm Password: </label>
                        <div>
                            <input type="password" class="form-control" id="confirmNPassword" name="confirmNPassword" required> <span id="toggle" onclick="toggle('confirmNPassword')" class="fa fa-fw fa-eye field-icon toggle-password"> </span>
                        </div>
                    </div> 
                    
                
            </div>
            
            <div class="modal-footer">
                <input  type="submit" value="Update" name="updatePass" class="form-control btn btn-primary" style="width:150px" > 
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
            </div>
        </div>
        </div>
            <!--end modal-->

</body>
</html>

<?php 

if (isset($_POST['cust_update'])) {           #if update button pressed
    $olduser = $username;
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $firstName = mysqli_real_escape_string($conn,$_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn,$_POST['lastName']);
    $contact = $_POST['contact'];
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $brgy = mysqli_real_escape_string($conn,$_POST['brgy']);
    $city = mysqli_real_escape_string($conn,$_POST['city']);
    $province = mysqli_real_escape_string($conn,$_POST['province']);
    $postal = mysqli_real_escape_string($conn,$_POST['postal']);

     #check if username or email exists
     if ($olduser!=$username) {
         $check_query = "SELECT * FROM customer where cust_Username = '$username' LIMIT 1;";
         $result = mysqli_query($conn, $check_query);
        $resultCheck = mysqli_num_rows($result);
     } else {
         $resultCheck = 0;
     }
     

     if ($resultCheck==0) {
        $insert = "UPDATE customer SET cust_Username='$username', cust_FName='$firstName', cust_LName='$lastName',cust_Contact=$contact, cust_Email='$email', cust_ABrgy='$brgy', cust_ACity='$city', cust_AProvince='$province', cust_APostal='$postal'  WHERE cust_ID=$id";
        $update_result = mysqli_query($conn, $insert);
        if ($update_result) {
            $_SESSION['username'] = $username;
            echo "<script> location.replace('profile.php?updated=yes'); </script>";
        } else {
            die(mysqli_error($conn));
        }

     } else {
        echo "<script> location.replace('profile.php?updated=no'); </script>";
     }

    

}

if (isset($_POST['updatePass'])) {           #if update password is pressed

    $password1 = md5(mysqli_real_escape_string($conn,$_POST["oldPassword"]));
    
    if ($password==$password1) {
        if (isset($_POST['newPassword'])&& isset($_POST['confirmNPassword'])) {
            $password = md5($_POST['newPassword']);    #hash
            $update_pass = "UPDATE customer SET cust_Password= '$password'   WHERE cust_ID=$id";
            $update_pass_result = mysqli_query($conn, $update_pass);
            if ($update_pass) {
                echo '<div class="container-sm p-1 my-1 bg-success text-white" style="max-width:50%;">
                Password changed succesfully.
                </div>';
                unset($_POST['newPassword']);
            } else {
                die(mysqli_error($conn));
            }
        } else {
            echo "Please enter and confirm new password";
        }
        
    } else {
        echo '<div class="container-sm p-1 my-1 bg-danger text-white" style="max-width:50%;">
                Wrong Password. Please try again.
                </div>';
    }
    unset($_POST['updatePass']);
}

if (isset($_POST['exit'])) {
    $_SESSION['username'] = $username;
    echo "<script> location.replace('../main.php'); </script>";
}

mysqli_close($conn);
?>