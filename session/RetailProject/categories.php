<?php
    include_once 'env/connection.php';
    $chosenBranch = $name = $id = "";
	$chosenCateg = $chosenBrand = $categ = "All";
	$sort = "ASC";
	$order = "Name";

    if(isset($_SESSION)) {
        $chosenBranch = $_SESSION['branch'];
        $chosenBrand = $_SESSION['brand'];
        $name = $_SESSION['username'];
        $id = $_SESSION['userID'];
    }
    
    if(isset($_SESSION['categ'])) {
		$chosenCateg = $_SESSION['categ'];
        $categ = $chosenCateg;
	} if (isset($_SESSION['sort'])) {
		$sort = $_SESSION['sort'];
	} if (isset($_SESSION['order'])){
		$order = $_SESSION['order'];
	}

    if (!empty($_GET['brand'])) {
        $chosenBrand = $_GET['brand'];
        $_SESSION['brand'] = $chosenBrand;
    }
    if (!empty($_GET['categ'])) {
        $chosenCateg = $_GET['categ'];
        $_SESSION['categ'] = $chosenCateg;
        $categ = $chosenCateg;
    } if ($chosenCateg == "PastaNoodles") {
        $categ = "Pasta & Noodles";
    }

    if (!empty($_GET['sort']) && !empty($_GET['order'])) {
        $order = $_GET['order'];
        $sort = $_GET['sort'];
        $_SESSION['sort'] = $sort;
        $_SESSION['order'] = $order;
    }
    if (!empty($_GET['item'])) {
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
    
    /* for categpry descriptions */
    //search item in table
    if ($chosenCateg != "All") {
        $sqlCateg = "SELECT COUNT(i.item_ID) AS items FROM Item i
                INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
                INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
                INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
                INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
                WHERE i.item_Category = '$categ'
                    AND bii.item_Stock > 0
                    AND b.branch_ID = '$chosenBranch'
                ";
    } else if ($chosenCateg == "All") {
        $sqlCateg = "SELECT COUNT(i.item_ID) AS items FROM Item i
                INNER JOIN BI_has_I bii ON (i.item_ID = bii.item_ID)
                INNER JOIN branchInventory bi ON (bi.inventory_ID = bii.inventory_ID)
                INNER JOIN B_has_BI bbi ON (bbi.inventory_ID = bi.inventory_ID)
                INNER JOIN Branch b on (b.branch_ID = bbi.branch_ID)
                WHERE bii.item_Stock > 0
                    AND b.branch_ID = '$chosenBranch'
                    OR i.item_Category = '$categ'
                ";
    }
    
	$resCateg = mysqli_query($conn, $sqlCateg);
    $countC = mysqli_num_rows($resCateg);
    if ($countC > 0) {
        $rowC = mysqli_fetch_assoc($resCateg);
        $desc = "There is <b>a total of ".$rowC['items']." ".$categ." item/s</b>.";
    } else {
        $desc = "There is no ".$categ." item/s";
    }
	
    //action add to cart
    if (!empty($_GET['action'])) {
        switch($_GET['action']){
            case 'logout':
                session_destroy();
                header("location: main.php");
                exit;   
        }
    }
?>

<html>
<head>
    <link rel="stylesheet" href="main.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title> Categories </title>
</head>
<body style="background-color:#E6E9F0;" class="w-100 h-100">
    <header class="shadow p-3 mb-0 border-bottom bg-white h-20">
        <div class="container-fluid d-grid gap-3 align-items-center">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="../main.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
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
                    <li><a class="dropdown-item" href="categories.php?branch=1">Paoay</a></li>
                    <li><a class="dropdown-item" href="categories.php?branch=2">Vicas</a></li>
                    <li><a class="dropdown-item" href="categories.php?branch=3">Cordon</a></li>
                </ul>
            </li>
            </ul>

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
                            <a class="nav-link px-2 text-dark"> Hello,  <?php echo $name; ?> </a>
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
                        <a href="client/cart.php?branch=<?php echo $chosenBranch ?>" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <!-- <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg> -->
                            <img src="img/cart4.svg" width="32" height="32"/>
                        </a>
                        <?php
                    }
            ?>
        </div>
        </div>
    </header>
                    
    <div class="container-fluid p-4 mx-auto h-100">
        <div class="row">
            <div class="col-md-7">
                <h1><a class="link-dark text-decoration-none dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                        if ($categ == "All") { echo $categ." Categories";}
                        else {echo $categ;}
                    ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-macos mx-0 shadow" style="width: 300px;">
                    <li><a class="dropdown-item" href="categories.php?categ=All">All Categories</a></li>
                    <li><a class="dropdown-item" href="categories.php?categ=Canned+Goods">Canned Goods</a></li>
                    <li><a class="dropdown-item" href="categories.php?categ=Condiments">Condiments</a></li>
                    <li><a class="dropdown-item" href="categories.php?categ=PastaNoodles">Pasta & Noodles</a></li>
                    <li><a class="dropdown-item" href="categories.php?categ=Beverages">Beverages</a></li>
                    <li><a class="dropdown-item" href="categories.php?categ=Biscuits">Biscuits</a></li>
                </ul>
                </h1>
                <p> <?php echo $desc ?> </p>
            </div>
            <div class="col-md-5">
                <ul class="nav col-lg-auto mb-2 mb-md-0 justify-content-end">
                    <li>
                        <h5>
                        <a class="nav-link link-dark text-decoration-none dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Brand: <?php echo $chosenBrand; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-macos mx-0 shadow" style="width: 220px;">
                            <li><a class="dropdown-item" href="categories.php?brand=All">All</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=Purefoods">Purefoods</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=Century">Century</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=Datu+Puti">Datu Puti</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=Lorins">Lorins</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=White+King">White King</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=Fiesta">Fiesta</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=Kopiko">Kopiko</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=Rebisco">Rebisco</a></li>
                            <li><a class="dropdown-item" href="categories.php?brand=Libbys">Libbys</a></li>
                        </ul>
                        </h5>
                    </li>
                    <li>
                        <h5>
                        <a class="nav-link link-dark text-decoration-none dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Sort by: <?php
                                switch($order){
                                    case "Name":
                                        echo "Name";
                                        if ($sort == "ASC") { echo "(A-Z)"; }
                                        else { echo "(Z-A)"; }
                                        break;
                                    case "RetailPrice":
                                        echo "Price";
                                        if ($sort == "ASC") { echo "(&uarr;)"; }
                                        else { echo "(&darr;)"; }
                                        break;
                                }
                            ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-macos mx-0 shadow" style="width: 220px;">
                            <li><a class="dropdown-item" href="categories.php?order=Name&sort=ASC">Name (A-Z)</a></li>
                            <li><a class="dropdown-item" href="categories.php?order=Name&sort=DESC">Name (Z-A)</a></li>
                            <li><a class="dropdown-item" href="categories.php?order=RetailPrice&sort=ASC">Price (&uarr;)</a></li>
                            <li><a class="dropdown-item" href="categories.php?order=RetailPrice&sort=DESC">Price (&darr;)</a></li>
                        </ul>
                        </h5>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="row h-100">
            <div class="col-md-12 w-100 h-100">
                <iframe name="display" height="100%" width="100%" src="pages/getItem.php?branch=<?php echo $chosenBranch ?>&for=categ&brand=<?php echo $chosenBrand ?>&categ=<?php echo $chosenCateg ?>&sort=<?php echo $sort ?>&order=<?php echo $order ?>">
            </div>
        </div>
</body>
</html>
