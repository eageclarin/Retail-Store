<?php

include_once '../env/connection.php';
include_once '../env/adminAuth.php';

?>


<?php
        if (isset($_POST['newUser'])) {

                $password = md5($_POST['AdminPass']);
                $admin_confirmation_query = "SELECT admin_Username FROM admin where admin_Password='$password';";
                $admin_confirmation_result = mysqli_query($conn,$admin_confirmation_query);
                $admin_confirmation_Check = mysqli_num_rows($admin_confirmation_result);
                $admin_confirmation_user="";
                if($admin_confirmation_Check>0){
                    while($admin_confirmation_row = mysqli_fetch_assoc( $admin_confirmation_result)) {
                        
                        $admin_confirmation_user = $admin_confirmation_row['admin_Username'];
                 
                    }       
                }

                if($admin_confirmation_user== $_SESSION['admin_User'] ){
                     
                    $newbranchName=$_POST['newBranchName'];
                    $branchAddress =$_POST['branchAddress'];
                    $adminFN =$_POST['adminFN'];
                    $adminLN = $_POST['adminLN'];
                    $branchContact =$_POST['branchContact'];
                    $branchUser = $_POST['branchUser'];
                    $branchUserPass = md5($_POST['branchUserPass']);
                    $branchContact =$_POST['branchUserContact'];
                   
            
        
                    $query1= "INSERT INTO `admin` ( `admin_Username`, `admin_Firstname`, `admin_Lastname`, `admin_Password`) VALUES ( '$branchUser', ' $adminFN', ' $adminLN', '$branchUserPass');";
                    $query1_res = mysqli_query($conn,$query1);
                        
                    
                    $query2= "SELECT admin_ID FROM `admin` where admin_Username='$branchUser ';";
                    $query2_res = mysqli_query($conn,$query2);
                    $row = mysqli_fetch_assoc( $query2_res);
                     $newadmin_ID = $row['admin_ID'];

                    $query3= "INSERT INTO `admin_contact` (`admin_ID`, `contact`) VALUES ($newadmin_ID, $branchContact)";
                    $query3_res = mysqli_query($conn,$query3);

                    $query4= "INSERT INTO `branch` ( `branch_Name`, `branch_Address`) VALUES ( ' $newbranchName', '$branchAddress')";
                    $query4_res = mysqli_query($conn,$query4);

                    if($query4_res){

                        $query5= "SELECT * FROM branch where branch_Address='$branchAddress';";
                        $query5_res = mysqli_query($conn,$query5);
                        $query5_row = mysqli_fetch_assoc($query5_res);
                        $newbranch_ID = $query5_row['branch_ID'];

                        $query5a= "INSERT INTO `branch_contact` (`branch_ID`, `contact`) VALUES ($newbranch_ID ,  $branchContact)";
                        $query5a_res = mysqli_query($conn,$query5a);
                   
                       
                        $query6= "INSERT INTO `a_manages_b` (`admin_ID`, `branch_ID`) VALUES (  $newadmin_ID,  $newbranch_ID)";
                        $query6_res = mysqli_query($conn,$query6);

                        $query6a= "INSERT INTO `a_manages_b` (`admin_ID`, `branch_ID`) VALUES (  1 ,  $newbranch_ID)";
                        $query6a_res = mysqli_query($conn,$query6a);

                        $query7= "INSERT INTO `branchinventory` (`inventory_ID`) VALUES ($newbranch_ID);";
                        $query7_res = mysqli_query($conn,$query7);

                        $query8= "INSERT INTO `b_has_bi` (`branch_ID`, `inventory_ID`) VALUES ($newbranch_ID, $newbranch_ID)";
                        $query8_res = mysqli_query($conn,$query8);

                        if($query8_res){
                        header('location: adminHome.php');
                        }
                        else{
                            die(mysqli_error($conn));
                        }



                    }
                   

                 
    
                }else{
                    $_SESSION['confirm_err']=1;
                    header('location: adminHome.php');
                }                    

        }
        else{
            header("Location: ./adminHome.php"); 
        }

    ?>
