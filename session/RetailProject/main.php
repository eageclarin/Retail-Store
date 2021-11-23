<?php
    require 'env/connection.php';

    $chosenCateg = "All"; $name = "Guest"; $id = $item = 0;
    $orderPrice = $orderQty = $orderTotal = $rand = $chosenBranch = 1;

    if (isset($_GET['item'])) {
        $item = $_GET['item'];
    }
    if (isset($_GET['id']) || isset($_GET['branch']) || isset($_GET['categ'])) {
        $id = $_GET['id'];
        $chosenBranch = $_GET['branch'];
        $chosenCateg = $_GET['categ'];

        if ($id == 0) { //id == 0 is guest
            header("location:login.php?itemID=$item&branch=$chosenBranch&categ=$chosenCateg");
        } else if ($id == "temp") {
            header("location:main.php?id=$id&branch=$chosenBranch&categ=$chosenCateg");
        }
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
    if (!empty($_GET['action'])) {
        if ($_GET['action'] == 'add' && $id != 0) { //action=add
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
    
                /* good for sql
                INSERT INTO Cart (total) VALUES (0);
                INSERT INTO Cu_orders_Ca VALUES
                    (@@IDENTITY, $id, $chosenBranch, '$date', 0)
                */
    
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
                }		
    
                header("location: main.php?id=$id&branch=$chosenBranch&categ=$chosenCateg");
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
                }
    
                header("location: main.php?id=$id&branch=$chosenBranch&categ=$chosenCateg");
            }
        }
    }
?>

<html>
<head>
    <link rel="stylesheet" href="main.css" />
    <title> Main </title>
</head>
<body>
    this is the main page..
    <?php
        if ($id != 0){ //if not guest (guest is id ==0)
            if ($id == 'temp') {
                $name = "Guest";
            } else {
                $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cust_Username FROM Customer WHERE cust_ID='$id'"));
                $name = $row['cust_Username'];
            }
        }

        if ($name == "Guest") { //Checks if customer is logged in
            echo "<a href='login.php?branch=".$chosenBranch."&categ=".$chosenCateg."'><button>Log In</button></a>";
            echo "<a href='client/register.php?branch=".$chosenBranch."&categ=".$chosenCateg."'><button>Register</button></a>";
        } else {
            echo " <a href='login.php?branch=".$chosenBranch."&categ=".$chosenCateg."'><button>Logout</button></a>";
        }
    ?>
    
    <div>
        <!-- top -->
        <div id="top">
            <!-- navigation bar -->
            <ul>
                <li>
                    <p> Hello,
                        <?php
                            if (strlen($name) > 5) {
                                echo substr($name,0,-3);
                            } else {
                                echo $name;
                            }
                            
                        ?> </p>
                </li>
                <?php
                        if ($name != "Guest") {
                    ?>
                        <li>
                    
                            Cart <a href="client/cart.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>"> <img src="cart.png" /> </a> 
                        </li>
                <?php  
                        }
                    ?>
                <li>
                    Branch: <?php
                        switch($chosenBranch) {
                            case 1: echo "Paoay"; break;
                            case 2: echo "Vicas"; break;
                            case 3: echo "Cordon"; break;
                        }
                    ?>
                </li>
            </ul>
        </div>

        <!-- body -->
        <div>
            <!-- filter -->
            <div id="body-filter">
                <ul>
                    <li class="drp">
                        <p class="drpbtn"> Change Branch </p>
                        <div class="drp-content">
                            <?php
                                if ($id == 0) {
                                    $temp = 'temp';
                                } else {
                                    $temp = $id;
                                }
                            ?>
                            <a href="main.php?id=<?php echo $temp ?>&branch=1&categ=All"> Paoay </a>
                            <a href="main.php?id=<?php echo $temp ?>&branch=2&categ=All"> Vicas </a>
                            <a href="main.php?id=<?php echo $temp ?>&branch=3&categ=All"> Cordon </a>
                        </div>
                    </li>
                    <li class="drp">
                        <p class="drpbtn"> Change Category </p>
                        <div class="drp-content">
                            <a href="pages/getItem.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>&categ=Canned+Goods" target="display"> Canned Goods </a>
                            <a href="pages/getItem.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>&categ=Condiments" target="display"> Condiments </a>
                            <a href="pages/getItem.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>&categ=PastaNoodles" target="display"> Pasta & Noodles </a>
                            <a href="pages/getItem.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>&categ=Beverages" target="display"> Beverages </a>
                            <a href="pages/getItem.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>&categ=Biscuits" target="display"> Biscuits </a>
                        </div>
                    </li>
                </ul>       
            </div>

            <!-- list of items -->
            <div id="body-items">
                <iframe name="display" height="50%" width="100%" src="pages/getItem.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>&categ=<?php echo $chosenCateg ?>">
            </div>
        </div>
    </div>
</body>
</html>
