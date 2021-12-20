
<?php 
    include_once '../env/connection.php';
    include_once '../env/adminAuth.php';

    if (isset($_POST['updateId'])){

        
        
        $item_id=$_POST['updateId'];
        $item_query ="SELECT * from item  WHERE item_ID = $item_id" ;

        $item_result = mysqli_query($conn,$item_query);
        $response=array();
       
            while ($item_row = mysqli_fetch_assoc($item_result)){
                $response = $item_row;
             
            };
        echo json_encode($response);

    }else{
        
        // header('location: adminHome.php');
        $response['status']=200;
        $response['message']="Invalid or data not found";
    };


    if (isset($_POST['itemId'])){
        $branchID=$_SESSION['branchID'];
        $item_id=$_POST['itemId'];
        $item_query ="SELECT * FROM bi_has_i where bi_has_i.inventory_ID=$branchID  AND bi_has_i.item_ID=$item_id;" ;
        $item_result = mysqli_query($conn,$item_query);
        $response=array();
       
            while ($item_row = mysqli_fetch_assoc($item_result)){
                $response = $item_row;
             
            };
        echo json_encode($response);

    }else{
        
        // header('location: adminHome.php');
        $response['status']=200;
        $response['message']="Invalid or data not found";
    };

    if (isset($_POST['pendingId'])){
        
        $id=$_POST['pendingId'];
        $query ="SELECT * FROM cart where cart_ID=  $id;" ;
        $result = mysqli_query($conn,$query);
        $response=array();
       
            while ($item_row = mysqli_fetch_assoc($result)){
                $response = $item_row;
             
            };
        echo json_encode($response);

    }else{
        
        // header('location: adminHome.php');
        $response['status']=200;
        $response['message']="Invalid or data not found";
    };

    if (isset($_POST['adminUser'])){
        $user = $_POST['adminUser'];
        $query ="SELECT * FROM admin natural join admin_contact where admin.admin_Username='$user' ;" ;
        $result = mysqli_query($conn,$query);
        $response=array();
        while ($item_row = mysqli_fetch_assoc($result)){
            $response = $item_row;
            
        };
         
        echo json_encode($response);

    }else{
        
        // header('location: adminHome.php');
        $response['status']=200;
        $response['message']="Invalid or data not found";
    };

    if (isset($_POST['productID'])){

        $id=$_POST['productID'];
        $item_query ="SELECT * FROM item where item.item_ID=$id;" ;
        $item_result = mysqli_query($conn,$item_query);
        $response=array();
       
            while ($item_row = mysqli_fetch_assoc($item_result)){
                $response = $item_row;
             
            };
        echo json_encode($response);

    }else{
        
        // header('location: adminHome.php');
        $response['status']=200;
        $response['message']="Invalid or data not found";
    };


    if (isset($_POST['productId'])){

        $item_id=$_POST['productId'];
        $item_query ="SELECT * FROM item where item_ID=$item_id;" ;
        $item_result = mysqli_query($conn,$item_query);
        $response=array();
       
            while ($item_row = mysqli_fetch_assoc($item_result)){
                $response = $item_row;
             
            };
        echo json_encode($response);

    }else{
        
        // header('location: adminHome.php');
        $response['status']=200;
        $response['message']="Invalid or data not found";
    };

?>


