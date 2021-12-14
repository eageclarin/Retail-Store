<?php
    include_once '../env/connection.php';
    
    $id = $_SESSION['userID'];
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

?>

<!DOCTYPE html>
<html>
<head>
<title> Profile </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
  <script src="client.js"></script>

</head>

<body>
    <!-- customer details -->
    <div class="container-sm p-5 my-5 mb-1 bg-dark text-white" style="max-width:50%;">
    <div style="display:flex; padding-right:10px; padding-bottom:10px;">
        <img src="https://github.com/mdo.png" alt="mdo" width="50" height="50" class="pr-5 rounded-circle">
        <h2 style="padding-left:10px;"> <?php echo $firstName." ". $lastName?> </h2>
    </div>
        <form id="form" action="profile.php" method="post" class="form-inline"> 
            <div class="form-group">
                <div class="mb-1 mt-1">
                    <label for="username" >Username: </label>
                    <input type="text" class="form-control" id="username" name="username"  value="<?php echo $username?>">
                </div>
                <div class="mb-1 mt-1">
                    <label for="firstName" >First Name: </label>
                    <input type="text" class="form-control" id="firstName" name="firstName"  value="<?php echo $firstName?>">    
                </div>
                <div class="mb-1 mt-1">  
                    <label for="lastName" >Last Name: </label>
                    <input type="text" class="form-control" id="lastName" name="lastName"  value="<?php echo $lastName?>">
                </div>
                <div class="mb-1 mt-1">
                    <label for="contact" >Contact Number: </label>
                    <input type="text" class="form-control" id="contact" name="contact"   value="<?php echo $contact?>" >
                </div>
                <div class="mb-1 mt-1">
                    <label for="email" >Email: </label>
                    <input type="text" class="form-control" id="email" name="email"  value="<?php echo $email?>">
                </div>
                <div class="col-xs-3">
                    <div class="mb-1 mt-1">
                        <label for="brgy" >Barangay: </label>
                        <input type="text" class="form-control" id="brgy" name="brgy"  value="<?php echo $brgy?>">
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="mb-1 mt-1">
                        <label for="city" >City: </label>
                        <input type="text" class="form-control" id="city" name="city"  value="<?php echo $city?>">
                    </div>
                </div>
                <div class="mb-1 mt-1">
                    <label for="province" >Province: </label>
                    <input type="text" class="form-control" id="province" name="province"  value="<?php echo $province?>">
                </div>
                <div class="mb-1 mt-1">
                    <label for="postal" >Postal Code: </label>
                    <input type="text" class="form-control" id="postal" name="postal"  value="<?php echo $postal?>">
                </div>
                <a data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="text-decoration:underline; cursor:pointer">Change Password</a>
                <div class="mb-3 mt-3">
                    <input type="submit" value="Update" name="cust_update" class="btn btn-primary" style="width:150px"  >   
                        
                </div>
            </div>
        </form>  
        <form action="profile.php" method="post" class="form-inline">   
            <input  type="submit" value="Cancel" name="cancel" class="form-control" style="width:150px" > 
        </form>
    </div>

    <!-- change Password dialog box-->
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form" action="profile.php" method="post" class="form-inline"> 
                    <div class="mb-1 mt-1">
                        <label for="oldPassword" >Enter current password: </label>
                        <input type="password" class="form-control" id="oldPassword" name="oldPassword"  value="<?php echo $password?>">
                        <span id="toggle" onclick="toggle('oldPassword')"><i class="fa fa-eye" style="cursor:pointer;"></i> </span> 
                    </div> 
                    <div class="mb-1 mt-1">
                        <label for="password" >Password: </label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword"   required>
                        <span id="toggle" onclick="toggle('newPassword')"><i class="fa fa-eye"style="cursor:pointer;"></i> </span> 
                    </div> 
                    <div class="mb-1 mt-1">
                        <label for="confirmPassword" >Confirm Password: </label>
                        <input type="password" class="form-control" id="confirmNPassword" name="confirmNPassword"   required>
                        <span id="toggle" onclick="toggle('confirmNPassword')"><i class="fa fa-eye"style="cursor:pointer;"></i> </span>
                    </div> 
                    <input  type="submit" value="Update" name="updatePass" class="form-control" style="width:150px" > 
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
            echo "<script> location.replace('../main.php'); </script>";
        } else {
            die(mysqli_error($conn));
        }

     } else {
         echo "username already exists";
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

if (isset($_POST['cancel'])) {            #if cancel is pressed
    echo "<script> location.replace('../main.php'); </script>";
}

mysqli_close($conn);
?>