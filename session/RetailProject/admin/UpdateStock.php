<?php
    include '../env/connection.php';
    include '../env/adminAuth.php';


    $inventoryID=$_SESSION['inventoryID'];
    if(isset($_POST['addStock'])) {

        $addStock_itemID =$_POST['Item_ID'];
        $AddStock_Value=$_POST['Stock'];

        $addStock_query = "UPDATE bi_has_i SET item_Stock=item_Stock+ $AddStock_Value
        WHERE item_ID =$addStock_itemID  AND inventory_ID=$inventoryID";

        $AddStock_result = mysqli_query($conn,$addStock_query);

        if($AddStock_result){
            // echo "<script> alert(\"You Added Stock\")</script>"; style="display:none;" 
            header('location: inventory.php');
        }else{
            die(mysqli_error($conn));
        }
    }
    
   

    if(isset($_POST['decreaseStock'])) {

        $decStock_itemID =$_POST['Item_ID'];
        $decStock_Value=$_POST['Stock'];

        $decStock_query = "UPDATE bi_has_i SET item_Stock=item_Stock- $decStock_Value
        WHERE item_ID =$decStock_itemID  AND inventory_ID=$inventoryID";

        $decStock_result = mysqli_query($conn,$decStock_query);

        if($decStock_result){
            // echo "<script> alert(\"You Added Stock\")</script>"; style="display:none;" 
            header('location: inventory.php');
        }else{
            die(mysqli_error($conn));
        }

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

    mysqli_close($conn);

    

?>
