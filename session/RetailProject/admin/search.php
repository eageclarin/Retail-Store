<?php

include '../env/connection.php';
include '../env/adminAuth.php';


if (isset($_POST['search'])) {
   $Name = $_POST['search'];
   $Query = "SELECT * FROM item WHERE item_Name LIKE '%$Name%' LIMIT 5";

   $ExecQuery = MySQLi_query($conn, $Query);

   while ($Result = MySQLi_fetch_array($ExecQuery)) {
       ?>

   <li class="list-group-item" onclick='fill("<?php echo $Result["item_Name"]; ?>")'>
   <a>
       <?php echo $Result['item_Name']; ?>
   </li></a>
   <?php
}}
?>
