<?php
    include_once '../env/connection.php';
    include_once '../env/adminAuth.php';
    
if (isset($_POST['onclickUpdate'])) {

   
    $password = md5($_POST['AdminPass']);
    $admin_confirmation_query = "SELECT admin_Username FROM admin where admin_Password='$password';";
    $admin_confirmation_result = mysqli_query($conn,$admin_confirmation_query);
    $admin_confirmation_Check = mysqli_num_rows($admin_confirmation_result);
    $admin_confirmation_user="";
    if($admin_confirmation_Check>0){
        while($admin_confirmation_row = mysqli_fetch_assoc( $admin_confirmation_result)) {
            
            $admin_confirmation_user = $admin_confirmation_row['admin_Username'];
     
        }
             
    };

    if($admin_confirmation_user== $_SESSION['admin_User'] ){
         $id =  $_POST['updateItem_ID'];
         $itemName =$_POST['updateItem_Name'];
         $RetailPrice = $_POST['updateRetail_Price'];
         $WholesalePrice = $_POST['updateWholesale_Price'];
         $Category = $_POST['update_Category'];
         $Brand=$_POST['updateBrand'];
         $Image=$_POST['updateImage'];
    
    
    
        $update_query = "UPDATE item SET item_Name='$itemName',
        item_RetailPrice = $RetailPrice ,item_WholesalePrice =$WholesalePrice, item_Category='$Category', 
        item_Brand ='$Brand', 
        item_Image='$Image' 
        WHERE item_ID = $id";

    
        $update_result = mysqli_query($conn,$update_query);
    
        if($update_result){
            $_SESSION['confirm_err']=2;
            header('location: products.php');
        }else{
            die(mysqli_error($conn));
        }
      
    



    }else{
        $_SESSION['confirm_err']=1;
        header('location: products.php');
    }                    

    }
    else{
        header("Location: ./products.php"); 
    };



?>

