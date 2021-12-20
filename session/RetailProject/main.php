<?php
    include_once 'env/userConnection.php';
    $chosenCateg = "All"; $name = "Guest"; $id = $item = $opacity = 0; $display = "";
    $title = "Home";
    $orderPrice = $orderQty = $orderTotal = $rand = $chosenBranch = $branch = 1;

    if(isset($_SESSION['username']) && isset($_SESSION['userID'])) {
        $name = $_SESSION['username'];
        $id = $_SESSION['userID'];

        $title = $name." | Home";
    }
    
    if (isset($_GET['item'])) {
        $item = $_GET['item'];
    }

    if (isset($_SESSION['branch'])) {
        $chosenBranch = $_SESSION['branch'];
    }

    if (!empty($_GET['branch']) || !empty($_SESSION['branch'])) {
        $branch = $_GET['branch'];
        switch($branch) {
            case 1: $chosenBranch = $branch; break;
            case 2: $chosenBranch = $branch; break;
            case 3: $chosenBranch = $branch; break;
            default: $chosenBranch = $chosenBranch; break;
        }
        $display = "none";
        $opacity = 1;

        $_SESSION['branch'] = $chosenBranch;
    } else {
        $display = "block";
        $opacity = 0.2;
    }

    //action
    if (!empty($_GET['action'])) {
        switch($_GET['action']){
            case 'branch':
                $chosenBranch = $_POST['branch'];
                $_SESSION['branch'] = $chosenBranch;
                header("location: main.php?branch=$chosenBranch");
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
                    $_SESSION['categ'] = "All";
                    $_SESSION['sort'] = "ASC";
                    $_SESSION['order'] = "Name";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <style>
        #chooseBranch {
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
        
        .overlayText {
            position: relative;
            width: 100%;
            transition: .5s ease;
        }
        .overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 100%;
            opacity: 0;
            transition: .5s ease;
            background-color: rgba(255,255,255,0.90);
            border-radius: 15px;
        }
        .overlayText:hover .overlay {
            opacity: 1;
            transition: .5s ease;
        }
        .textt {
            color: black;
            font-size: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .card {
            transition: .5s ease;
        }
        .card:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(0,0,0,.75), 0 4px 8px rgba(0,0,0,0.75);
            transition: .5s ease;
        }
       
    </style>
    <title> <?php echo $title ?> </title>
   
</head>
<body style="background-color:#E6E9F0;" class="w-100 h-100">
    <div id="chooseBranch" style="display: <?php echo $display ?>">
    <form action="main.php?action=branch" method="get">
        <div class="modal modal-sheet position-static bg-transparent d-block py-5" tabindex="-1" role="dialog" id="modalSheet">
            <div class="modal-dialog bg-transparent" role="document">
                <div class="modal-content rounded-6 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Welcome! Please choose your branch.</h5>
                </div>
                <div class="modal-body py-0">
                    <label for="branch">Branch:</label>
                    <select name="branch" id="branchh" class="dropdown dropdown-menu-macos mx-0 shadow" style="width: 220px;">
                        <option class="dropdown-item" value="1" selected>Paoay</option>
                        <option class="dropdown-item" value="2">Vicas</option>
                        <option class="dropdown-item" value="3">Cordon</option>
                    </select>
                </div>
                <div class="modal-footer flex-column border-top-0">
                    <input type="submit" class="btn btn-lg btn-primary w-100 mx-0 mb-2" data-bs-dismiss="modal" value="Choose Branch">
                </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    <div id="content" style="opacity: <?php echo $opacity ?>">
    <header class="shadow p-3 mb-0 border-bottom bg-white h-20">
        <div class="container-fluid d-grid gap-3 align-items-center">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="img/logo.png" height="50" role="img" />
                <!-- <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg> -->
            </a>
            &nbsp; &nbsp; &nbsp;
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="main.php?branch=<?php echo $chosenBranch ?>" class="nav-link px-2 text-dark">Home</a></li>
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

            <?php
                    if (empty($_SESSION['username'])) { //Checks if customer is logged in
                        ?>
                        <div class="text-end">
                            <a href="login.php"><button type="button" class="btn btn-outline-primary me-2">Login</button></a>
                            <a href="client/register.php"><button type="button" class="btn btn-warning">Sign-up</button></a>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="text-end nav col-12 col-lg-auto mb-2 mb-md-0">
                            <a class="nav-link px-2 text-dark"> Hello,
                            <?php echo $name; ?> </a>
                        </div>
                        &nbsp;
                        <div class="dropdown text-end">
                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                            </a>
                            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="client/profile.php">Edit Account</a></li>
                                <li><a class="dropdown-item" href="client/report.php">Order History</a></li>
                                <li><a class="dropdown-item" href="main.php?action=logout">Log out</a></li>
                                
                            </ul>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <a href="client/cart.php?branch=<?php echo $chosenBranch ?>" class="cart d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
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
                                <div class="overlayText">
                                    <img src="img/main/brand1.jpg" class="shadow d-block w-100" style="border-radius: 15px;"/>
                                    <div class="overlay">
                                        <div class="textt"> Purefoods </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=brand&brand=C2" class="text-center text-dark h-100">
                                <div class="overlayText">    
                                    <img src="img/main/brand2.jpg" class="shadow d-block w-100" style="border-radius: 15px;"/>
                                        <div class="overlay">
                                            <div class="textt"> C2 </div>
                                        </div>
                                    </a>
                                </div>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=brand&brand=Kopiko" class="text-center text-dark h-100">
                                <div class="overlayText">     
                                    <img src="img/main/brand3.jpg" class="shadow d-block w-100" style="border-radius: 15px;"/>
                                    <div class="overlay">
                                        <div class="textt"> Kopiko </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li style="width:23%">
                            <a href="main.php?action=brand&brand=All" class="text-center text-dark h-100">
                                <div class="overlayText"> 
                                    <img src="img/main/brand4.jpg" class="shadow d-block w-100" style="border-radius: 15px;"/>
                                    <div class="overlay">
                                        <div class="textt"> All Brands </div>
                                    </div>
                                </div>
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
                    <div class="col"><a href="categories.php?categ=All" class="nav-link text-primary">All Categories ></a></div>
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
            <div class="shadow p-4 mb-4 bg-white" style="border-radius: 15px; height: 55%">
                <p class="text-center my-auto"> About Us <br>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam, necessitatibus rerum quia enim explicabo, quaerat error atque ut corporis consectetur, deserunt temporibus id nemo dolores delectus? Libero odio aspernatur distinctio.
                <br><br> Contact Us <br>
                Facebook: CMSC 127 <br>
                Twitter: cmsc_127 <br>
                Email: cmsc127@gmail.com <br>
                Contact No.: 091234567890
                </p>
            </div>
        </div>
        </div>
    </div>
    </div>
</body>
</html>
