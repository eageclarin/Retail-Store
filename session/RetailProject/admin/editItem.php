<?php

    include '../env/connection.php';
    include '../env/adminAuth.php';

    $id=0;
    $item_Name ="";
    $item_RetailPrice = 0.00;
    $item_WholesalePrice =0.00;
    $item_Category = "";
    $item_Image = "";
    $item_Brand = "";

    
        if (isset($_GET['edit_item_id'])) {
            $_SESSION['item_id'] = $_GET['edit_item_id'];

                    $inventoryID=$_SESSION['inventoryID'];
                    $id =  $_SESSION['item_id'];
                    $item_query ="SELECT *FROM item  NATURAL JOIN bi_has_i WHERE item_ID = $id AND inventory_ID = $inventoryID ";
                    $item_result = mysqli_query($conn,$item_query);
                    $item_Check = mysqli_num_rows($item_result);
            
                    if ($item_Check>0){
                        while ($item_row = mysqli_fetch_assoc($item_result)){
                            $item_Name = $item_row["item_Name"];
                            $item_RetailPrice = $item_row["item_RetailPrice"];
                            $item_WholesalePrice = $item_row["item_WholesalePrice"];
                            $item_Category = $item_row["item_Category"];
                            $item_Image = $item_row["item_Image"];
                            $item_Brand = $item_row["item_Brand"];
                        }
                    }else{
                        header('location: inventory.php');
                    }

             
               
            
            
            
            
            
    
        }
  

    
  


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

    <div class="form" >
       
        <div class="form-con shadow">
            <h4>Edit Item Form</h4>
            <hr>
            <form class="row g-3" action="editItem.php" method="post">
                <div class="col-12">
                    <label for="Item_Name" class="form-label">Item name</label>
                    <input type="text" class="form-control" name="Item_Name" 
                    value="<?php echo $item_Name?>"
                    >
                    
                </div>
                <div class="col-md-6">
                    <label for="Retail_Price" class="form-label">Retail Price</label>
                    <input type="number" class="form-control" name="Retail_Price" min=0  step="0.01" value="<?php echo $item_RetailPrice?>">
                </div>
                <div class="col-md-6">
                    <label for="Wholesale_Price" class="form-label">Wholesale Price</label>
                    <input type="number" class="form-control" name="Wholesale_Price"  min=0  step="0.01" value="<?php echo $item_WholesalePrice?>">
                </div>
                
                <div class="col-md-6">
                    <label for="Retail_Price" class="form-label">Category</label>
                    <input type="text" class="form-control" name="Category" value="<?php echo $item_Category?>">
                </div>
                <div class="col-md-6">
                    <label for="Brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" name="Brand" value="<?php echo $item_Brand?>">
                </div>

                <div class="col-md-6">
                    <label for="Image" class="form-label">Image</label>
                    <input type="text" class="form-control" name="Image" value="<?php echo $item_Image?>">
                </div>
  

                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="update" >UPDATE</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <?php

            if (isset($_POST['update'])) {
               
                $id =  $_SESSION['item_id'];
                $itemName =$_POST['Item_Name'];
                $RetailPrice = $_POST['Retail_Price'];
                $WholesalePrice = $_POST['Wholesale_Price'];
                $Category = $_POST['Category'];
                $Brand=$_POST['Brand'];
                $Image=$_POST['Image'];

            

            $update_query = "UPDATE item SET item_Name='$itemName',
            item_RetailPrice = $RetailPrice ,item_WholesalePrice =$WholesalePrice, item_Category='$Category', 
            item_Brand ='$Brand', 
            item_Image='$Image' 
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
  
</body>
</html>
