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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Admin | Summary</title>
    <script src="../env/idle.js"></script>
  </head>
  <body>
        <?php include "./components/nav.php"?>

      
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
            </li>
        </ul>
      

        <div class="container mt-5">
            <table class="table table-striped table-hover table-success">
                <thead>
                    <tr>
                        <th scope="col">Cart ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Items</th>
                        <th scope="col">Total</th>
                        <th scope="col">Date</th>
                        <th scope="col">Address</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>

                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $branchID = $_SESSION['branchID'] ;
                    if($_SESSION['admin']==1){ 
                        $orders_query = "SELECT * FROM customer NATURAL join cu_orders_ca NATURAL join cart ; ";
                    }else{
                        $orders_query = "SELECT * FROM customer NATURAL join cu_orders_ca NATURAL join cart where cu_orders_ca.status=1 AND customer.cust_ID=cu_orders_ca.customer_ID AND branch_ID=$branchID"; 
                    }
                    $status = array("", "Pending", "Delivered","Cancelled");
                    $orders_result = mysqli_query($conn,$orders_query);
                    $orders_Check = mysqli_num_rows($orders_result);
                   
                        if ($orders_Check>0) {                                                       
                            while($orders_row = mysqli_fetch_assoc($orders_result)) {
                               echo"<tr>
                                    <td>".$orders_row['cart_ID']."</td>
                                    <td>". $orders_row['cust_FName'] ." ".$orders_row['cust_LName'] ."</td>
                                    <td>"; ?> <button type="button" class="badge btn btn-secondary" onclick="showDetails(<?php  echo $orders_row['cart_ID'] ;?>)" >Show Items</button></td>
                                     <?php echo "
                                    <td>" .  $orders_row['total']."</td>
                                    <td>". $orders_row['order_Date'] ."</td>
                                    <td>". $orders_row['cust_ABrgy'] .", ".$orders_row['cust_ACity'] .", ".$orders_row['cust_AProvince'] .", ".$orders_row['cust_APostal'] ."</td>
                                    <td> " . $status[ $orders_row['status']]."</td>
                                    <td>"?> <button type="button" class="badge btn btn-primary" onclick="orderStatus( <?php  echo $orders_row['cart_ID'];?>)">Update</button> <?php "</td>
                                    </tr>";
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

function showDetails(cartID){

        $.post("displayItems.php",{cartID:cartID},function(data,status){
            var json=JSON.parse(data);
            let cleanJSON = json;
            document.getElementById("demo").innerHTML = cleanJSON.map(getItem).join("");
            function getItem(item) {
            return "<tr><td>"+ item.item_ID + "</td><td>"+ item.item_Name + "</td><td>"+ item.item_RetailPrice + "</td><td>"+ item.quantity + "</td></tr>";
            }
            // document.getElementById("demo").innerHTML = myJSON;            
        });

        $('#showItems').modal('show');
        };

        function orderStatus(cartID){
          
          $('#pendingActionModal').modal('show');
         

          $.post("update.php",{pendingId:cartID},function(data,status){
              var json=JSON.parse(data);
              $("#pendingCart_ID").val(json.cart_ID);
            
          });
          
      };



    </script>
    <!-- show items modal ##################################-->
    <div class="modal fade" id="showItems" tabindex="-1" aria-labelledby="showItemsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showItemsLabel">Cart Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover ">
                    <thead>
                        <tr>
                            <th scope="col">Item ID</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
  
                        </tr>
                    </thead>
                    <tbody id="demo" >
                      

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
            </div>
        </div>
    </div>

    <!--Order.pending action Modal ##################################-->
    <div class="modal fade" id="pendingActionModal" tabindex="-1" aria-labelledby="pendingActionModalLabel"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateOrderModalLabel">Delete Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <form class="row g-3" action="order.update.php" method="post">   
                        <input type="text" id="pendingCart_ID" name="pendingCart_ID" > 

                        <select class="form-select text-center bg-primary bg-opacity-25" aria-label="Default select example" name="status" required>
                            <option selected>Select Status</option>
                            <option value="1">Delivered</option>
                            <option value="2">Cancelled</option>
                        </select>

                        <div class="col-md-12">
                                        <label for="adminPass" class="form-label">Admin Password</label>
                                        <input type="password" class="form-control" name="AdminPass" required>
                        </div>
                        
                        <div class="col-12">
                            <button class="btn btn-primary text-light " name="orderUpdate" type="submit" >Update</button>                                          
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