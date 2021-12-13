
<?php 
    include_once '../env/connection.php';
    include_once '../env/adminAuth.php';

    if (isset($_POST['cartID'])){

        
        
        $cart_id=$_POST['cartID'];
        $item_query ="SELECT * FROM ca_contains_i where cart_ID=$cart_id;" ;
        $item_result = mysqli_query($conn,$item_query);
        $response=array();
       
            while ($item_row = mysqli_fetch_assoc($item_result)){
                // $response = $item_row;
                array_push($response,$item_row);
             
            };
        echo json_encode($response);

    }else{
        
        // header('location: adminHome.php');
        $response['status']=200;
        $response['message']="Invalid or data not found";
    };


?>


