<?php
    include_once '../env/userConnection.php';
?>

<!DOCTYPE html>
<html>
<head>
<title> Order </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

    <body>
    
    <?php 
    #sample only
    //$_SESSION['CartID'] = 2;
        if (!empty($_SESSION['CustomerID'])&& !empty($_SESSION['CartID'])) {           #if login button is pressed
            $id = $_SESSION['CustomerID'];
            $cartID = $_SESSION['CartID'];
            
            $update = "UPDATE cu_orders_ca SET status=1 WHERE customer_ID ='$id' AND cart_ID='$cartID';";
            mysqli_query($conn, $update);

            #Display customer and order details ------------------------------------------------------------------------------
            $customer_order = "SELECT * FROM customer INNER JOIN cu_orders_ca ON (customer.cust_ID = cu_orders_ca.customer_ID) WHERE customer_ID = '$id' AND cart_ID = '$cartID';"; #check if in admin table
            $order_result = mysqli_query($conn,$customer_order);
            $order_Check = mysqli_num_rows($order_result);

            echo "<div class='container-sm p-5 my-3' style='max-width:50%;'>";
            echo "<h2 class='mb-3'> Receipt </h2>";
            if ($order_Check>0) {                                               #username and password in admin table
                while($order_row = mysqli_fetch_assoc($order_result)) {
                    echo "Name: " .$order_row['cust_FName'] .$order_row['cust_LName'];
                    echo '<div class="float-right"> Cart ID: ' .$order_row['cart_ID']. "</div>";   
                    echo "Address: " .$order_row['cust_ABrgy'].", " .$order_row['cust_ACity'].", " .$order_row['cust_AProvince'];
                    echo "<div class='float-right'> Date: " .$order_row['order_Date']. "</div>";
                }                    
            }
    
            echo '<div class="container-sm p-5 my-3 bg-dark text-white" >';
            #queries for items
            $item_query = "SELECT item_Name, item_RetailPrice, quantity, cai.total AS itemTotal, c.total AS totalPrice
                    FROM item i
                    INNER JOIN ca_contains_i cai ON (i.item_ID=cai.item_ID)
                    INNER JOIN cart c ON (cai.cart_ID=c.cart_ID)
                    WHERE c.cart_ID = '$cartID';";
            $item_result = mysqli_query($conn, $item_query);
            $item_check = mysqli_num_rows($item_result);
            $totalPrice = 0;
            if ($item_check>0) {  
                echo "<table class='table' style='color:white;'>";
                echo " <tr>
                <th class='mb-1 mt-1'> Item </th>
                <th class='mb-1 mt-1'> Unit Price </th>
                <th class='mb-1 mt-1'> Qty </th>
                <th class='mb-1 mt-1'> Total </th>
                </tr>" ;
        
                while($item_row = mysqli_fetch_assoc($item_result)) {
                    echo "<tr>";
                    echo "<td>" .$item_row['item_Name']. "</td>";
                    echo "<td>" .$item_row['item_RetailPrice']. "</td>";
                    echo "<td>" .$item_row['quantity']. "</td>";
                    echo "<td>" .$item_row['itemTotal']. "</td>"; #Note: I changed this attribute name in ca_contains_i
                    echo "</tr>";

                    $totalPrice = $item_row['totalPrice'];
                }

                echo "<tr> <td></td> <td></td> <td></td>";
                echo "<td><b>" .$totalPrice. "</b></td>";
                echo "</tr>";
                echo "</table>";
                unset($_SESSION['CartID']);
            }
            echo "</div>";

        }
       # echo "</div>";
        mysqli_close($conn);
    ?>

    <form action="../main.php?" method="post" class="form-inline">   
                <div class="mb-2 mt-2">
                    <input type="submit" value="Home" name="return" class="form-control" style="width:150px;">
                </div>
            </form>
    </div>   
    </body>
</html>