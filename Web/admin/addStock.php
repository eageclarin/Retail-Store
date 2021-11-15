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
    mysqli_close($conn);

?>