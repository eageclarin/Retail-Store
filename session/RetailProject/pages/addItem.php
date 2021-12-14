<?php
    include_once '../env/connection.php';
    $id = $item = $for = "";
    $orderPrice = $orderQty = $orderTotal = $rand = $chosenBranch = $branch = 1;

    if(isset($_SESSION)) {
        $chosenBranch = $_SESSION['branch'];
        $name = $_SESSION['username'];
        $id = $_SESSION['userID'];
    }
    
    if (isset($_GET['itemID'])) {
        $item = $_GET['itemID'];
    } if (isset($_GET['for'])) {
        $for = $_GET['for'];
    }
    
    /* FOR ADD TO CART ITEM */
    //search item in table
    $sqlItem = "SELECT * FROM Item i
                INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
                INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
                INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
                INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
                WHERE i.item_ID = '$item'
                    AND bii.item_Stock > 0
                    AND b.branch_ID = '$chosenBranch'
                ";
	$resItem = mysqli_query($conn, $sqlItem);
	$countI = mysqli_num_rows($resItem);

	//if item exists in table, get item price
    if ($countI >= 1) {
        $rowI = mysqli_fetch_assoc($resItem);
        $orderPrice = $rowI['item_RetailPrice']; //get item price
    }

    //action add to cart
                //check if there is consisting cartID of customerID in branchID
                $sqlCart = "SELECT * FROM Cart c
                                INNER JOIN Cu_orders_Ca cca ON (c.cart_ID = cca.cart_ID)
                                INNER JOIN Branch b ON (cca.branch_ID = b.branch_ID)
                                INNER JOIN Customer cu ON (cca.customer_ID = cu.cust_ID)
                                WHERE cu.cust_ID = '$id'
                                    AND b.branch_ID = '$chosenBranch'
                                    AND cca.status = 0;
                            ";
                $resCart = mysqli_query($conn, $sqlCart);
                $countC = mysqli_num_rows($resCart);
                
                //no cart id yet for customer in branch
                if($countC < 1) { 
                    $date = date("Y-m-d"); //get current date
        
                    $sqlAddCart = "INSERT INTO Cart (total) VALUES (0)";
                    $resAddCart = mysqli_query($conn, $sqlAddCart);
        
                    if ($resAddCart) {
                        $lastID = mysqli_insert_id($conn);
                        $sqlAdd = "INSERT INTO Cu_orders_Ca VALUES
                                        ($lastID, $id, $chosenBranch, '$date', 0)";
                        $resAdd = mysqli_query($conn, $sqlAdd);
                    }
                }
                
                //check if item is in cart
                $sqlSearch = "SELECT * FROM Ca_contains_I cai
                                INNER JOIN Cu_orders_Ca cca ON (cai.cart_ID = cca.cart_ID)
                                INNER JOIN Item i ON (cai.item_ID = i.item_ID)
                                WHERE i.item_ID = '$item' AND cca.customer_ID = '$id'
                                AND cca.branch_ID = '$chosenBranch' AND cca.status = 0;
                            ";
                $resSearch = mysqli_query($conn, $sqlSearch);
                $countSearch = mysqli_num_rows($resSearch);
                
                //if in cart, update
                if ($countSearch >= 1){ 
                    $rowSearch = mysqli_fetch_assoc($resSearch);
                    $orderQty = $rowSearch['quantity']; //get current qty
                    $orderTotal = $rowSearch['total']; //get current total
        
                    $orderQty++;
                    $orderTotal = $orderQty * $orderPrice;
        
                    //update qty and total of item in ca_contains_i then update cart and stock
                    $sqlUpdate = "UPDATE Ca_contains_I SET quantity = '$orderQty', total = '$orderTotal'
                                    WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID = '$id' AND branch_ID = '$chosenBranch' AND `status`=0)
                                ";
                    $resUpdate = mysqli_query($conn, $sqlUpdate);
        
                    if ($resUpdate){
                        //update total in cart
                        $sqlUpdate = "UPDATE Cart SET total=(
                            SELECT SUM(total) FROM Ca_contains_I
                                WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID = '$id' AND branch_ID = '$chosenBranch' AND `status`=0)
                            )
                        WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID = '$id' AND branch_ID = '$chosenBranch' AND `status`=0);";
                        $resUpdate = mysqli_query($conn, $sqlUpdate);
        
                        //decrease stock in bi_has_i
                        $sqlDelete = "UPDATE BI_has_I SET item_Stock = item_Stock - 1
                                        WHERE inventory_ID = (SELECT inventory_ID FROM B_has_BI WHERE branch_ID = '$chosenBranch')
                                        AND item_ID = '$item'";
                        $resDelete = mysqli_query($conn, $sqlDelete);

                        if ($for == "brand") {
                            header("location: ../brand.php?updated");
                        } else if ($for == "categ") {
                            header("location: ../categories.php?updated");
                        }
                    } else {
                        echo "ERROR: Could not be able to execute $sqlAdd." . mysqli_error($conn);
                    }		
        
                } else { //if not in cart yet, insert
                    //insert into ca_contains_i then update total in cart and update stock
                    $sqlCartID = mysqli_query($conn, "SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID = '$id' AND branch_ID = '$chosenBranch' AND `status`=0");
                    $rowCart = mysqli_fetch_assoc($sqlCartID);
                    $cart_ID = $rowCart['cart_ID'];
                    $sqlAdd = "INSERT INTO Ca_contains_I VALUES
                                ($item,$cart_ID,1,'$orderPrice')";
                    $resAdd = mysqli_query($conn, $sqlAdd);
        
                    if ($resAdd){
                        //update total in cart
                        $sqlUpdate = "UPDATE Cart SET total=(
                            SELECT SUM(total) FROM Ca_contains_I
                                WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID = '$id' AND branch_ID = '$chosenBranch' AND `status`=0)
                            )
                            WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca WHERE customer_ID = '$id' AND branch_ID = '$chosenBranch' AND `status`=0)";
                        $resUpdate = mysqli_query($conn, $sqlUpdate);
        
                        //decrease stock in bi_has_i
                        $sqlDelete = "UPDATE BI_has_I SET item_Stock = item_Stock - 1
                                        WHERE inventory_ID = (SELECT inventory_ID FROM B_has_BI WHERE branch_ID = '$chosenBranch')
                                        AND item_ID = '$item'";
                        $resDelete = mysqli_query($conn, $sqlDelete);

                        if ($for == "brand") {
                            header("location: ../brand.php?inserted");
                        } else if ($for == "categ") {
                            header("location: ../categories.php?inserted");
                        }
                    } else {
                        echo "ERROR: Could not be able to execute $sqlAdd." . mysqli_error($conn);
                    }
                    
                }   

    //echo "ERROR: Could not be able to execute $sqlItem." . mysqli_error($conn);
?>
