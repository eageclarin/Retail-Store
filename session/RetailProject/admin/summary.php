<?php

include_once '../env/connection.php';
include_once '../env/adminAuth.php';

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
        
        <div class="item-display">
            <table class="table table-striped table-hover table-success">
                <thead>
                    <tr>
                        <th scope="col">Cart ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Items</th>
                        <th scope="col">Total</th>
                        <th scope="col">Date</th>
                        <th scope="col">Address</th>

                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $branchID = $_SESSION['branchID'] ;
                    $orders_query = "SELECT * FROM customer NATURAL join cu_orders_ca where status=1 and branch_ID=$branchID"; 
                    $orders_result = mysqli_query($conn,$orders_query);
                    $orders_Check = mysqli_num_rows($orders_result);
                   
                        if ($orders_Check>0) {                                                       
                            while($orders_row = mysqli_fetch_assoc($orders_result)) {
                               echo"<tr>
                                    <td>".$orders_row['cart_ID']."</td>
                                    <td>". $orders_row['cust_FName'] ." ".$orders_row['cust_LName'] ."</td>
                                    <td></td>
                                    <td></td>
                                    <td>". $orders_row['order_Date'] ."</td>
                                    <td>". $orders_row['cust_ABrgy'] .", ".$orders_row['cust_ACity'] .", ".$orders_row['cust_AProvince'] .", ".$orders_row['cust_APostal'] ."</td>
                                    </tr>";
                            }
                        } 

                     

                        
                        
                      
                        
                    ?>
                </tbody>
            </table>
        </div>
       
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


  </body>
</html>