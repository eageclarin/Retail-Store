<?php
    include_once '../env/connection.php';
    include_once '../env/adminAuth.php';

    

    if(isset($_POST['decreaseStock'])) {

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

            $decStock_itemID =$_POST['Item_ID'];
            $decStock_Value=$_POST['itemStock'];
            $inventoryID=$_POST['Inventory_ID'];

            $decStock_query = "UPDATE `bi_has_i` SET item_Stock=item_Stock- $decStock_Value
            WHERE item_ID =$decStock_itemID  AND inventory_ID=$inventoryID;";

            $decStock_result = mysqli_query($conn,$decStock_query);

            if($decStock_result){

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
    }
    
    

    if(isset($_POST['deleteStock'])) {

        $id =$_POST['Item_ID'];
        $inventoryID=$_POST['inventory_ID'];

        $delete_query = "DELETE from BI_has_I where item_ID=$id AND inventory_ID=$inventoryID";
   
        $delete_result = mysqli_query($conn,$delete_query);
    
        if($delete_result){
            header('location: inventory.php');
        }else{
            die(mysqli_error($conn));
        }

    }
        
    if(isset($_POST['addStock'])) {

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
            $addStock_itemID =$_POST['Item_ID'];
            $AddStock_Value=$_POST['itemStock'];
            $inventoryID=$_POST['Inventory_ID'];

            $addStock_query = "UPDATE bi_has_i SET item_Stock=item_Stock+$AddStock_Value
            WHERE item_ID =$addStock_itemID  AND inventory_ID=$inventoryID";

            $AddStock_result = mysqli_query($conn,$addStock_query);

            if($AddStock_result){
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
    }   



    mysqli_close($conn);



    

?>
