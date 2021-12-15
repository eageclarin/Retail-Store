<?php
    include_once '../env/userconnection.php';
    
    $id = $_SESSION['userID'];
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
            header('location: ../main.php');
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

<body>
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
                    echo "<td> Cart ID: ".$customer_row['cart_ID']."</td>";
                    echo "<td> Total: ".$customer_row['total']."</td>";
                    echo "</tr> <tr>";
                    echo "<td> Branch ID: ".$customer_row['branch_ID']."</td>";
                    echo "<td> Date: ".$customer_row['order_Date']."</td>";
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

        <form action="report.php" method="post" class="form-inline">   
            <input  type="submit" value="Home" name="home" class="form-control" style="width:150px" > 
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['home'])) {            #if cancel is pressed
    echo "<script> location.replace('../main.php'); </script>";
}

?>