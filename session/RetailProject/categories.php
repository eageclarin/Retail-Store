<?php
    require 'env/connection.php';
    session_start();

    if(isset($_SESSION)) {
        $chosenBranch = $_SESSION['branch'];
        $chosenCateg = $_SESSION['categ'];
        $name = $_SESSION['username'];
    }

    echo $chosenBranch;
    echo $chosenCateg;
    echo $name;
?>