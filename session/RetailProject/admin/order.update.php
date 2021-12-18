<?php

include_once '../env/connection.php';
include_once '../env/adminAuth.php';


if(isset($_POST['orderUpdate'])) {
    $cartID =  $_POST['pendingCart_ID'];
    $status =$_POST['status'];

    $password = md5($_POST['AdminPass']);
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
        switch ( $status) {
            case 2:
            case 3:
                $query = "UPDATE cu_orders_ca SET status = $status WHERE cu_orders_ca.cart_ID = $cartID";

                $result = mysqli_query($conn,$query);
        
                if($result){
                    $_SESSION['confirm_err']=2;
                    header('location: order.pending.php');
                }else{
                    die(mysqli_error($conn));
                }
               
              break;
            default:
            header('location: order.pending.php');
          }


     


        

    }else{
        $_SESSION['confirm_err']=1;
        header('location:  order.pending.php');
    }      


mysqli_close($conn);


}

?>