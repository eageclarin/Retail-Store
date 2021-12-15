<?php
    include_once '../env/connection.php';
    include_once '../env/adminAuth.php';

    
    if(isset($_POST['updateProfile'])) {

         $password = md5($_POST['userOld_pass']);
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
            echo$username =  $_POST['userUpdate_UserName'];
            echo$contact =$_POST['userUpdate_contact'];
            echo$pass = $_POST['userUpdate_pass'];
            echo$id = $_POST['userUpdate_ID'];
           

    
        $query = "UPDATE admin NATURAL join admin_contact SET admin.admin_Username='$username',
        admin_contact.contact = '$contact', admin.admin_Password='$pass' 
        WHERE admin_ID = $id;";

     

    
        $result = mysqli_query($conn,$query);
    
        if($result){
            $_SESSION['admin_User']= $username;
            $_SESSION['confirm_err']=2;
            header('location: inventory.php');
        }else{
            die(mysqli_error($conn));
        }
    

        

    }else{
        header('location: inventory.php');
    };


    mysqli_close($conn);

}



    

?>
