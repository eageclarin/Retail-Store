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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
 
    <?php include "./components/nav.html"?>

    <div class="container mt-5">
                <h1>Inventory</h1>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Edit
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            You are about to edit item
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger">Edit</button>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
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
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-danger">Edit</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>

             
    </div>
    

    <div class="container mt-5 bg-info p-2 text-dark bg-opacity-10" >
            <form class="d-flex container-sm mx-auto mt-3 mb-3"style="width: 50%;">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
            </form>
      
    </div>
    <div class="container bg-info p-2 text-dark bg-opacity-10" >
        put filter buttons here
    </div>

    <div class="container mt-5">
      
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
                                            <!-- Button trigger modal -->
                                            <button type="button" class="badge btn btn-primary editbtn" data-bs-toggle="modal"  data-bs-target="#editmodal">
                                           
                                            Edit
                                            </button>

                                            
                                            
                                             <!-- <form action="editItem.php" method="post">
                                                <div class="input-group mb-3" style="width:100%;">
                                                                               
                                                    <input type="number" style="display:none;" value="<?php echo $inventory_row["item_ID"]?>" name="Item_ID" >
                                                    <input type="number" style="display:none;" value="<?php echo $inventoryID?>" name="inventory_ID" >
                                                    <button class="badge btn btn-success text-light " name="editItem" type="submit" id="button-addon2" >EDIT</button>

                                                </div>
                                            </form> -->

                        

                                         
                                        </td>
                                        <td>
                                        <form action="UpdateStock.php" method="post">
                                                <div class="input-group mb-3" style="width:100%;">
                                                                               
                                                    <input type="number" style="display:none;" value="<?php echo $inventory_row["item_ID"]?>" name="Item_ID" >
                                                    <input type="number" style="display:none;" value="<?php echo $inventoryID?>" name="inventory_ID" >

                                                    <button class="badge btn btn-danger text-light " name="deleteStock" type="submit" id="button-addon2" >Delete</button>

                                                </div>
                                            </form>
                                            <!-- <button type="submit" class="btn btn-danger" name="Delete">
                                                <a class="text-light"href="delete.php?delete_item_id=<?php echo $inventory_row["item_ID"]?>&inventoryID=<?php echo $branchID ?>">DELETE</a>
                                            </button> -->
                                        </td>


                                        <td class="text-center">

                                            <form action="UpdateStock.php" method="post">
                                                <div class="input-group mb-3" style="width:100%;">
                                                
                                                    <input type="number" class="form-control "   name="Stock" min=1 value=0>

                                                    <input type="number" 
                                                    style="display:none;"
                                                    
                                                    value="<?php echo $inventory_row["item_ID"]?>" name="Item_ID" >
                                                    
                                                    <button class="btn btn-primary text-light " name="addStock" type="submit" id="button-addon2" >+</button>
                                                    <button class="btn btn-danger text-light " name="decreaseStock" type="submit" id="button-addon2" >-</button>
                                           
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    
    </script>
    <script>
        var myModal = document.getElementById('myModal')
        var myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', function () {
        myInput.focus()
        })
    </script>

<script>
    $(document).ready(function () {
        $(".editbtn").on('click',function(){
            console.log("Hello");
            $('#editmodal').modal('show');

                $tr = $(this).closest('tr');

                let data = $str.children("td").map(function(){
                    return $(this).text();
                }).get();

                console.log(data);

                // $('#').val(data[0]);
                // $('#').val(data[0]);
                // $('#').val(data[0]);
                // $('#').val(data[0]);
                // $('#').val(data[0]);
        });
       
    });
    </script>


  </body>
</html>