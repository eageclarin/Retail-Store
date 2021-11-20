<?php
    
    include_once '../env/connection.php';
    include_once '../env/adminAuth.php';
    
    $id = 0;

    if (isset($_POST['update'])) {
        $id = $_GET['update_item_id'];
        $itemName =$_POST['Item_Name'];
        $RetailPrice = $_POST['Retail_Price'];
        $WholesalePrice = $_POST['Wholesale_Price'];
        $Category = $_POST['Category'];
        $Brand=$_POST['Brand'];
        $Image=$_POST['Image'];

    

    $update_query= "UPDATE item SET item_Name=$itemName,
    item_RetailPrice = $RetailPrice ,item_WholesalePrice =$WholesalePrice, item_Category=$Category, 
    item_Brand =$Brand, 
    item_Image=$Image 
    WHERE item_ID = $id";

    $update_result = mysqli_query($conn,$update_query);

    if($update_result){
        header('location: inventory.php');
    }else{
        die(mysqli_error($conn));
    }

   

    }

    mysqli_close($conn);
?>

<!-- include_once '../env/connection.php';
        $id = 0;

    if (isset($_POST['update'])) {
        $id = $_GET['update_item_id'];
        $itemName =$_POST['Item_Name'];
        $RetailPrice = $_POST['Retail_Price'];
        $WholesalePrice = $_POST['Wholesale_Price'];
        $Category = $_POST['Category'];
        $Brand=$_POST['Brand'];
        $Image=$_POST['Image'];

    

    $update_query= "UPDATE item SET item_Name=$itemName,
    item_RetailPrice = $RetailPrice ,item_WholesalePrice =$WholesalePrice, item_Category=$Category, 
    item_Brand =$Brand, 
    item_Image=$Image 
    WHERE item_ID = $id";

    $update_result = mysqli_query($conn,$update_query);

    if($update_result){
        header('location: inventory.php');
    }else{
        die(mysqli_error($conn));
    }

   

    }

    mysqli_close($conn); -->