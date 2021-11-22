<?php

include_once '../env/connection.php';
include_once '../env/adminAuth.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./components/admin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Admin</title>
</head>
<body>
    <div class="body">
        <?php include "./components/header.html"?>
        <?php include "./components/nav.html"?>

    <div class="form">
       
        <div class="form-con shadow">
            <h4>New Item Form</h4>
            <hr>
            <form class="row g-3"  action="addItem.php" method="post">
                <div class="col-12">
                    <label for="ItemName" class="form-label">Item name</label>
                    <input type="text" class="form-control" name="ItemName" >
                </div>
                <div class="col-md-6">
                    <label for="Retail_Price" class="form-label">Retail Price</label>
                    <input type="number" class="form-control" name="RetailPrice" min=0>
                </div>
                <div class="col-md-6">
                    <label for="Wholesale_Price" class="form-label">Wholesale Price</label>
                    <input type="number" class="form-control" name="WholesalePrice"  min=0>
                </div>
                
                <div class="col-md-6">
                    <label for="Retail_Price" class="form-label">Category</label>
                    <input type="text" class="form-control" name="Category" min=0 step=0.001>
                </div>
                <div class="col-md-6">
                    <label for="Brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" name="Brand" >
                </div>

                <div class="col-md-6">
                    <label for="Image" class="form-label">Image</label>
                    <input type="text" class="form-control" name="Image" >
                </div>
                <div class="col-md-6">
                    <label for="Stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="Stock"  min=0>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="Add">Add</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <?php
         if (isset($_POST['Add'])) {
               
               $inventoryID=$_SESSION['inventoryID'];
               $itemName =$_POST['ItemName'];
               $RetailPrice = $_POST['RetailPrice'];
               $WholesalePrice = $_POST['WholesalePrice'];
               $Category = $_POST['Category'];
               $Brand=$_POST['Brand'];
               $Image=$_POST['Image'];
               $Stock=$_POST['Stock'];
   
               $AddItem_query= "INSERT INTO item(item_Name,item_RetailPrice,item_WholesalePrice ,item_Category ,item_Brand ,item_Image) VALUES ('$itemName', $RetailPrice , $WholesalePrice, '$Category', '$Brand', '$Image ')";

             
               $AddItem_result = mysqli_query($conn,$AddItem_query);
                if($AddItem_result){
           
                }
                else{
                        die(mysqli_error($conn));
                }

             
            $item_query = "SELECT *FROM item WHERE item.item_Name= '$itemName'";
            


            $item_result = mysqli_query($conn,$item_query);
            $item_Check = mysqli_num_rows($item_result);
            
                      
            $itemID = 0;

            if($item_Check>0){
                while($itemrow = mysqli_fetch_array($item_result)) {
                    echo $itemID;
                    $itemID = $itemrow['item_ID'];
                    // exit;               
                }       
            }else{
                die(mysqli_error($conn));
            }                                         
                            
            

            $Bi_has_i_query= "INSERT INTO bi_has_i( inventory_ID,item_ID,item_Stock ) VALUES ($inventoryID , $itemID , $Stock )";
            $Bi_has_i_result = mysqli_query($conn,$Bi_has_i_query);

            if($Bi_has_i_result){
                header('location: inventory.php');
            }else{
                die(mysqli_error($conn));
            }

  

    

        }

    ?>

</body>
</html>