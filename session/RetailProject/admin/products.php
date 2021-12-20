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

              
        <!-- Search bar-->
        <script type="text/javascript" src="script.branchInventory.js"></script>

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
                <h1>Products</h1>    
        </div>

        <div class="container mt-5  p-2 text-dark bg-transparent" >
            <div class="row align-items-center">
            <?php if($_SESSION['admin']==1){  ?>
                <div class="col">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#newItemModal">
                        New item
                    </button>
                </div>
            <?php } ?>
                <div class="col">
               
                </div>

                <div class="col"> <!-- SEARCH BAR-->
                    <div class="searchBar"> <!-- class here not defined yet-->
                        <form class="d-flex container-sm mx-auto mt-3 mb-3" method="post" style="float: right;">
                            <ul class="list-group">    
                                <div  data-bs-toggle="dropdown" >
                                    <input class="form-control me-2" type="text" placeholder="Search" aria-label="Search" name="search" id="search" style="width:400px; margin-bottom: 0px;">
                                </div>
                                <div class="dropdown-menu" id="display" style="width:400px; cursor:pointer;" ></div>
                            </ul>  
                            <input class="btn btn-primary" type="submit" name="searchSubmit" value="Search" style="height: 40px; float: right; padding: 5px; margin: 5px;">
                            <input class="btn btn-primary" type="submit" name="searchAll" value="All" style="height: 40px; width: 40px; float: right; padding: 5px; margin: 5px;">       
                        </form> 
                    </div> <!-- end of class="searchBar"-->
                </div> <!-- end of class="col"-->
            </div>
        </div>
       
        <div class="container mt-2 bg-transparent ">
      
            <table class="table table-striped table-hover table-success ">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Item ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Weight/Volume</th>
                        <th scope="col">Retail Price</th>
                        <th scope="col">Wholesale Price</th>
                        <th scope="col">Category</th>
                        <th scope="col">Brand</th>
                        <?php 

                        if($_SESSION['admin']==1){  ?>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                        <?php
                        }else{  ?>

                        <th scope="col">Action</th>
                        <?php 
                        }
                        ?>
                                
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $branchID = $_SESSION['branchID'] ;
                        $inventoryID=$_SESSION['inventoryID'];

                        if (!isset($_POST['searchSubmit'])) {
                            $products_query = "SELECT * from item where item.item_Status=0;"; 
                        }else { // if search is clicked, show search items
                            $itemName = $_POST['search']; 
                            $products_query ="SELECT * FROM item where item_Name LIKE '%$itemName%';";
                        }
                        if (isset($_POST['seachAll'])) { //if "all" is clicked, show all items
                            unset($_POST['searchSubmit']);
                        }

                        $products_result = mysqli_query($conn,$products_query);
                        $products_Check = mysqli_num_rows($products_result);
                    
                        if ($products_Check>0) {                                                       
                            while($row = mysqli_fetch_assoc($products_result)) {
                                echo "<tr class=\"fs-6\">"?>

                                <td><img src="<?php echo $row["item_Image"]?>"  style="width: 100px; height: 100px;"></img></td>
                                <?php echo "<td>" . $row["item_ID"] . "</td>
                                    <td>" . $row["item_Name"] . "</td>
                                    <td>" . $row["item_Weight"]. "</td>
                                    <td>" . $row["item_RetailPrice"]. "</td>
                                    <td>" . $row["item_WholesalePrice"]. "</td>
                                    <td>" . $row["item_Category"]. "</td>
                                    <td>" . $row["item_Brand"]."</td>
                                  
                                   ";
                                    $id =  $row["item_ID"];
                                    if($_SESSION['admin']==1){
                                    ?>
                                    <td>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="badge btn btn-primary" onclick="getDetails( <?php  echo $id;?>)">Edit</button>
                                  
                                                                                                 
                                    </td>

                                    <td>
                                         <!-- Button trigger modal -->
                                         <button type="button" class="badge btn btn-danger" onclick="deleteInfo( <?php  echo $id;?>)">Delete</button>

                                            <!-- <form action="UpdateStock.php" method="post">
                                                    <div class="input-group mb-3" style="width:100%;">                                                                               
                                                        <input type="number" style="display:none;" value="<?php echo $row["item_ID"]?>" name="Item_ID" >
                                                        <input type="number" style="display:none;" value="<?php echo $inventoryID?>" name="inventory_ID" >
                                                        <button class="badge btn btn-danger text-light " name="deleteStock" type="submit" id="button-addon2" >Delete</button>
                                                    </div>
                                                </form> -->
                                                <!-- <button type="submit" class="btn btn-danger" name="Delete">
                                                    <a class="text-light"href="delete.php?delete_item_id=<?php echo $row["item_ID"]?>&inventoryID=<?php echo $branchID ?>">DELETE</a>
                                                </button> -->
                                            </td>
                                                                                                                                                                                                                                 
                                        
                                        <?php
                                    }else{
                                        ?>
                                        <td>
                                        <button type="button" class="badge btn btn-secondary" onclick="getInfo( <?php  echo $id ;?>)">Order</button>
                                    </td>
                                        </tr>
                                      <?php
                                    }
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

        //for update products
        function getDetails(id){
           
            $.post("update.php",{updateId:id},function(data,status){
                var json=JSON.parse(data);
                $("#update_ID").val(json.item_ID);
                $("#updateItem_Name").val(json.item_Name);
                $("#updateItemWeight").val(json.item_Weight);
                $('#updateRetail_Price').val(json.item_RetailPrice);
                $('#updateWholesale_Price').val(json.item_WholesalePrice);
                $('#update_Category').val(json.item_Category);
                $('#updateImage').val(json.item_Image);
                $('#updateBrand').val(json.item_Brand);
                // alert("Data: " + data );
              
            });
            $('#updateItemModal').modal('show');
        };

        //to delete products
        function deleteInfo(itemId){
          
            $('#deleteModal').modal('show');
           

            $.post("update.php",{productId:itemId},function(data,status){
                var json=JSON.parse(data);
                $("#delItem_ID").val(json.item_ID);
             
                // alert("Data: " + data );
              
            });
            
        };
        //to order products
        function getInfo(itemId){
            $('#stockModal').modal('show');
        //    alert("Data: " + itemId);

            $.post("update.php",{productID:itemId},function(data,status){
                var json=JSON.parse(data);
                $("#Item_ID").val(json.item_ID);
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
    if($_SESSION['confirm_err']==3){
        echo '<script>
        setTimeout(function(){  $(\'#unableModal\').modal("show"); }, 500);
        </script>';
        $_SESSION['confirm_err']=0;
    }
    ?>

    <!-- Unable to perform Error Modal -->
    <div class="modal fade" id="unableModal" tabindex="-1" aria-labelledby="passErrLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passErrLabel">Cannot Perform Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                Item still exist on other branch/es. 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
            </div>
        </div>
    </div>
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
                    <form class="row g-3"  action="products.edit.php" method="post">
                        <div class="col-12">
                            <label for="updateItem_Name" class="form-label">Item name</label>
                            <input type="text" class="form-control" id="updateItem_Name" name="updateItem_Name">
                        </div>

                        <div class="col-6">
                            <label for="updateItemWeight" class="form-label">Weight/Volume</label>
                            <input type="text" class="form-control" name="updateItemWeight"id="updateItemWeight"  required>
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


    <!-- delete Stock Modal ##################################-->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <form class="row g-3" action="products.delete.php" method="post">   
                                                          
                        <input type="hidden" id="delItem_ID" name="delItem_ID" >

                

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
                                    <div class="col-6">
                                        <label for="ItemWeight" class="form-label">Weight/Volume</label>
                                        <input type="text" class="form-control" name="ItemWeight" required>
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
                           
    <!-- Order Modal ##################################-->
    <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockModalLabel">Order Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <form class="row g-3" action="products.order.php" method="post">

                        

                        <div class="col-md-6">
                            <label for="itemStock" class="form-label">Quantity</label>
                            <input type="number" class="form-control " id="itemStock" name="itemStock" min=1 value=0 required>
                        </div>
                                                       
                        <input type="text" id="Item_ID" name="Item_ID" >

                        <input type="text" id="Inventory_ID" name="Inventory_ID" value=" <?php echo $_SESSION['inventoryID'] ?>" >

                        <div class="col-md-6">
                            <label for="adminPass" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" name="AdminPass" required>
                        </div>
                        <div class="col-12">
                        <button class="btn btn-primary text-light " name="orderProduct" type="submit" id="button-addon2" >Order</button>
                        
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