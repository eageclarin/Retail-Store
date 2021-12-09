<?php
    require 'env/connection.php';
    //session_start();
    $chosenCateg = "All"; $name = "Guest"; $id = $item = 0;
    $orderPrice = $orderQty = $orderTotal = $rand = $chosenBranch = $branch = 1;

    if(isset($_SESSION['username']) && isset($_SESSION['userID'])) {
        $name = $_SESSION['username'];
        $id = $_SESSION['userID'];
    }
    
    if (isset($_GET['item'])) {
        $item = $_GET['item'];
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
 
    //header("location:login.php?itemID=$item&branch=$chosenBranch&categ=$chosenCateg");
    //header("location:main.php?id=$id&branch=$chosenBranch&categ=$chosenCateg");
    
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
        switch($_GET['action']){
            case 'add':
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
            case 'logout':
                session_destroy();
                header("location: main.php");
                exit;
            case'brand':
                if(empty($_SESSION['username'])) {
                    header("location: login.php");
                    exit;
                } else {
                    $_SESSION['brand'] = $_GET['brand'];
                    $_SESSION['branch'] = $chosenBranch;
                    header("location: brand.php"); 
                    exit;
                }
            case 'categ':
                if(empty($_SESSION['username'])) {
                    header("location: login.php");
                    exit;
                } else {
                    $_SESSION['categ'] = $_GET['categ'];
                    header("location: categories.php"); 
                    exit;
                }      
        }
    }

?>

<html>
<head>
    <link rel="stylesheet" href="main.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title> Main </title>
</head>
<body style="background-color:#E6E9F0;" class="w-100 h-100">
    <?php
        if ($id != 0){ //if not guest (guest is id ==0)
            if ($id == 'temp') {
                $name = "Guest";
            } else {
                $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cust_Username FROM Customer WHERE cust_ID='$id'"));
                $name = $row['cust_Username'];
            }
        }
    ?>
    <header class="shadow p-3 mb-0 border-bottom bg-white h-20">
        <div class="container-fluid d-grid gap-3 align-items-center">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="img/logo.jpg" height="50" role="img" />
                <!-- <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg> -->
            </a>
            &nbsp; &nbsp; &nbsp;
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="main.php" class="nav-link px-2 text-dark">Home</a></li>
            <li>
                <a class="nav-link link-dark text-decoration-none dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Branch:
                    <?php 
                        switch($chosenBranch) {
                            case 1: echo "Paoay"; break;
                            case 2: echo "Vicas"; break;
                            case 3: echo "Cordon"; break;
                        }
                    ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-macos mx-0 shadow" style="width: 220px;">
                    <li><a class="dropdown-item" href="main.php?branch=1">Paoay</a></li>
                    <li><a class="dropdown-item" href="main.php?branch=2">Vicas</a></li>
                    <li><a class="dropdown-item" href="main.php?branch=3">Cordon</a></li>
                </ul>
            </li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
            <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
            </form>

            <?php
                    if (empty($_SESSION['username'])) { //Checks if customer is logged in
                        ?>
                        <div class="text-end">
                            <a href="login.php">
                                <button type="button" class="btn btn-outline-primary me-2">Login</button>
                            </a>
                            <a href="client/register.php">
                                <button type="button" class="btn btn-warning">Sign-up</button>
                            </a>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="text-end nav col-12 col-lg-auto mb-2 mb-md-0">
                            <a class="nav-link px-2 text-dark"> Hello,
                            <?php
                                if ((substr($name,0,-3)) == "Guest") {
                                    echo "Guest";
                                } else {
                                    echo $name;
                                }
                                
                            ?> </a>
                        </div>
                        &nbsp;
                        <div class="dropdown text-end">
                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                            </a>
                            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="client/profile.php">Edit Account</a></li>
                                <li><a class="dropdown-item" href="main.php?action=logout">Log out</a></li>
                            </ul>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <a href="client/cart.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <!-- <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg> -->
                            <img src="img/cart4.svg" width="32" height="32"/>
                        </a>
                        <?php
                    }
            ?>
        </div>
        </div>
    </header>
                    
    <div class="container-fluid row p-4 mx-auto">
        <div class="col-md-7">
                <div class="col shadow mb-3 bg-white" style="border-radius: 15px">
                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="border-radius: 15px">
                            <div class="carousel-item active" data-bs-interval="2000">
                            <img src="img/main/ad1.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                            <img src="img/main/ad2.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                            <img src="img/main/ad3.jpg" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div> 
                </div>

                <div class="col d-flex flex-wrap w-100 mb-3 h-20">
                    <ul class="nav col-md-12 justify-content-between">
                        <li style="width:23%">
                            <a href="main.php?action=brand&brand=Purefoods" class="text-center text-dark h-100">
                                <img src="img/main/brand1.jpg" class="shadow d-block w-100" style="border-radius: 15px;"/>
                            </a>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=brand&brand=Century" class="text-center text-dark h-100">
                                <img src="img/main/brand2.jpg" class="shadow d-block w-100" style="border-radius: 15px;"/>
                            </a>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=brand&brand=Datu+Puti" class="text-center text-dark h-100">
                                <img src="img/main/brand3.jpg" class="shadow d-block w-100" style="border-radius: 15px;"/>
                            </a>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=brand&brand=Lorins" class="text-center text-dark h-100">
                                <img src="img/main/brand4.jpg" class="shadow d-block w-100" style="border-radius: 15px;"/>
                            </a>
                        </li>
                    </ul>
                    
                </div>
        </div>

        <div class="col-md-5">
            <div class="shadow bg-white mb-3 p-4 w-100" style="border-radius: 15px;">
                <div class="row">
                    <div class="col nav-link text-dark">Categories</div>
                    <div class="col-4"></div>
                    <div class="col"><a href="categories.php" class="nav-link text-primary">All Categories ></a></div>
                </div>
                <div class="col d-flex flex-wrap mt-3 h-20">
                    <ul class="nav col-md-12 mb-3 justify-content-between">
                        <li style="width:23%">
                            <a href="main.php?action=categ&categ=Canned Goods" class="card shadow bg-primary" style="border-radius: 15px; text-decoration: none">
                                <img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;" src="img/main/categ1.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <p class="card-text text-light" style="font-size: 11px">Canned Goods</p>
                                </div>
                            </a>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=categ&categ=Condiments" class="card shadow bg-success" style="border-radius: 15px; text-decoration: none">
                                <img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;" src="img/main/categ2.jpg" alt="Card image cap">
                                <div class="card-body ">
                                    <p class="card-text text-light" style="font-size: 11px">Condiments</p>
                                </div>
                            </a>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=categ&categ=PastaNoodles" class="card shadow bg-danger" style="border-radius: 15px; text-decoration: none">
                                <img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;" src="img/main/categ3.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <p class="card-text text-light" style="font-size: 11px">Pasta&Noodles</p>
                                </div>
                            </a>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=categ&categ=Beverages" class="card shadow bg-warning" style="border-radius: 15px; text-decoration: none">
                                <img class="card-img-top w-100" style="border-radius: 15px 15px 0 0;"src="img/main/categ4.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <p class="card-text text-dark" style="font-size: 11px">Beverages</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="shadow p-4 mb-4 bg-white" style="border-radius: 15px">
                <p class="text-center"> About Us <br>
                shdjkadhjkdhkdjahdjkahdkajdhakjdhajkdahdjkashdkjsd
                shdjkadhjkdhkdjahdjkahdkajdhakjdhajkdahdjkashdkjsd
                shdjkadhjkdhkdjahdjkahdkajdhakjdhajkdahdjkashdkjsd
                <br><br> Contact Us <br>
                Facebook: krusty krab <br>
                Twitter: cmsc_127 <br>
                Email: cmsc127@gmail.com <br>
                Contact No.: 091234567890
                </p>
            </div>
        </div>
    </div>
        <!-- body
        <div>
            filter
            <div id="body-filter">
                <ul>
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

            list of items
            <div id="body-items">
                <iframe name="display" height="50%" width="100%" src="pages/getItem.php?id=<?php echo $id ?>&branch=<?php echo $chosenBranch ?>&categ=<?php echo $chosenCateg ?>">
            </div>
        </div>
        
    </div>-->
</body>
</html>
