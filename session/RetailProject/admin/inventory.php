<?php

    include_once '../env/connection.php';
    include_once '../env/adminAuth.php';
   

    $user = $_SESSION['admin_User'] ;
    $branchID_query = "SELECT *FROM branch NATURAL JOIN (b_has_bi) NATURAL JOIN branchinventory NATURAL JOIN a_manages_b NATURAL JOIN admin WHERE admin.admin_Username= '$user' ;"; #check if in admin table
    $branchID_result = mysqli_query($conn,$branchID_query);
    $branchID_Check = mysqli_num_rows($branchID_result); #should be same with eigram

    if ($branchID_Check>0) {                                               #username and password in admin table
        while($branchID_row = mysqli_fetch_assoc($branchID_result)) {
            $_SESSION['branchID'] = $branchID_row['branch_ID'];                #store in $_SESSION for referencing later
            $_SESSION['inventoryID'] = $branchID_row['inventory_ID']; 
           
        }                    
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./components/admin.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Admin</title>
</head>
<body >
<div class="body">
        <?php include "./components/header.html"?>
        <?php include "./components/nav.html"?>

        <div class="item-display">
            <table class="table table-striped table-hover table-success">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Item ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Retail Price</th>
                        <th scope="col">Wholesale Price</th>
                        <th scope="col">Category</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                        <th scope="col">Add Stock</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $branchID = $_SESSION['branchID'] ;
                    $inventoryID=$_SESSION['inventoryID'];
                    $inventory_query = "SELECT item_Image,item_ID, item_Name, item_RetailPrice, item_WholesalePrice, item_Category, item_Brand, item_Stock FROM item NATURAL JOIN (bi_has_i) NATURAL JOIN branchinventory where inventory_id = '$branchID';"; 
                    $inventory_result = mysqli_query($conn,$inventory_query);
                    $inventory_Check = mysqli_num_rows($inventory_result);
                   
                        if ($inventory_Check>0) {                                                       
                            while($inventory_row = mysqli_fetch_assoc($inventory_result)) {
                                echo "<tr class=\"fs-6\">"?>

                                        <td><img src="<?php echo $inventory_row["item_Image"]?>"  style="width: 80%;"></td>
                                        <?php echo "<th>" . $inventory_row["item_ID"] . "</th>
                                        <td>" . $inventory_row["item_Name"] . "</td>
                                        <td>" . $inventory_row["item_RetailPrice"]. "</td>
                                        <td>" . $inventory_row["item_WholesalePrice"]. "</td>
                                        <td>" . $inventory_row["item_Category"]. "</td>
                                        <td>" . $inventory_row["item_Brand"]."</td>
                                        <td>" . $inventory_row["item_Stock"]."</td>
                                        <td>"?>       
                                            <button type="submit" class="btn btn-success" name="edit">
                                                <a class="text-light"href="editItem.php?edit_item_id=<?php echo $inventory_row["item_ID"]; ?>">EDIT</a>
                                            </button>

                                         
                                        </td>
                                        <td>

                                            <button type="submit" class="btn btn-danger" name="Delete">
                                                <a class="text-light"href="delete.php?delete_item_id=<?php echo $inventory_row["item_ID"]?>">DELETE</a>
                                            </button>
                                        </td>


                                        <td class="text-center">

                                            <form action="addStock.php" method="post">
                                                <div class="input-group mb-3" style="width:100%;">
                                                
                                                    <input type="number" class="form-control "   name="Stock" min=1 value=0>

                                                    <input type="number" 
                                                    style="display:none;"
                                                    
                                                    value="<?php echo $inventory_row["item_ID"]?>" name="Item_ID" >
                                                    
                                                    <button class="btn btn-primary text-light " name="addStock" type="submit" id="button-addon2" >ADD</button>
                                           
                                                </div>
                                            </form>
                                        </td >
                                            
                                      
                                            
                                      
                                   
                                    </tr>
                                    <?php
                            }
                        } 

                     

                        
                        
                      
                        
                    ?>
                </tbody>
            </table>
        </div>

       
    </div>
</body>
</html>