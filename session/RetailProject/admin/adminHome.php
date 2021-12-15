<?php

include_once '../env/connection.php';
include_once '../env/adminAuth.php';


$user = $_SESSION['admin_User'] ;
$branchID_query = "SELECT * FROM branch NATURAL JOIN (b_has_bi) NATURAL JOIN branchinventory NATURAL JOIN a_manages_b NATURAL JOIN admin WHERE admin.admin_Username= '$user' ;"; #check if in admin table
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

    <title>Admin Home</title>
    <script id="helper" data-name="<?php echo $_SESSION['admin_User'];?>" src="../env/idle.js"></script> 
  </head>
  <body>
 
  <?php include "./components/nav.php"?>
    
    <div class="container-fluid     mt-4 pt-4 pb-4 ps-4 pe-4">
        <div class="row align-items-start">
          <div class="col ">
            
          </div>
          <div class="col  bg-danger ms-2 me-2 p-2 text-dark bg-opacity-50 rounded">
            <h1 class="text-center">Low on Stocks<h1>

            <table class="table table-striped table-hover fs-6  fw-normal">
                <thead>
                    <tr>
                        <th scope="col">Item Name</th>
                        <th scope="col">Stock</th>
            

                        
                    </tr>
                </thead>
                <tbody>
            <?php
                
                       
                         $inventoryID=$_SESSION['inventoryID'];
                         $inventory_query = "SELECT  item_Name,item_Stock FROM item NATURAL JOIN (bi_has_i) NATURAL JOIN branchinventory where inventory_id =$inventoryID and item_Stock<500 "; 
                         $inventory_result = mysqli_query($conn,$inventory_query);
                         $inventory_Check = mysqli_num_rows($inventory_result);
                      
                            if ( $inventory_Check>0) {                                                       
                                while($inventory_row = mysqli_fetch_assoc($inventory_result)) {
                                  echo"<tr>
                                  <td>".$inventory_row['item_Name']."</td>
                                  <td>". $inventory_row['item_Stock'] ." </td>                           
                                  </tr>";
                          
                                }
                            } 

                        

                            
                            
                          
                            
                        ?>
                         </tbody>
            </table>  

          </div>
          <div class="col ">
            <div class="row align-items-center bg-info p-2 text-dark bg-opacity-50  text-center rounded">

            <div class="col   ">
                <h1 > Total Sales<h1>
                <?php
                        $branchID = $_SESSION['branchID'] ;
                        $orders_query = "SELECT sum(total) AS sales FROM customer NATURAL join cu_orders_ca  NATURAL join cart where cu_orders_ca.status=1 AND customer.cust_ID=cu_orders_ca.customer_ID AND branch_ID=$branchID;"; 
                        $orders_result = mysqli_query($conn,$orders_query);
                        $orders_Check = mysqli_num_rows($orders_result);
                      
                            if ($orders_Check>0) {                                                       
                                while($orders_row = mysqli_fetch_assoc($orders_result)) {
                                  if($orders_row['sales'] !=null){

                                  
                                  ?>  
                                  <p class="display-4 "><?php echo $orders_row['sales']  ?>  </p> 
                                  <?php
                                }else{
                                  ?>  
                                  <p class="display-4 ">0</p> 
                                  <?php
                                }
                                }
                            }

                        

                            
                            
                          
                            
                        ?>


            </div>
            <div class="col">   
                <h1>Total Orders</h1>
                <?php
                        $branchID = $_SESSION['branchID'] ;
                        $orders_query = "SELECT COUNT(cart_ID) FROM customer NATURAL join cu_orders_ca NATURAL join cart where cu_orders_ca.status=1 AND customer.cust_ID=cu_orders_ca.customer_ID AND branch_ID=$branchID;"; 
                        $orders_result = mysqli_query($conn,$orders_query);
                        $orders_Check = mysqli_num_rows($orders_result);
                      
                            if ($orders_Check>0) {                                                       
                                while($orders_row = mysqli_fetch_assoc($orders_result)) {
                                  ?>  
                                  <p class="display-4"><?php echo $orders_row['COUNT(cart_ID)']  ?>  </p> 


                                
                                  <?php
                                }
                            } 

                        

                            
                            
                          
                            
                        ?>

            </div>
          </div> 
          </div>
      </div>
    </div>
  
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


  </body>
</html>