<?php
    require '../env/userConnection.php';
    // require 'http://localhost/CMSC_P3/session/RetailProject/env/UserAuth.php';
    $chosenBranch = $brand = $item = $qty = $disable = $name = $id = "";
    $display = "none"; $opacity=1;

    if(isset($_SESSION)) {
        $chosenBranch = $_SESSION['branch'];
        $name = $_SESSION['username'];
        $id = $_SESSION['userID'];
    }

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    }

    if (!empty($_GET['item'])) {
        $item = $_GET['item'];

        $opacity = 0.2;
        $display = "block";
    }

    if (!empty($_GET['branch'])) {
        $branch = $_GET['branch'];
        switch($branch) {
            case 1: $chosenBranch = $branch; break;
            case 2: $chosenBranch = $branch; break;
            case 3: $chosenBranch = $branch; break;
            default: $chosenBranch = $chosenBranch; break;
        }

        $_SESSION['branch'] = $chosenBranch;
    }

    $sqlUser = "SELECT * FROM Customer WHERE cust_Username = '$name' AND cust_ID = '$id'";
    $resUser = mysqli_query($conn, $sqlUser);
    if ($resUser) {
        $rowUser = mysqli_fetch_assoc($resUser);
        $wname = $rowUser['cust_FName']." ".$rowUser['cust_LName'];
        $contact = $rowUser['cust_Contact'];
        $address = "Brgy. ".$rowUser['cust_ABrgy'].", ".$rowUser['cust_ACity'].", ".$rowUser['cust_AProvince']." ".$rowUser['cust_APostal'];
    }

    $sqlTotal = "SELECT total FROM Cart c
        INNER JOIN Cu_orders_Ca cca ON (c.cart_ID = cca.cart_ID)
        WHERE cca.customer_ID = $id AND cca.branch_ID = $branch AND cca.status=0";
    $resTotal = mysqli_query($conn, $sqlTotal);
                        
    if ($resTotal) {
        $row = mysqli_fetch_assoc($resTotal);
        $totalPrice = $row['total'];
    } else {
        $totalPrice = 0;
    }

    if (!empty($_GET['action'])) {
        switch($_GET['action']) {
            case "delete":
                $sqlSearch = "SELECT * FROM Ca_contains_I cai
                                INNER JOIN Cu_orders_Ca cca ON (cai.cart_ID = cca.cart_ID)
                                INNER JOIN Item i ON (cai.item_ID = i.item_ID)
                                WHERE cca.customer_ID='$id' AND cca.branch_ID='$branch'
                                    AND cai.item_ID='$item' AND cca.status=0";
                $resSearch = mysqli_query($conn, $sqlSearch);
                $countSearch = mysqli_num_rows($resSearch);

                if ($countSearch >= 1) {
                    $rowD = mysqli_fetch_assoc($resSearch);
                    $cartID = $rowD['cart_ID'];
                    $itemQty = $rowD['quantity'];

                    $sqlDelete = "DELETE FROM Ca_contains_I WHERE item_ID='$item' AND cart_ID='$cartID'";
                    $resDelete = mysqli_query($conn, $sqlDelete);

                    if ($resDelete) {
                        $sqlUpdate = "UPDATE Cart SET total=(
                                    SELECT SUM(total) FROM Ca_contains_I WHERE cart_ID = $cartID)
                                    WHERE cart_ID = $cartID";
                        $resUpdate = mysqli_query($conn, $sqlUpdate);
                    
                        //increase stock in bi_has_i
                        $sqlDelete = "UPDATE BI_has_I SET item_Stock = item_Stock + $itemQty
                                                WHERE inventory_ID = (SELECT inventory_ID FROM B_has_BI WHERE branch_ID = '$branch')
                                                AND item_ID = '$item'";
                        $resDelete = mysqli_query($conn, $sqlDelete);
                        
                        $display = "none";
                        $opacity = 1;
                        header("location: cart.php?branch=$branch");
                    }
                }

                break;
            case "order":
                $sqlOrder = "SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID='$id' AND branch_ID='$branch' AND status=0";
                $resOrder = mysqli_query($conn, $sqlOrder);
                $rowOrder = mysqli_fetch_assoc($resOrder);

                $_SESSION['CustomerID'] = $id;                #store in $_SESSION for referencing later
                $_SESSION['CartID'] = $rowOrder['cart_ID'];
                mysqli_close($conn);
                header("location: order.php?id=$id&branch=$branch&categ=All");                    #redirect to adminHome.php
                exit;
        }
    }
?>

<html>
<head>
<title> Cart </title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function changeQty(getID, getItem, getQty, getBranch) {
            var dataString = "action=update&id="+getID+"&item="+getItem+"&qty="+getQty+"&branch="+getBranch;

            $.ajax({
                type: "GET",
                url: "update.php",
                data: dataString,
                success: function(data) {
                $("#totalEach-"+getItem).html(data);

                totalPrice(getID, getItem, getQty, getBranch);
                }
            });

            return false;
        }

        //update total price
        function totalPrice(getID, getItem, getQty, getBranch) {
            var dataString = "action=total&id="+getID+"&qty="+getQty+"&item="+getItem+"&branch="+getBranch;

            $.ajax({
                type: "GET",
                url: "update.php",
                data: dataString,
                success: function(data){
                $("#totalPrice").html(data);
                } 
            });
        }

        function delPrompt(getItem, getBranch, getID) {
            window.parent.location.replace('cart.php?id='+getID+'&branch='+getBranch+'&item='+getItem);
        }
        function del(getID, getBranch, getItem) {
            window.parent.ocation.replace('cart.php?action=delete&id='+getID+'&branch='+getBranch+'&item='+getItem);
        }
        function back(getBranch) {
            window.parent.location.replace('cart.php?branch='+getBranch);
        }
    </script>
  
</head>
    <body>
                    <div class="cart container-fluid">
                        <?php
                            $sqlCart = "SELECT * FROM Ca_contains_I cai
                                            INNER JOIN Cu_orders_Ca cca ON (cai.cart_ID = cca.cart_ID)
                                            INNER JOIN Item i ON (cai.item_ID = i.item_ID)
                                            WHERE cca.customer_ID='$id' AND cca.branch_ID='$branch'
                                            AND cca.status=0
                                            ORDER BY cai.item_ID ASC";
                            $resCart = mysqli_query($conn, $sqlCart);

                            if ($resCart) {
                                while(($rowCart = mysqli_fetch_assoc($resCart))) {
                                    $itemID = $rowCart['item_ID'];
                                    $itemName = $rowCart['item_Name'];
                                    $itemWeight = $rowCart['item_Weight'];
                                    $itemQty = $rowCart['quantity'];
                                    $itemTotal = $rowCart['total'];
                                    $cartID = $rowCart['cart_ID'];

                                    $sqlStock = "SELECT item_Stock FROM BI_has_I bii
                                                    INNER JOIN B_has_BI bbi ON (bii.inventory_ID = bbi.inventory_ID)
                                                    WHERE bii.item_ID = $itemID";
                                    $resStock = mysqli_query($conn, $sqlStock);
                                    $rowStock = mysqli_fetch_assoc($resStock);

                                    if ($rowStock['item_Stock'] <= 0) {
                                        $disable = "disabled";
                                    } else {
                                        $disable = "";
                                    }
                        ?>
                            <div class="row align-items-center border-bottom">
                                <div class="col-1 p-0">
                                    <button type="image" class="btn" onclick="delPrompt(<?php echo $itemID ?>, <?php echo $chosenBranch ?>, <?php echo $id ?>)" class="img align-middle d-block"><img src="trash.svg"></button>
                                </div>

                                <div class="col-4 p-0">
                                    <?php echo $itemName ?>
                                </div>

                                <div class="col-3">
                                    <?php echo $itemWeight ?>
                                </div>

                                <div class="col-2">
                                    <form action="" class="my-auto" method="post">
                                        <select <?php echo $disable ?> name="qty" class="select" onchange="changeQty('<?php echo $id ?>', '<?php echo $itemID ?>', this.value, '<?php echo $branch ?>');">
                                        <?php
                                            echo '<option value="'.$itemQty.'" selected>'.$itemQty.' </option>';
                                        ?>
                                            <option value="1"> 1 </option>
                                            <option value="2"> 2 </option>
                                            <option value="3"> 3 </option>
                                            <option value="4"> 4 </option>
                                            <option value="5"> 5 </option>
                                            <option value="6"> 6 </option>
                                            <option value="7"> 7 </option>
                                            <option value="8"> 8 </option>
                                            <option value="9"> 9 </option>
                                            <option value="10"> 10 </option>
                                            <option value="11"> 11 </option>
                                            <option value="12"> 12 </option>
                                            <option value="13"> 13 </option>
                                            <option value="14"> 14 </option>
                                            <option value="15"> 15 </option>
                                            <option value="16"> 16 </option>
                                            <option value="17"> 17 </option>
                                            <option value="18"> 18 </option>
                                            <option value="19"> 19 </option>
                                            <option value="20"> 20 </option>
                                        </select>
                                    </form> 
                                </div>

                                <div class="col-2" style="text-align:center">
                                    <span class="align-middle" id="totalEach-<?php echo $itemID ?>" > <?php echo $itemTotal ?> </span>
                                </div>

                            </div>
                                        
                        <?php
                                }
                            }
                        ?>
                    </div>
    </body>
</html>