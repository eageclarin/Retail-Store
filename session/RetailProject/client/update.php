<?php
	include_once '../env/connection.php';

	$id = $item = $qty = $branch = "";
	if (isset($_GET['id']) && isset($_GET['item']) && isset($_GET['qty']) && isset($_GET['branch'])) {
		$id = $_GET['id'];
		$item = $_GET['item'];

		$itemQty = $_GET['qty'];
		$branch = $_GET['branch'];
	}

	if(!empty($_GET['action'])){
		switch($_GET['action']) {
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
					$sqlUpdate = "UPDATE Ca_contains_I SET quantity='$itemQty', total='$itemTotalP'
                                    WHERE item_ID='$item'";
					$resUpdate = mysqli_query($conn, $sqlUpdate);

                    //update total in cart
                    $sqlUCart = "UPDATE Cart SET total=(
                        SELECT SUM(total) FROM Ca_contains_I
                            WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca
                                                WHERE customer_ID = '$id'
                                                AND branch_ID = '$branch')
                        )
                    WHERE cart_ID = (SELECT cart_ID FROM Cu_orders_Ca
                                                WHERE customer_ID = '$id'
                                                AND branch_ID = '$branch');";
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
                        $sqlDelete = "UPDATE BI_has_I SET item_Stock = item_Stock + $add
                                        WHERE inventory_ID = (SELECT inventory_ID FROM B_has_BI WHERE branch_ID = '$branch')
                                        AND item_ID = '$item'";
                        $resDelete = mysqli_query($conn, $sqlDelete);
                    }

                    echo $itemTotalP;
				} else {
                    echo "ERROR: Could not be able to execute $sqlSearch." . mysqli_error($conn);
                }
                
                break;
            case "total":
                //update total in cart
                $sqlTotal = "SELECT total FROM Cart c
                            INNER JOIN Cu_orders_Ca cca ON (c.cart_ID = cca.cart_ID)
                            WHERE cca.customer_ID = $id AND cca.branch_ID = $branch";
                $resTotal = mysqli_query($conn, $sqlTotal);
                $countTotal = mysqli_num_rows($resTotal);
    
                if($countTotal >= 1) {
                    $rowTotal = mysqli_fetch_assoc($resTotal);
                    $totalPrice = $rowTotal['total']; 

                    echo $totalPrice;
                } else {
                    echo "ERROR: Could not be able to execute $sqlTotal." . mysqli_error($conn);
                }
                
                break;
		}
	}
	
?>