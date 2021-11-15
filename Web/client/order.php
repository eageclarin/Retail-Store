<?php
    include_once '../env/connection.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

    <body>
    
    <?php 
    #sample only
    $_SESSION['CartID'] = 2;
        if (!empty($_SESSION['CustomerID'])&& !empty($_SESSION['CartID'])) {           #if login button is pressed
            $id = $_SESSION['CustomerID'];
            $cartID = $_SESSION['CartID'];
            
            $update = "UPDATE cu_orders_ca SET status='1' WHERE customer_ID ='$id' AND cart_ID='$cartID';";
            mysqli_query($conn, $update);

            #Display customer and order details ------------------------------------------------------------------------------
            $customer_order = "SELECT * FROM customer INNER JOIN cu_orders_ca ON (customer.cust_ID = cu_orders_ca.customer_ID) WHERE customer_ID = '$id' AND cart_ID = '$cartID';"; #check if in admin table
            $order_result = mysqli_query($conn,$customer_order);
            $order_Check = mysqli_num_rows($order_result);

            if ($order_Check>0) {                                               #username and password in admin table
                while($order_row = mysqli_fetch_assoc($order_result)) {
                    echo "customer ID: " .$order_row['customer_ID']. "</br>";
                    echo "cart ID: " .$order_row['cart_ID']. "</br>";   
                    echo "status: " .$order_row['status']. "</br>";                #redirect to adminHome.php
                }                    
            }

            #queries for items
            $item_query = "SELECT * FROM item INNER JOIN ca_contains_i ON (item.item_ID=ca_contains_i.item_ID) INNER JOIN cart ON(ca_contains_i.cart_ID=cart.cart_ID) WHERE cart.cart_ID = '$cartID';";
            $item_result = mysqli_query($conn, $item_query);
            $item_check = mysqli_num_rows($item_result);
            if ($item_check>0) {                                               #username and password in admin table
                while($item_row = mysqli_fetch_assoc($item_result)) {
                    echo "Item: " .$item_row['item_Name']. "</br>";
                }      
                              
            }


        }
        mysqli_close($conn);
    ?>

<form action="../main.php" method="post" class="form-inline">   
                <div class="mb-2 mt-2">
                    <input type="submit" value="Home" name="return" class="form-control" style="width:150px;">
                </div>
            </form>

    </body>
</html>