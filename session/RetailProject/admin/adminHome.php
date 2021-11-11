<?php

include_once '../env/connection.php';




if (!empty($_SESSION['admin'])) {
    echo "Hello admin ". $_SESSION['admin_User'];
} else {
    echo "You're not allowed here.";
}



?>