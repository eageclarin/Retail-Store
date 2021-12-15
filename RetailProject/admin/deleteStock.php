<?php
    include_once '../env/connection.php';
    include_once '../env/adminAuth.php';

    
    if(isset($_POST['deleteItem'])) {

        $password = md5($_POST['deleteAdminPass']);
        $admin_confirmation_query = "SELECT admin_Username FROM admin where admin.admin_Password='$password';";
        $admin_confirmation_result = mysqli_query($conn,$admin_confirmation_query);
        $admin_confirmation_Check = mysqli_num_rows($admin_confirmation_result);
        $admin_confirmation_user="";
        if($admin_confirmation_Check>0){
            while($admin_confirmation_row = mysqli_fetch_assoc( $admin_confirmation_result)) {
                
                $admin_confirmation_user = $admin_confirmation_row['admin_Username'];
         
            }       
        }
        if($admin_confirmation_user== $_SESSION['admin_User'] ){

            echo $id =$_POST['delItem_ID'];
            echo $inventoryID=$_POST['delInventory_ID'];
    
            $delete_query = "DELETE from BI_has_I where item_ID=$id AND inventory_ID=$inventoryID";
       
            $delete_result = mysqli_query($conn,$delete_query);
        
            if($delete_result){
                $_SESSION['confirm_err']=2;
                header('location: inventory.php');
            }else{
                die(mysqli_error($conn));
            }

        }else{
            $_SESSION['confirm_err']=1;
            header('location: inventory.php');
        }                    
    

        

    }else{
        header('location: inventory.php');
    };


    mysqli_close($conn);



    

?>
