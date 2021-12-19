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
            $username =  $_POST['userUpdate_UserName'];
            $contact =$_POST['userUpdate_contact'];
            $pass = $_POST['userUpdate_pass'];
            if(strlen($pass)!=32){
                $pass=md5($pass); 
            }
            $id = $_POST['userUpdate_ID'];
           

    
        $query = "UPDATE admin NATURAL join admin_contact SET
        admin_contact.contact = '$contact', admin.admin_Password='$pass' 
        WHERE admin_ID = $id;";

     

    
        $result = mysqli_query($conn,$query);
    
        if($result){
            $_SESSION['confirm_err']=2;
            header('location: inventory.php');
        }else{
            die(mysqli_error($conn));
        }
    

        

    }else{
        $_SESSION['confirm_err']=1;
        header('location: inventory.php');
    }      


    mysqli_close($conn);

}



    

?>
