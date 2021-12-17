<?php

    include '../env/connection.php';
    include '../env/adminAuth.php';
   

    $user = $_SESSION['admin_User'] ;
    $branchID_query = "SELECT *FROM branch NATURAL JOIN (b_has_bi) NATURAL JOIN branchinventory NATURAL JOIN a_manages_b NATURAL JOIN admin WHERE admin.admin_Username= '$user' ;"; #check if in admin table
    $branchID_result = mysqli_query($conn,$branchID_query);
    $branchID_Check = mysqli_num_rows($branchID_result); #should be same with eigram

    if ($branchID_Check>0) {                                               #username and password in admin table
        while($branchID_row = mysqli_fetch_assoc($branchID_result)) {
            $_SESSION['branchID'] = $branchID_row['branch_ID'];                #store in $_SESSION for referencing later
            $_SESSION['inventoryID'] = $branchID_row['inventory_ID']; 
           
        }                    
    }

    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- ajax -->
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- jquery -->
        <script src="jquery-3.5.1.min.js"></script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>

 
    <title>Admin | Summary</title>
    <script src="../env/idle.js"></script>
  </head>
  <body>
        <?php include "./components/nav.php"?>

    

    





  </body>
</html>