<?php
    include_once '../env/connection.php';

    if (isset($_GET['branch']) && isset($_GET['categ'])) {
        $branch = $_GET['branch'];
        $categ = $_GET['categ'];
    }

    echo $branch;
    echo $categ;
?>