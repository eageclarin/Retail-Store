<?php
    require 'env/connection.php';

    $chosenBranch = $chosenCateg = "";

    if (isset($_GET['branch'])) {
        $chosenBranch = $_GET['branch'];
    }
    if (isset($_GET['categ'])) {
        $chosenCateg = $_GET['categ'];
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
    }
        
    
    ?>
    
    <div>
        <!-- top -->
        <div id="top">
            <!-- navigation bar -->
            <ul>
                <li>
                    <p> Hello <?php $_SESSION['CustomerID'] ?> </p>
                </li>
                <li>
                    <a href="cart.php"> <img src="cart.png" /> </a> 
                </li>
                <li>
                    <?php echo $chosenBranch ?>
                </li>
            </ul>
        </div>

        <!-- body -->
        <div>
            <!-- filter -->
            <div id="body-filter">
                <ul>
                    <li class="drp">
                        <p class="drpbtn"> Branch </p>
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
                        <p class="drpbtn"> Category </p>
                        <div class="drp-content">
                            <a href="pages/getItem.php?branch=<?php echo $chosenBranch ?>&categ=Canned+Goods" target="display"> Canned Goods </a>
                            <a href="pages/getItem.php?branch=<?php echo $chosenBranch ?>&categ=Condiments" target="display"> Condiments </a>
                            <a href="pages/getItem.php?branch=<?php echo $chosenBranch ?>&categ=PastaNoodles" target="display"> Pasta & Noodles </a>
                            <a href="pages/getItem.php?branch=<?php echo $chosenBranch ?>&categ=Beverages" target="display"> Beverages </a>
                            <a href="pages/getItem.php?branch=<?php echo $chosenBranch ?>&categ=Biscuits" target="display"> Biscuits </a>
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

            <button onclick="chooseCateg('<?php echo $chosenBranch ?>','Canned Goods');"> Canned Goods </button>
            <!-- list of items -->
            <div id="body-items">
                <iframe name="display" height="50%" width="100%">
            </div>
        </div>
    </div>
</body>
</html>
