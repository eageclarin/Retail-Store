<?php
    include_once '../env/connection.php';
    
    $id = $_SESSION['userID'];

    $cust_query ="SELECT *FROM customer WHERE cust_ID = $id";
    $cust_result = mysqli_query($conn,$cust_query);
    $cust_Check = mysqli_num_rows($cust_result);
            
    if ($cust_Check>0){
        while ($cust_row = mysqli_fetch_assoc($cust_result)){
            $username = $cust_row['cust_Username'];
            $password1 = $cust_row['cust_Password'];
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
    
    $password = md5($password1);

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
    <!-- Registration form -->
    <div class="container-sm p-5 my-5 bg-dark text-white" style="max-width:50%;">
        <h2> Profile </h2>
        <form id="form" action="profile.php" method="post" class="form-inline"> 
            <div class="form-group">
                <div class="mb-1 mt-1">
                    <label for="username" >Username: </label>
                    <input type="text" class="form-control" id="username" name="username"  value="<?php echo $username?>">
                </div>
                <div class="mb-1 mt-1">
                    <label for="password" >Password: </label>
                    <input type="password" class="form-control" id="password" name="password"  value="<?php echo $password?>">
                    <span id="toggle" onclick="toggle('password')"><i class="fa fa-eye"></i> </span> 
                </div> 
                <div class="mb-1 mt-1">
                    <label for="password" >Confirm Password: </label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"  value="<?php echo $password?>">
                    <span id="toggle" onclick="toggle('confirmPassword')"><i class="fa fa-eye"></i> </span>
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
                <div class="mb-3 mt-3">
                    <input type="submit" value="Update" name="cust_update" class="btn btn-primary" style="width:150px"  >   
                        
                </div>
            </div>
        </form>  
        <form action="profile.php" method="post" class="form-inline">   
            <input  type="submit" value="Cancel" name="cancel" class="form-control" style="width:150px" > 
        </form>
    </div>
 
</body>
</html>

<?php 

if (isset($_POST['cust_update'])) {           #if register button pressed
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

    
        $password = md5($password1);    #hash
        $insert = "UPDATE customer SET cust_Username='$username', cust_Password= '$password', cust_FName='$firstName', cust_LName='$lastName',cust_Contact=$contact, cust_Email='$email', cust_ABrgy='$brgy', cust_ACity='$city', cust_AProvince='$province', cust_APostal='$postal'  WHERE cust_ID=$id";
        $update_result = mysqli_query($conn, $insert);
        if ($update_result) {
            echo "<script> location.replace('../main.php'); </script>";
        } else {
            die(mysqli_error($conn));
        }

}

if (isset($_POST['cancel'])) {            #if cancel is pressed
    echo "<script> location.replace('../main.php'); </script>";
}

mysqli_close($conn);
?>