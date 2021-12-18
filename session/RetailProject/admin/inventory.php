<?php

    include '../env/connection.php';
    include '../env/adminAuth.php';
   

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
        
         <!-- ajax -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- jquery -->
        <script src="jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="script.js"></script>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Admin | Inventory</title>
        
        <script id="helper" data-name="<?php echo $_SESSION['admin_User'];?>" src="../env/idle.js"></script> 
    </head>
    
    <body>
        <?php include "./components/nav.php"?>

        <div class="container mt-5">
                <h1>Inventory</h1>    
        </div>

        <div class="container mt-5  p-2 text-dark bg-transparent" >
            <div class="row align-items-center">
                <div class="col">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#newItemModal">
                        New item
                    </button>
                </div>
                <div class="col">
               
                </div>
                <div class="col"> <!-- SEARCH -->
                    <form class="d-flex container-sm mx-auto mt-3 mb-3" method="post">
                        <input class="form-control me-2" type="text" placeholder="Search" aria-label="Search" name="search" id="search">
                        <input class="btn btn-primary" type="submit" name="searchSubmit" value="Search">
                        <input class="btn btn-primary" type="submit" name="searchAll" value="All">
                    </form>
                    <ul class="list-group" id="display"></ul>
                </div>
            </div>
        </div>

        <div class="container mt-2 bg-transparent ">
      
            <table class="table table-striped table-hover table-success ">
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
                        if (!isset($_POST['searchSubmit'])) {
                            $inventory_query = "SELECT item_Image,item_ID, item_Name, item_RetailPrice, item_WholesalePrice, item_Category, item_Brand, item_Stock FROM item NATURAL JOIN (bi_has_i) NATURAL JOIN branchinventory where inventory_id = '$branchID';"; 
                            
                        }else {
                            $itemName = $_POST['search']; 
                            //$inventory_query = "SELECT item_Image,item_ID, item_Name, item_RetailPrice, item_WholesalePrice, item_Category, item_Brand, item_Stock FROM item NATURAL JOIN (bi_has_i) NATURAL JOIN branchinventory where inventory_id = '$branchID' AND item_Name='$itemName';"; 
                            $inventory_query ="SELECT * FROM item NATURAL JOIN (bi_has_i) NATURAL JOIN branchinventory where inventory_id = '$branchID' AND item_Name LIKE '%$itemName%';";
                        }
                        if (isset($_POST['seachAll'])) {
                            unset($_POST['searchSubmit']);
                        }
                        
                        
                        
                        $inventory_result = mysqli_query($conn,$inventory_query);
                        $inventory_Check = mysqli_num_rows($inventory_result);
                    
                        if ($inventory_Check>0) {                                                       
                            while($inventory_row = mysqli_fetch_assoc($inventory_result)) {
                                echo "<tr class=\"fs-6\">"?>

                                <td><img src="<?php echo $inventory_row["item_Image"]?>"  style="width: 100px; height: 100px;"></img></td>
                                <?php echo "<td>" . $inventory_row["item_ID"] . "</td>
                                    <td>" . $inventory_row["item_Name"] . "</td>
                                    <td>" . $inventory_row["item_RetailPrice"]. "</td>
                                    <td>" . $inventory_row["item_WholesalePrice"]. "</td>
                                    <td>" . $inventory_row["item_Category"]. "</td>
                                    <td>" . $inventory_row["item_Brand"]."</td>
                                    <td>" . $inventory_row["item_Stock"]."</td>
                                    <td>";
                                    $id =  $inventory_row["item_ID"];
                                    ?>

                                        <!-- Button trigger modal -->
                                        <button type="button" class="badge btn btn-primary" onclick="getDetails( <?php  echo $id;?>)">Edit</button>
                                  
                                                                                                 
                                    </td>

                                    <td>
                                         <!-- Button trigger modal -->
                                         <button type="button" class="badge btn btn-danger" onclick="deleteInfo( <?php  echo $id;?>)">Delete</button>

                                            <!-- <form action="UpdateStock.php" method="post">
                                                    <div class="input-group mb-3" style="width:100%;">                                                                               
                                                        <input type="number" style="display:none;" value="<?php echo $inventory_row["item_ID"]?>" name="Item_ID" >
                                                        <input type="number" style="display:none;" value="<?php echo $inventoryID?>" name="inventory_ID" >
                                                        <button class="badge btn btn-danger text-light " name="deleteStock" type="submit" id="button-addon2" >Delete</button>
                                                    </div>
                                                </form> -->
                                                <!-- <button type="submit" class="btn btn-danger" name="Delete">
                                                    <a class="text-light"href="delete.php?delete_item_id=<?php echo $inventory_row["item_ID"]?>&inventoryID=<?php echo $branchID ?>">DELETE</a>
                                                </button> -->
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="badge btn btn-secondary" onclick="getInfo( <?php  echo $id ;?>)">Update Stock</button>
                                            </td >                                                                                                                                                                                       
                                        </tr>
                                        <?php
                                }
                            }                        
                        ?>
                </tbody>
            </table>
    
        </div>



    <!-- JavaScript Bundle with Popper -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>

    <script type="text/javascript">


        function getDetails(id){
           
            $.post("update.php",{updateId:id},function(data,status){
                var json=JSON.parse(data);
                $("#update_ID").val(json.item_ID);
                $("#updateItem_Name").val(json.item_Name);
                $('#updateRetail_Price').val(json.item_RetailPrice);
                $('#updateWholesale_Price').val(json.item_WholesalePrice);
                $('#update_Category').val(json.item_Category);
                $('#updateImage').val(json.item_Image);
                $('#updateBrand').val(json.item_Brand);
                // alert("Data: " + data );
              
            });
            $('#updateItemModal').modal('show');
        };

        function getInfo(itemId){
            $('#stockModal').modal('show');
           

            $.post("update.php",{itemId:itemId},function(data,status){
                var json=JSON.parse(data);
                $("#Item_ID").val(json.item_ID);
                $("#Inventory_ID").val(json.inventory_ID);
                // alert("Data: " + data );
              
            });
            
        };

        function deleteInfo(itemId){
          
            $('#deleteModal').modal('show');
           

            $.post("update.php",{itemId:itemId},function(data,status){
                var json=JSON.parse(data);
                $("#delItem_ID").val(json.item_ID);
                $("#delInventory_ID").val(json.inventory_ID);
                // alert("Data: " + data );
              
            });
            
        };




    </script>




    <?php
    if($_SESSION['confirm_err']==1){
        echo '<script>
        setTimeout(function(){  $(\'#passErr\').modal("show"); }, 500);
        </script>';
        $_SESSION['confirm_err']=0;
    }
    if($_SESSION['confirm_err']==2){
        echo '<script>
        setTimeout(function(){  $(\'#successModal\').modal("show"); }, 500);
        </script>';
        $_SESSION['confirm_err']=0;
    }
    ?>
    <!-- password Error Modal -->
    <div class="modal fade" id="passErr" tabindex="-1" aria-labelledby="passErrLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passErrLabel">Password Incorrect</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                You have Entered a wrong password
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="passErrLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passErrLabel">Update Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                The database was successfully updated
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
            </div>
        </div>
    </div>

    <!-- Update Item Modal ##################################-->
    <div class="modal fade" id="updateItemModal" tabindex="-1" aria-labelledby="updateItemModalLabel"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateItemModalLabel">Update Item Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-3"  action="editItem.php" method="post">
                        <div class="col-12">
                            <label for="updateItem_Name" class="form-label">Item name</label>
                            <input type="text" class="form-control" id="updateItem_Name" name="updateItem_Name">
                        </div>

                        <input type="hidden" class="form-control" id="update_ID" name="updateItem_ID">

                        <div class="col-md-6">
                            <label for="updateRetail_Price" class="form-label">Retail Price</label>
                            <input type="number" class="form-control" id="updateRetail_Price"   name="updateRetail_Price"min=0  step="0.01" value="">
                        </div>

                        <div class="col-md-6">
                            <label for="updateWholesale_Price" class="form-label">Wholesale Price</label>
                            <input type="number" class="form-control" id="updateWholesale_Price"  name="updateWholesale_Price" min=0  step="0.01" value="">
                        </div>
                                                                    
                        <div class="col-md-6">
                            <label for="update_Category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="update_Category" name="update_Category">
                        </div>

                        <div class="col-md-6">
                            <label for="updateBrand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="updateBrand" name="updateBrand" >
                        </div>
                                                    
                        <div class="col-md-6">
                            <label for="updateImage" class="form-label">Image</label>
                            <input type="text" class="form-control" id="updateImage"  name="updateImage">
                        </div>

                        <div class="col-md-6">
                            <label for="adminPass" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" name="AdminPass" required>
                        </div>

                                                            
                                                    
                                                    
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" name="onclickUpdate" >UPDATE</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                                               
                </div>
            </div>
        </div>
    </div>


    <!-- Update Stock Modal ##################################-->
    <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockModalLabel">Update Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <form class="row g-3" action="UpdateStock.php" method="post">

                        

                        <div class="col-md-6">
                            <label for="itemStock" class="form-label">Quantity</label>
                            <input type="number" class="form-control "   id="itemStock" name="itemStock" min=1 value=0 required>
                        </div>
                                                       
                        <input type="hidden" id="Item_ID" name="Item_ID">

                        <input type="hidden" id="Inventory_ID" name="Inventory_ID">

                        <div class="col-md-6">
                            <label for="adminPass" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" name="AdminPass" required>
                        </div>
                        <div class="col-12">
                        <button class="btn btn-primary text-light " name="addStock" type="submit" id="button-addon2" >Increase Stock</button>
                        
                        </div>

                        <div class="col-12">
                        <button class="btn btn-danger text-light " name="decreaseStock" type="submit" id="button-addon2" >Decrease Stock</button>                                          
                        </div>
                                                                                  
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                                               
                </div>
            </div>
        </div>
    </div>     

    <!-- delete Stock Modal ##################################-->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <form class="row g-3" action="deleteStock.php" method="post">   
                                                          
                        <input type="hidden" id="delItem_ID" name="delItem_ID" >

                        <input type="hidden" id="delInventory_ID" name="delInventory_ID">

                        <div class="col-md-12">
                            <label for="deleteAdminPass" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" name="deleteAdminPass" required>
                        </div>
                        
                        <div class="col-12">
                        <button class="btn btn-danger text-light " name="deleteItem" type="submit" >Delete</button>                                          
                        </div>

                                           
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                                               
                </div>
            </div>
        </div>
    </div>
 <!-- New Item Modal ##################################-->
 <div class="modal fade" id="newItemModal" tabindex="-1" aria-labelledby="newItemModalLabel"  aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newItemModalLabel">New Item Form</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="row g-3 pb-4" action="addItem.php" method="post">
                                    <div class="col-12">
                                        <label for="ItemName" class="form-label">Item name</label>
                                        <input type="text" class="form-control" name="ItemName" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Retail_Price" class="form-label">Retail Price</label>
                                        <input type="number" class="form-control" name="RetailPrice" min=0 required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Wholesale_Price" class="form-label">Wholesale Price</label>
                                        <input type="number" class="form-control" name="WholesalePrice"  min=0 required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="Retail_Price" class="form-label">Category</label>
                                        <input type="text" class="form-control" name="Category" min=0 step=0.001 required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Brand" class="form-label">Brand</label>
                                        <input type="text" class="form-control" name="Brand" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="Image" class="form-label">Image</label>
                                        <input type="text" class="form-control" name="Image" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Stock" class="form-label">Stock</label>
                                        <input type="number" class="form-control" name="Stock"  min=0 required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="adminPass" class="form-label">Admin Password</label>
                                        <input type="password" class="form-control" name="AdminPass" required>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-dark" name="Add">Submit</button>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>          
                           

  </body>
</html>