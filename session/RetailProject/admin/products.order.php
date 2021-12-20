<?php
include_once '../env/connection.php';
include_once '../env/adminAuth.php';


if(isset($_POST['orderProduct'])) {
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
          
        $inventoryID=$_POST['Inventory_ID'];
        $itemID =$_POST['Item_ID'];
        $Stock =$_POST['itemStock'];
        $query = "SELECT * FROM bi_has_i where bi_has_i.item_ID=$itemID AND bi_has_i.inventory_ID=$inventoryID;";
        $result = mysqli_query($conn,$query);
        $Check = mysqli_num_rows($result);
      
        if($Check>0){
            $products_query = "UPDATE bi_has_i SET item_Stock = item_Stock+$Stock WHERE bi_has_i.inventory_ID =$inventoryID AND bi_has_i.item_ID = $itemID;";
            $products_result = mysqli_query($conn,$products_query);
            if($products_result){
                header('location: products.php');
            }else{
                die(mysqli_error($conn));
            }

        }else{
            $products_query = "INSERT INTO bi_has_i (inventory_ID, item_ID, item_Stock) VALUES ($inventoryID, $itemID, $Stock);";
            $products_result = mysqli_query($conn,$products_query);
            if($products_result){
                header('location: products.php');
            }else{
                die(mysqli_error($conn));
            }

        }

    }else{
        $_SESSION['confirm_err']=1;
        header('location: products.php');
    }                    

    mysqli_close($conn);


}else{
    $_SESSION['confirm_err']=1;
    header('location:  order.pending.php');
} 


?>