<?php
    require '../env/userconnection.php';
    // require 'http://localhost/CMSC_P3/session/RetailProject/env/UserAuth.php';
    $chosenBranch = $brand = $item = $qty = $disable = $name = $id = "";
    $display = "none"; $opacity=1;

    if(isset($_SESSION)) {
        $chosenBranch = $_SESSION['branch'];
        $name = $_SESSION['username'];
        $id = $_SESSION['userID'];
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
            location.replace('cart.php?id='+getID+'&branch='+getBranch+'&item='+getItem);
        }
        function del(getID, getBranch, getItem) {
            location.replace('cart.php?action=delete&id='+getID+'&branch='+getBranch+'&item='+getItem);
        }
        function back(getBranch) {
            location.replace('cart.php?branch='+getBranch);
        }
    </script>
    <style>
        #delete {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9;
            width: 30%;
            height: 70%;
        }
        #content {
            width: 100%;
            height: 100%;
        }
    </style>
  
</head>
    <body>
        <div id="content" style="opacity: <?php echo $opacity ?>">
        <div class="container-fluid h-100 bg-danger bg-gradient">
            <div class="row h-100 d-flex justify-content-between">
                <div class="col-7 p-0">
                    <header class="p-3 m-3 h-20 bg-white" style="border-radius: 15px">
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                                <img src="../img/logo.jpg" height="50" role="img" />
                                <!-- <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg> -->
                            </a>
                            &nbsp; &nbsp; &nbsp;
                            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                            <li><a href="../main.php?branch=<?php echo $branch ?>" class="nav-link px-2 text-dark">Home</a></li>
                            <li>
                                <a class="nav-link link-dark text-decoration-none dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Go To:
                                </a>
                                <ul class="dropdown-menu dropdown-menu-macos mx-0 shadow" style="width: 220px;">
                                    <li><a class="dropdown-item" href="../brand.php?branch=<?php echo $branch ?>">Brands</a></li>
                                    <li><a class="dropdown-item" href="../categories.php?branch=<?php echo $branch ?>">Categories</a></li>
                                </ul>
                            </li>
                            </ul>
                        </div>
                    </header>

                    <div class="container-fluid h-80">
                        <h4 class="row p-2 fw-bold text-light"> Checkout Details </h4>
                        <div class="row mb-3 text-start">
                            <div class="col">
                                <div class="card rounded-3 shadow-sm">
                                <div class="card-header py-3 d-flex justify-content-between">
                                    <h6 class="my-auto fw-bold">Billing Address</h6>
                                    <a href="profile.php"><button type="button" class="w-30 h-100 btn btn-sm btn-outline-primary">Edit</button></a>
                                </div>
                                <div class="row card-body d-flex justify-content-between">
                                    <div class="col-2">
                                        <img src="https://github.com/mdo.png" alt="mdo" width="100" height="100" class="rounded-circle">
                                    </div>
                                    <div class="col">
                                        <ul class="list-unstyled mb-2 fs-5">
                                        <li><?php echo $wname ?></li>
                                        <li><?php echo $contact ?></li>
                                        <li><?php echo $address ?></li>
                                        </ul>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1 text-start">
                            <div class="col">
                                <div class="card rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h6 class="my-0 fw-bold">Shipping</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Grab
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Foodpanda
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Angkas
                                        </label>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h6 class="my-0 fw-bold">Payment</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Paypal
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Bank Transfer
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            GCash
                                        </label>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col m-3 bg-white h-90" style="border-radius: 15px">
                    <header class="p-3 mt-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <?php
                                    if (empty($_SESSION['username'])) { //Checks if customer is logged in
                                        ?>
                                        <div class="text-end">
                                            <a href="../login.php"><button type="button" class="btn btn-outline-primary me-2">Login</button></a>
                                            <a href="register.php"><button type="button" class="btn btn-warning">Sign-up</button></a>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="nav col-12 col-lg-auto mb-2 mb-md-0">
                                            <a class="link-dark text-decoration-none dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Branch:
                                                <?php 
                                                    switch($branch) {
                                                        case 1: echo "Paoay"; break;
                                                        case 2: echo "Vicas"; break;
                                                        case 3: echo "Cordon"; break;
                                                    }
                                                ?>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-macos mx-0 shadow" style="width: 220px;">
                                                <li><a class="dropdown-item" href="cart.php?branch=1">Paoay</a></li>
                                                <li><a class="dropdown-item" href="cart.php?branch=2">Vicas</a></li>
                                                <li><a class="dropdown-item" href="cart.php?branch=3">Cordon</a></li>
                                            </ul>
                                        </div>
                                        <div class="dropdown text-end">
                                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                                            </a>
                                            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                                                <li><a class="dropdown-item" href="profile.php">Edit Account</a></li>
                                                <li><a class="dropdown-item" href="../main.php?action=logout">Log out</a></li>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                            ?>
                        </div>
                    </header> 

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-1"> </div>
                            <div class="col-5"> Product </div>
                            <div class="col-2"> </div>
                            <div class="col-2"> Qty </div>
                            <div class="col-2"> Total </div>
                        </div>

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
                            <div class="row align-items-center">
                                <div class="col-1">
                                    <button type="image" class="btn" onclick="delPrompt(<?php echo $itemID ?>, <?php echo $chosenBranch ?>, <?php echo $id ?>)" class="img align-middle d-block"><img src="trash.svg"></button>
                                </div>

                                <div class="col-5">
                                    <?php echo $itemName ?>
                                </div>

                                <div class="col-2">
                                    0.00g
                                </div>

                                <div class="col-2">
                                    <form action="" method="post">
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

                                <div class="col-2">
                                    <span class="align-middle" id="totalEach-<?php echo $itemID ?>"> <?php echo $itemTotal ?> </span>
                                </div>

                            </div>
                                        
                        <?php
                                }
                            }
                        ?>

                        <div class="row align-items-end border-top mt-4">
                            <div class="col mt-4">
                                <h6>Total:</h6>
                                <h2 id="totalPrice">
                                    <?php echo $totalPrice ?>
                                </h2>
                            </div>
                            <div class="col-2">
                                <form action="cart.php?action=order&id=<?php echo $id ?>&branch=<?php echo $branch ?>" method="post">
                                    <button class="btn btn-sm btn-success"> Order </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div id="delete" style="display: <?php echo $display ?>">
            <div class="modal modal-alert position-static d-block bg-transparent d-block py-5" tabindex="-1" role="dialog" id="modalChoice">
            <div class="modal-dialog bg-transparent" role="document">
                <div class="modal-content rounded-4 shadow">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Delete this item?</h5>
                    <p class="mb-0">You can always add this to cart again.</p>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    <button type="button" onclick="del(<?php echo $id ?>,<?php echo $chosenBranch ?>,<?php echo $item ?>)" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-right"><strong>Yes, delete</strong></button>
                    <button type="button" onclick="back(<?php echo $chosenBranch ?>)" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0" data-bs-dismiss="modal">No, mb mb</button>
                </div>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>