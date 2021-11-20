<?php
        include_once '../env/connection.php';
        include_once '../env/adminAuth.php';
        $item_query = "SELECT *FROM item WHERE item.item_Name= $itemName";
        $item_result = mysqli_query($conn,$item_query);
        $item_Check = mysqli_num_rows($item_result);
        
                  
        $itemID = 0;

        if($item_Check>0){
            while($item_row = mysqli_fetch_assoc($item_result)) {
                echo $itemID;
                $itemID = $itemrow['item_ID'];
                // exit;               
            }       
        }else{
            die(mysqli_error($conn));
        }                                         
                        
        

        $Bi_has_i_query= "INSERT INTO bi_has_i( inventory_ID,item_ID,item_Stock ) VALUES ($inventoryID , $itemID , $Stock )";
        $Bi_has_i_result = mysqli_query($conn,$Bi_has_i_query);

        if($Bi_has_i_result){
            header('location: inventory.php');
        }else{
            die(mysqli_error($conn));
        }


// include '../env/connection.php';
// include '../env/adminAuth.php';

//     $inventoryID = $_SESSION['inventoryID'] ;
      
//             $itemName =$_POST['Item_Name'];
//             $RetailPrice = $_POST['Retail_Price'];
//             $WholesalePrice = $_POST['Wholesale_Price'];
//             $Category = $_POST['Category'];
//             $Brand=$_POST['Brand'];
//             $Image=$_POST['Image'];
//             $Stock=$_POST['Stock'];

//             $AddItem_query= "INSERT INTO item(item_Name,item_RetailPrice,item_WholesalePrice ,item_Category ,item_Brand ,item_Image) VALUES ('$itemName', $RetailPrice , $WholesalePrice,' $Category', '$Brand', '$Image' )";

//             if($conn->query($AddItem_query)===TRUE){
//                 header('location: main.php');
//             }else{
//                 die(mysqli_error($conn));
//             }

            
//             $item_query = "SELECT *FROM item WHERE item.item_Name= $itemName;"; #check if in admin table


//             $item_result = $conn->query($item_query);
            
//             $itemID = 0;
//             if ($result->num_rows > 0) {                                               #username and password in admin table
//                 while($item_row = $item_result->fetch_assoc()) {
//                     $itemID = $item_row['item_ID'];
                
//                 }                    
//             }

//             $Bi_has_i_query= "INSERT INTO bi_has_i( inventory_ID,item_ID,item_Stock ) VALUES ($inventoryID , $itemID , $Stock )";
           

//             if($conn->query($Bi_has_i_query)===TRUE){
//                 header('location: main.php');
//             }else{
//                 die(mysqli_error($conn));
//             }
        
//         mysqli_close($conn);



?>