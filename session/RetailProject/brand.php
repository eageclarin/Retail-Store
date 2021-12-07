<?php
    require 'env/connection.php';
    session_start();
    $chosenBranch = $chosenBrand = $name = $id = ""; $chosenCateg = "All";

    if(isset($_SESSION)) {
        $chosenBranch = $_SESSION['branch'];
        $chosenBrand = $_SESSION['brand'];
        $name = $_SESSION['username'];
        $id = $_SESSION['userID'];
    }

    echo $chosenBranch;
    echo $chosenBrand;
    echo $name;

    
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
            case 'logout':
                unset($_SESSION['username']);
                header("location: main.php");
                exit;
            case'brand':
                if(empty($_SESSION['username'])) {
                    header("location: login.php");
                    exit;
                } else {
                    $_SESSION['brand'] = $_GET['brand'];
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
                                <li><a class="dropdown-item" href="#">Edit Account</a></li>
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
                    
    <div class="container-fluid p-4 mx-auto">
        <div class="row">
            <div class="col-md-7">
                    <h1 class="mb-1"> <?php echo $chosenBrand ?> </h1> <br>
                    <p> Brand Description here </p>
            </div>
            <div class="col-md-5">
                <h3> filters </h3>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 w-100">
                <iframe name="display" height="100%" width="100%" src="pages/getItem.php?branch=<?php echo $chosenBranch ?>&brand=<?php echo $chosenBrand ?>">
            </div>
        </div>
</body>
</html>
