<?php

include_once '../env/connection.php';
include_once '../env/adminAuth.php';

?>


<?php
        if (isset($_POST['Add'])) {

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
                     
                    $inventoryID=$_SESSION['inventoryID'];
                    $itemName =$_POST['ItemName'];
                    $Weight =$_POST['ItemWeight'];
                    $RetailPrice = $_POST['RetailPrice'];
                    $WholesalePrice = $_POST['WholesalePrice'];
                    $Category = $_POST['Category'];
                    $Brand=$_POST['Brand'];
                    $Image=$_POST['Image'];
            
        
                    $AddItem_query= "INSERT INTO item(item_Name,item_Weight,item_RetailPrice,item_WholesalePrice ,item_Category ,item_Brand ,item_Image) VALUES ('$itemName',$Weight, $RetailPrice , $WholesalePrice, '$Category', '$Brand', '$Image ')";

                    
                    $AddItem_result = mysqli_query($conn,$AddItem_query);
                        if($AddItem_result){
                            header('location: products.php');
                        }
                        else{
                                die(mysqli_error($conn));
                        }

    
                }else{
                    $_SESSION['confirm_err']=1;
                    header('location: products.php');
                }                    

        }
        else{
            header("Location: ./adminHome.php"); 
        }

    ?>
