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

            $id =$_POST['delItem_ID'];
            $delete_query = "SELECT count(item_ID) as item_count FROM `item` NATURAL JOIN bi_has_i WHERE item_ID=$id;";
            $result = mysqli_query($conn,$delete_query);
            $row = mysqli_fetch_assoc( $result);
            $count = $row['item_count'];
           
                       
                if($row['item_count']==0){
                    $delete_query = "UPDATE `item` SET `item_Status` = '1' WHERE `item`.`item_ID` = 1";
                    $delete_result = mysqli_query($conn,$delete_query);
    
                    if($delete_result){
                        $_SESSION['confirm_err']=2;
                        header('location: products.php');
                    }
                    else{
                        die(mysqli_error($conn));
                    }
                }else{
                    $_SESSION['confirm_err']=3;
                    header('location:  products.php');
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
