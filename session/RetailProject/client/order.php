<?php
   require '../env/userConnection.php';

   if(isset($_SESSION)) {
    $name = $_SESSION['username'];
    $id = $_SESSION['userID'];

    $title = $name." | Receipt";
}
?>

<!DOCTYPE html>
<html>
<head>
<title> <?php echo $title ?> </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            height: 115%;
            width: 100%;
            background: rgb(196,53,49);
            background: linear-gradient(144deg, rgba(196,53,49,1) 0%, rgba(218,55,50,1) 26%, rgba(228,123,120,1) 78%);
        }
    </style>
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

    ?>
            <!-- Registration form -->
            <section class="vh-100 gradient-custom">
                <div class="container py-5 h-100">
                    <div class="row justify-content-center align-items-center h-100">
                        <div class="col-12 col-lg-9 col-xl-7">
                            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">

    <?php
            if ($order_Check>0) {                                               #username and password in admin table
                while($order_row = mysqli_fetch_assoc($order_result)) {
                    $fName = $order_row['cust_FName'];
                    $lName = $order_row['cust_LName'];
                    $cartID = $order_row['cart_ID'];
                    $address = $order_row['cust_ABrgy'].", " .$order_row['cust_ACity'].", " .$order_row['cust_AProvince'];
                    $date = $order_row['order_Date'];
    ?>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <img src="../img/logo.png" width="50%">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                    <h3><p class="text-end">Receipt</p></h3>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        Name: <?php echo $fName .$lName ?>
                                    </div>
                                    <div class="col-md-6">
                                        Cart ID:<?php echo $cartID ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        Address:<?php echo $address ?>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        Date:<?php echo $date ?>
                                    </div>
                                </div>
    <?php
                }                    
            }
            #queries for items
            $item_query = "SELECT item_Name, item_Weight, item_RetailPrice, quantity, cai.total AS itemTotal, c.total AS totalPrice
                    FROM item i
                    INNER JOIN ca_contains_i cai ON (i.item_ID=cai.item_ID)
                    INNER JOIN cart c ON (cai.cart_ID=c.cart_ID)
                    WHERE c.cart_ID = '$cartID';";
            $item_result = mysqli_query($conn, $item_query);
            $item_check = mysqli_num_rows($item_result);
            $totalPrice = 0;
            if ($item_check>0) {  
    ?>
                <div class="row">
                    <div class="col-md-4 border-top border-bottom"> <strong>Item</strong></div>
                    <div class="col-md-2 border-top border-bottom"> </div>
                    <div class="col-md-2 border-top border-bottom"> <strong>Unit Price</strong> </div>
                    <div class="col-md-2 border-top border-bottom"> <strong>Qty</strong> </div>
                    <div class="col-md-2 border-top border-bottom"> <strong>Total</strong> </div>
                </div>
    <?php
                while($item_row = mysqli_fetch_assoc($item_result)) {
                    $itemName = $item_row['item_Name'];
                    $itemWt = $item_row['item_Weight'];
                    $itemPrice = $item_row['item_RetailPrice'];
                    $itemQty = $item_row['quantity'];
                    $itemTotal = $item_row['itemTotal'];
                    $totalPrice = $item_row['totalPrice'];
    ?>
                <div class="row">
                    <div class="col-md-4 border-bottom"><?php echo $itemName ?></div>
                    <div class="col-md-2 border-bottom"><?php echo $itemWt ?></div>
                    <div class="col-md-2 border-bottom"><?php echo $itemPrice ?></div>
                    <div class="col-md-2 border-bottom"><?php echo $itemQty ?></div>
                    <div class="col-md-2 border-bottom"><?php echo $itemTotal ?></div>
                </div>
    <?php
                }
    ?>
                <div class="row mb-3">
                    <div class="col-md-4"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2"><strong>Total</strong></div>
                    <div class="col-md-2"><strong><?php echo $totalPrice ?><strong></div>
                </div>
    <?php
                unset($_SESSION['CartID']);
            }
    ?>
                <form action="../main.php?" method="post" class="form-inline mb-0">   
                    <div class="row mt-5">
                        <div class="col-md-12 text-end">
                            <input type="submit" value="Home" name="return" class="form-control btn border-primary text-primary" style="width:150px;">
                        </div>
                    </div>
                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
        }
        mysqli_close($conn);
    ?>

    
    </div>   
    </body>
</html>