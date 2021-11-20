<?php # try to move to inventory.php
include '../env/connection.php';
include '../env/adminAuth.php';

if(isset($_GET['delete_item_id'])){
    $id=$_GET['delete_item_id'];

    $delete_query = "DELETE from BI_has_I where item_ID=$id AND inventory_ID=$inventoryID";
   
    $delete_result = mysqli_query($conn,$delete_query);

    if($delete_result){
        header('location: inventory.php');
    }else{
        die(mysqli_error($conn));
    }
}


mysqli_close($conn);
?>