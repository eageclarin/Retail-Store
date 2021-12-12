<?php

include_once '../env/connection.php';
include_once '../env/adminAuth.php';

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
 
    <?php include "./components/nav.html"?>
    
    <div class="container bg-info p-2 text-dark bg-opacity-10   mt-4 pt-4 pb-4 ps-4 pe-4">
                <h1>MEMO 124 s.2022</h1>
                <p class="fw-light"><small>December 31, 2021</small></p>
                <p>This example is a quick exercise to illustrate how the navbar and its contents work. Some navbars extend the width of the viewport, others are confined within a <code>.container</code>. For positioning of navbars, checkout the <a href="/docs/5.1/examples/navbar-static/">top</a> and <a href="/docs/5.1/examples/navbar-fixed/">fixed top</a> examples.</p>
                <p>At the smallest breakpoint, the collapse plugin is used to hide the links and show a menu button to toggle the collapsed content.</p>
                <p>
                    <a class="btn btn-primary" href="/docs/5.1/components/navbar/" role="button">View full memo</a>
                </p>
    </div>
  
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


  </body>
</html>