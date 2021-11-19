<?php
    include_once '../env/connection.php';
    $id = $branch = $item = $qty = "";

    if (isset($_GET['id']) && isset($_GET['branch'])) {
        $id = $_GET['id'];
        $branch = $_GET['branch'];
    }
    if (isset($_GET['item'])) {
        $item = $_GET['item'];
    }
    if (isset($_GET['qty'])) {
        $itemQty = $_GET['qty'];
    }

    $sqlTotal = "SELECT total FROM Cart c
        INNER JOIN Cu_orders_Ca cca ON (c.cart_ID = cca.cart_ID)
        WHERE cca.customer_ID = $id AND cca.branch_ID = $branch";
    $resTotal = mysqli_query($conn, $sqlTotal);
                        
    if ($resTotal) {
        $row = mysqli_fetch_assoc($resTotal);
        $totalPrice = $row['total'];
    }

    if (!empty($_GET['action'])) {
		switch($_GET['action']) {
            case "delete":
                $sqlSearch = "SELECT * FROM Ca_contains_I cai
                        INNER JOIN Cu_orders_Ca cca ON (cai.cart_ID = cca.cart_ID)
                        INNER JOIN Item i ON (cai.item_ID = i.item_ID)
                        WHERE cca.customer_ID='$id' AND cca.branch_ID='$branch'
                            AND cai.item_ID='$item'";
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

                        header("location: cart.php?id=$id&branch=$branch");
                    }
                }

                break;
            case "update":
                $sqlSearch = "SELECT * FROM Ca_contains_I cai
                        INNER JOIN Cu_orders_Ca cca ON (cai.cart_ID = cca.cart_ID)
                        INNER JOIN Item i ON (cai.item_ID = i.item_ID)
                        WHERE cca.customer_ID='$id' AND cca.branch_ID='$branch'
                            AND cai.item_ID='$item'";
                $resSearch = mysqli_query($conn, $sqlSearch);
                $countSearch = mysqli_num_rows($resSearch);

				if ($countSearch >= 1){ //if there's match, update
					$rowSearch = mysqli_fetch_assoc($resSearch);
					$itemPrice = $rowSearch['item_RetailPrice'];
                    $oldQty = $rowSearch['quantity'];

					$itemTotalP = $itemQty * $itemPrice;
					$sqlUpdate = "UPDATE Ca_contains_I SET quantity='$itemQty', total='$itemTotalP'";
					$resUpdate = mysqli_query($conn, $sqlUpdate);

                    //update total in cart
                    $sqlUCart = "UPDATE Cart SET total=(
                        SELECT SUM(total) FROM Ca_contains_I
                            WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID = '$id')
                        )
                    WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID = '$id');";
                    $resUCart = mysqli_query($conn, $sqlUCart);

                    if ($itemQty > $oldQty) {
                        //delete stock in bi_has_i
                        $remove = $itemQty - $oldQty;
                        $sqlDelete = "UPDATE BI_has_I SET item_Stock = item_Stock - $remove
                                        WHERE inventory_ID = (SELECT inventory_ID FROM B_has_BI WHERE branch_ID = '$branch')
                                        AND item_ID = '$item'";
                        $resDelete = mysqli_query($conn, $sqlDelete);
                    } else if ($itemQty < $oldQty) {
                        //add stock in bi_has_i
                        $add = $oldQty - $itemQty;
                        $sqlDelete = "UPDATE BI_has_I SET item_Stock = item_Stock - $add
                                        WHERE inventory_ID = (SELECT inventory_ID FROM B_has_BI WHERE branch_ID = '$branch')
                                        AND item_ID = '$item'";
                        $resDelete = mysqli_query($conn, $sqlDelete);
                    }
				}
                echo $itemTotalP;
                break;
            case "total":
                $sqlTotal = "SELECT total FROM Cart c
                            INNER JOIN Cu_orders_Ca cca ON (c.cart_ID = cca.cart_ID)
                            WHERE cca.customer_ID = $id AND cca.branch_ID = $branch";
                $resTotal = mysqli_query($conn, $sqlTotal);
                $countTotal = mysqli_num_rows($resTotal);
    
                if($countTotal >= 1) {
                    $rowTotal = mysqli_fetch_assoc($resTotal);
                    $totalPrice = $rowTotal['total']; 
                }
                echo $totalPrice;
                break;
        }
    }
?>

<html>
<head>
    <script src="jquery-3.2.1.min.js"></script>
    <script>
        function changeQty(getID, getItem, getQty, getBranch) {
            var dataString = "action=update&id="+getID+"&item="+getItem+"&qty="+getQty+"&branch="+getBranch;

            $.ajax({
                type: "GET",
                url: "cart.php",
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
                url: "cart.php",
                data: dataString,
                success: function(data){
                $("#totalPrice").html(data);
                } 
            });
        }
    </script>
</head>
    <body>
        <!-- start content-right-cart -->
        <div class="right-cart" width="100%">
            <!-- start cart -->
            <div id="cart">
                <!-- start cart header -->
                <table style="font-family: montserrat b;width: 100%;">
                        <tr>
                        <td style="width: 10%;"> </td>
                        <td style="width: 30%;"> Name </td>
                        <td style="width: 30%;"> Qty </td>
                        <td style="width: 30%;"> Total </td>
                        </tr>
                    </table>
                    <!-- end cart header -->

                    <?php
                        $sqlCart = "SELECT * FROM Ca_contains_I cai
                                    INNER JOIN Cu_orders_Ca cca ON (cai.cart_ID = cca.cart_ID)
                                    INNER JOIN Item i ON (cai.item_ID = i.item_ID)
                                    WHERE cca.customer_ID='$id' AND cca.branch_ID='$branch'
                                    ORDER BY cai.item_ID ASC";
                        $resCart = mysqli_query($conn, $sqlCart);
                    ?>

                    <!-- start cart-contents -->
                    <div class="cart-contents">
                        <table width="100%">
                    <?php 
                        if ($resCart) {
                            while(($rowCart = mysqli_fetch_assoc($resCart))) {
                                $itemID = $rowCart['item_ID'];
                                $itemName = $rowCart['item_Name'];
                                $itemQty = $rowCart['quantity'];
                                $itemTotal = $rowCart['total'];
                                $cartID = $rowCart['cartID'];
                    ?>
                        <tr>
                            <!-- start order delete -->
                            <td style="width: 10%">
                                <form action="cart.php?action=delete&id=<?php echo $id ?>&branch=<?php echo $branch ?>&item=<?php echo $itemID ?>" method="post">
                                    <input type="image" class="img" src="../img/icon-delete.png" />
                                </form>
                            </td>
                            <!-- end order delete -->

                            <!-- start order name -->
                            <td width="30%">
                                <?php echo $itemName ?>
                            </td>
                            <!-- end order name -->

                            <!-- start order qty -->
                            <td width="30%">
                                <form action="" method="post">
                                    <select name="qty" class="select" onchange="changeQty('<?php echo $id ?>', '<?php echo $itemID ?>', this.value, '<?php echo $branch ?>');">
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
                            </td>
                            <!-- end order qty -->

                            <!-- start total each -->
                            <td width="30%">
                                <p id="totalEach-<?php echo $itemID ?>"> <?php echo $itemTotal ?> </p>
                            </td>
                            <!-- end order total each -->

                        </tr>
                            
                            <?php
                                }
                            }
                            ?>
                        </table>
                    </div>
                    <!-- end cart contents -->

                </div> <!-- end cart -->

            </div> <!-- end content right cart -->

            <!-- start right-total -->
            <div class="right-total" style="bottom: 10px; right: 10px; position: fixed;">

                <!-- start total -->
                <div class="total">
                    <div> Total: </div>
                    <div id="totalPrice">
                        <?php echo $totalPrice ?>
                    </div>
                </div>
                <!-- end total -->
                <!-- start content-right-pay -->
            <div class="content-right-pay" style="height: 10%;">
                <button class="pay" onclick="payCompute()"> Pay </button>
            </div> <!-- end  content-right-pay -->
            </div>
            <!-- end right-total -->

            
    </body>
</html>