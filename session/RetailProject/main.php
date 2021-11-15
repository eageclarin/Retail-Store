<?php
    require 'env/connection.php';

    $chosenBranch = $chosenCateg = $customerID = "";
    $orderQty = $orderTotal = $totalPrice = 0;

    if (isset($_GET['branch'])) {
        $chosenBranch = $_GET['branch'];
    }
    if (isset($_GET['categ'])) {
        $chosenCateg = $_GET['categ'];
    }

    if (isset($_GET['name']) && isset($_GET['item']) && isset($_GET['branch'])) {
        $customerName = $_GET['name'];
        $customerItem = $_GET['item'];
        $chosenBranch = $_GET['branch'];
    }

    //search item in table
    $sqlItem = "SELECT * FROM Item i
                    INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
                    INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
                    INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
                    INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
                    WHERE i.item_Name = '$customerItem'
                        AND bii.item_Stock > 0
                        AND b.branch_Name = '$chosenBranch'
                ";
	$resItem = mysqli_query($conn, $sqlItem);
	$countI = mysqli_num_rows($resItem);

	//if item exists in table
	if ($countI == 1) {
		$rowI = mysqli_fetch_assoc($resItem);
		$orderName = $rowI['item_Name']; //get item name
		$orderImg = $rowI['item_Image']; //get item img
		$orderPrice = $rowI['item_RetailPrice']; //get item price
	}

    //action
	if (!empty($_GET['action'])) {
		switch($_GET['action']) {
			case "add":
				$orderTotal = $orderQty * $orderPrice;

				$sqlSearch = "SELECT * FROM `Orders` WHERE `table`='$orderTable' and `name`='$orderName' and `tab`='$orderTab' and `food`='$orderFood' and `price`='$orderPrice'";
				$resSearch = mysqli_query($conn, $sqlSearch);
				$countSearch = mysqli_num_rows($resSearch);

				if ($countSearch >= 1){ //if there's match, update
					$rowSearch = mysqli_fetch_assoc($resSearch);
					$orderQty = $rowSearch['qty'];
					$orderTotal = $rowSearch['total'];

					$orderQty++;
					$orderTotal = $orderQty * $orderPrice;

					$sqlUpdate = "UPDATE `Orders` SET `qty`='$orderQty', `total`='$orderTotal' WHERE `table`='$orderTable' and `name`='$orderName' and `tab`='$orderTab' and `food`='$orderFood'";
					$resUpdate = mysqli_query($conn, $sqlUpdate);

					header("location: order.php?name=$orderName&table=$orderTable&tab=$orderTab");
				} else { //if there's no match, insert
					$sqlAdd = "INSERT INTO `Orders`(`table`, `name`, `tab`, `code`, `food`, `qty`, `img`, `price`, `total`) VALUES ('$orderTable', '$orderName', '$orderTab', '$orderCode', '$orderFood', '$orderQty', '$orderImg', '$orderPrice', '$orderTotal')";
					$resAdd = mysqli_query($conn, $sqlAdd);

					header("location: order.php?name=$orderName&table=$orderTable&tab=$orderTab");	
				}
				break;
			case "cancel":
				$sqlSearch = "SELECT * FROM `Orders` WHERE `table`='$orderTable' and `name`='$orderName'";
				$resSearch = mysqli_query($conn, $sqlSearch);
				$countSearch = mysqli_num_rows($resSearch);

				if ($countSearch >= 1){
					$sqlDelete = "DELETE FROM `Orders` WHERE `table`='$orderTable' and `name`='$orderName'";
					$resDelete = mysqli_query($conn, $sqlDelete);

					header("location: order.php?name=$orderName&table=$orderTable&tab=$orderTab");
				}

				header("location: order.php?status=cancel&newTable=$orderTable");
				break;
			case "hold":
				$sqlSearch = "SELECT * FROM `Orders` WHERE `table`='$orderTable' and `name`='$orderName'";
				$resSearch = mysqli_query($conn, $sqlSearch);
				$countSearch = mysqli_num_rows($resSearch);

				if ($countSearch >= 1) {
					while($rowHold = mysqli_fetch_assoc($resSearch)){
						$orderFood = $rowHold['food'];
						$orderQty = $rowHold['qty'];
						$orderPrice = $rowHold['price'];
						$orderTotal = $rowHold['total'];

						$sqlAdd = "INSERT INTO `Pending`(`table`, `name`, `food`, `qty`, `price`, `total`) VALUES ('$orderTable', '$orderName', '$orderFood', '$orderQty', '$orderPrice', '$orderTotal')";
						$resAdd = mysqli_query($conn, $sqlAdd);
					}
				}

				$orderTable += 1;
				if ($orderTable > 12) {
					$orderTable = 1;
				}
				header("location: order.php?status=hold&newTable=$orderTable");
				break;
			case "delete":
				$sqlSearch = "SELECT * FROM `Orders` WHERE `table`='$orderTable' and `name`='$orderName'";
				$resSearch = mysqli_query($conn, $sqlSearch);
				$countSearch = mysqli_num_rows($resSearch);

				if ($countSearch >= 1) {
					$sqlDelete = "DELETE FROM `Orders` WHERE `table`='$orderTable' and `name`='$orderName' and `id`='$orderID'";
					$resDelete = mysqli_query($conn, $sqlDelete);

					header("location: order.php?name=$orderName&table=$orderTable&tab=$orderTab");
				}

				break;
		}
	}
?>

<html>
<head>
    <link rel="stylesheet" href="main.css" />

    <script>
        //choose branch
        function chooseBranch(branch) {
            document.location.assign("main.php?branch="+branch);
        }

        //choose categ
        funciton chooseCateg(branch, categ) {
            $.ajax({
                url: 'pages/getItem.php?branch='+branch+'&categ='+categ,
                success: function(html) {
                    var display = document.getElementById('body-items');
                    display.innerHTML = html;
                }
            });
        }
    </script>
</head>
<body>
    this is the main page..
    <?php 
    if (!empty($_SESSION['CustomerID'])) { //Checks if customer is logged in
        $customerID = $_SESSION['CustomerID'];
        echo $customerID;
    } else {
        echo '<a href="login.php">Log In</a>';
        $customerID = "Guest";
    }
        
    
    ?>
    
    <div>
        <!-- top -->
        <div id="top">
            <!-- navigation bar -->
            <ul>
                <li>
                    <p> Hello, <?php echo $customerID ?> </p>
                </li>
                <li>
                    <a href="client/cart.php"> <img src="cart.png" /> </a> 
                </li>
                <li>
                    Branch: <?php echo $chosenBranch ?>
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
                            <a href="main.php?branch=Paoay"> Paoay </a>
                            <a href="main.php?branch=Vicas"> Vicas </a>
                            <a href="main.php?branch=Cordon"> Cordon </a>
                            
                            <!--
                            <button onclick="chooseBranch('Vicas')"> Vicas </button>
                            <button onclick="chooseBranch('Vicas')"> Vicas </button>
                            <button onclick="chooseBranch('Cordon')"> Cordon </button>
                            -->
                        </div>
                    </li>
                    <li class="drp">
                        <p class="drpbtn"> Change Category </p>
                        <div class="drp-content">
                            <a href="pages/getItem.php?name=<?php echo $customerID ?>&branch=<?php echo $chosenBranch ?>&categ=Canned+Goods" target="display"> Canned Goods </a>
                            <a href="pages/getItem.php?name=<?php echo $customerID ?>&branch=<?php echo $chosenBranch ?>&categ=Condiments" target="display"> Condiments </a>
                            <a href="pages/getItem.php?name=<?php echo $customerID ?>&branch=<?php echo $chosenBranch ?>&categ=PastaNoodles" target="display"> Pasta & Noodles </a>
                            <a href="pages/getItem.php?name=<?php echo $customerID ?>&branch=<?php echo $chosenBranch ?>&categ=Beverages" target="display"> Beverages </a>
                            <a href="pages/getItem.php?name=<?php echo $customerID ?>&branch=<?php echo $chosenBranch ?>&categ=Biscuits" target="display"> Biscuits </a>
                            <!--
                            <button onclick="chooseCateg('<?php echo $chosenBranch ?>','Canned Goods');"> Canned Goods </button>
                            <button onclick="chooseCateg('<?php echo $chosenBranch ?>','Condiments');"> Condiments </button>
                            <button onclick="chooseCateg('<?php echo $chosenBranch ?>','Pasta & Noodles');"> Pasta & Noodles </button>
                            <button onclick="chooseCateg('<?php echo $chosenBranch ?>','Beverages');"> Beverages </button>
                            <button onclick="chooseCateg('<?php echo $chosenBranch ?>','Biscuits');"> Biscuits </button>
                            -->
                        </div>
                    </li>
                </ul>       
            </div>

            <!-- list of items -->
            <div id="body-items">
                <iframe name="display" height="50%" width="100%">
            </div>
        </div>
    </div>
</body>
</html>
