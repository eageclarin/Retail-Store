<?php
   require '../env/userConnection.php';
    
    $id = $_SESSION['userID'];
    $name = $_SESSION['username'];
    $chosenBranch = $_SESSION['branch'];
    //query customer details
    $cust_query ="SELECT *FROM customer WHERE cust_ID = $id";
    $cust_result = mysqli_query($conn,$cust_query);
    $cust_Check = mysqli_num_rows($cust_result);
            
    if ($cust_Check>0){
        while ($cust_row = mysqli_fetch_assoc($cust_result)){
            $username = $cust_row['cust_Username'];
            $password = $cust_row['cust_Password'];
            $firstName = $cust_row['cust_FName'];
            $lastName = $cust_row['cust_LName'];
            $contact = $cust_row['cust_Contact'];
            $email = $cust_row['cust_Email'];
            $brgy = $cust_row['cust_ABrgy'];
            $city = $cust_row['cust_ACity'];
            $province = $cust_row['cust_AProvince'];
            $postal = $cust_row['cust_APostal'];
        }
     }else{
            echo "No Orders Yet";
    }   
    

?>

<!DOCTYPE html>
<html>
<head>
<title> Report </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
 
</head>

<body style="background-color:#E6E9F0;" >
    <header class="shadow p-3 mb-0 border-bottom bg-white h-20">
        <div class="container-fluid d-grid gap-3 align-items-center">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="../img/logo.jpg" height="50" role="img" />
                <!-- <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg> -->
            </a>
            &nbsp; &nbsp; &nbsp;
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="../main.php?branch=<?php echo $chosenBranch ?>" class="nav-link px-2 text-dark">Home</a></li>
            <li>
                <div class="nav-link link-dark text-decoration-none">
                | &nbsp; &nbsp; <strong>Order History </strong>
                </div>
            </li>
            </ul>

            <?php
                    if (empty($_SESSION['username'])) { //Checks if customer is logged in
                        ?>
                        <div class="text-end">
                            <a href="login.php"><button type="button" class="btn btn-outline-primary me-2">Login</button></a>
                            <a href="client/register.php"><button type="button" class="btn btn-warning">Sign-up</button></a>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="text-end nav col-12 col-lg-auto mb-2 mb-md-0">
                            <a class="nav-link px-2 text-dark"> Hello,
                            <?php echo $name; ?> </a>
                        </div>
                        &nbsp;
                        <div class="dropdown text-end">
                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                            </a>
                            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="profile.php">Edit Account</a></li>
                                <li><a class="dropdown-item" href="main.php?action=logout">Log out</a></li>
                                
                            </ul>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <a href="cart.php?branch=<?php echo $chosenBranch ?>" class="cart d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <!-- <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg> -->
                            <img src="../img/cart4.svg" width="32" height="32"/>
                        </a>
                        <?php
                    }
            ?>
        </div>
        </div>
    </header>
    <div class="container-sm p-5 my-5">    
        <h2> <?php echo $firstName. " " .$lastName; ?></h2>
        <h3> Orders List </h3> </br>
        <?php
            $customer_query = "SELECT * FROM  Cu_orders_Ca cca 
            INNER JOIN cart  ON (cca.cart_ID = cart.cart_ID)
            WHERE cca.customer_ID='$id' AND cca.status=1";
            $customer_result = mysqli_query($conn,$customer_query);
            $customer_Check = mysqli_num_rows($customer_result); 
        
            if ($customer_Check>0) {                                               
                while($customer_row = mysqli_fetch_assoc($customer_result)) {
                    echo "<hr size='10'>";
                    echo "<table class='mt-3 pt-3' style='width:100%;'>";
                    echo "<tr>";
                    echo "<td> Cart ID: <strong>".$customer_row['cart_ID']."</strong></td>";
                    echo "<td> Total: <strong>".$customer_row['total']."</strong></td>";
                    echo "</tr> <tr>";

                    switch ($customer_row['branch_ID'] == 1) {
                        case 1: echo "<td> Branch: <strong>Paoay</strong> </td>"; break;
                        case 2: echo "<td> Branch: <strong>Vicas</strong> </td>"; break;
                        case 3: echo "<td> Branch: <strong>Cordon</strong> </td>"; break;
                    }

                    echo "<td> Date: <strong>".$customer_row['order_Date']."</strong></td>";
                    echo "</tr>";
                    echo "</table>";
                    $cartID = $customer_row['cart_ID'];
        ?>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Item Name</th>
            <th scope="col">Quantity</th>
            <th scope="col">Unit Price</th>
            <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
           
            <?php
                   
                            $item_query = "SELECT * FROM Ca_contains_I cai
                                            INNER JOIN Item i ON (cai.item_ID = i.item_ID)
                                            WHERE cai.cart_ID = '$cartID'";
                            $item_result = mysqli_query($conn,$item_query);
                            $item_Check = mysqli_num_rows($item_result); 
                            if ($item_Check>0) {                                               
                                while($item_row = mysqli_fetch_assoc($item_result)) {
                                    echo "<tr>";
                                    
                                    echo "<td>".$item_row['item_Name']."</td>";
                                    echo "<td>".$item_row['quantity']."</td>";
                                    echo "<td>".$item_row['item_RetailPrice']."</td>";
                                    echo "<td>".$item_row['total']."</td>";
                                    echo "</tr>";
                                }
                                echo "</table> </br> <hr size='10'>";
                            }

                            
                        }    
                        
                        
                    } 
                    

            ?>
        </tbody>
        </table>
    </div>
</body>
</html>