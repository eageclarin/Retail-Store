<?php

include '../env/connection.php';
include '../env/adminAuth.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./components/admin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Admin</title>
</head>
<body>
    <div class="body">
        <?php include "./components/header.html"?>
        <?php include "./components/nav.html"?>

    <div class="form">
       
        <div class="form-con shadow" action="insert.php" method="post">
            <h4>New Item Form</h4>
            <hr>
            <form class="row g-3">
                <div class="col-12">
                    <label for="ItemName" class="form-label">Item name</label>
                    <input type="text" class="form-control" name="ItemName" >
                </div>
                <div class="col-md-6">
                    <label for="Retail_Price" class="form-label">Retail Price</label>
                    <input type="number" class="form-control" name="Retail_Price" min=0>
                </div>
                <div class="col-md-6">
                    <label for="Wholesale_Price" class="form-label">Wholesale Price</label>
                    <input type="number" class="form-control" name="Wholesale_Price"  min=0>
                </div>
                
                <div class="col-md-6">
                    <label for="Retail_Price" class="form-label">Category</label>
                    <input type="text" class="form-control" name="Retail_Price" min=0>
                </div>
                <div class="col-md-6">
                    <label for="Brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" name="Brand" >
                </div>

                <div class="col-md-6">
                    <label for="Image" class="form-label">Image</label>
                    <input type="text" class="form-control" name="Image" >
                </div>
                <div class="col-md-6">
                    <label for="Stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="Stock"  min=0>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="Add">Add</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <?php
        

    ?>

</body>
</html>
