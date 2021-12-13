<?php

include_once '../env/connection.php';
include_once '../env/adminAuth.php';

?>


<?php
        if (isset($_POST['Add'])) {

                $password = md5($_POST['AdminPass']);
                $admin_confirmation_query = "SELECT admin_Username FROM admin where admin_Password='$password';";
                $admin_confirmation_result = mysqli_query($conn,$admin_confirmation_query);
                $admin_confirmation_Check = mysqli_num_rows($admin_confirmation_result);
                $admin_confirmation_user="";
                if($admin_confirmation_Check>0){
                    while($admin_confirmation_row = mysqli_fetch_assoc( $admin_confirmation_result)) {
                        
                        $admin_confirmation_user = $admin_confirmation_row['admin_Username'];
                 
                    }       
                }

                if($admin_confirmation_user== $_SESSION['admin_User'] ){
                     
                    $inventoryID=$_SESSION['inventoryID'];
                    $itemName =$_POST['ItemName'];
                    $RetailPrice = $_POST['RetailPrice'];
                    $WholesalePrice = $_POST['WholesalePrice'];
                    $Category = $_POST['Category'];
                    $Brand=$_POST['Brand'];
                    $Image=$_POST['Image'];
                    $Stock=$_POST['Stock'];
        
                    $AddItem_query= "INSERT INTO item(item_Name,item_RetailPrice,item_WholesalePrice ,item_Category ,item_Brand ,item_Image) VALUES ('$itemName', $RetailPrice , $WholesalePrice, '$Category', '$Brand', '$Image ')";

                    
                    $AddItem_result = mysqli_query($conn,$AddItem_query);
                        if($AddItem_result){
                
                        }
                        else{
                                die(mysqli_error($conn));
                        }

                    
                    $item_query = "SELECT *FROM item WHERE item.item_Name= '$itemName'";
                    


                    $item_result = mysqli_query($conn,$item_query);
                    $item_Check = mysqli_num_rows($item_result);
                    
                            
                    $itemID = 0;

                    if($item_Check>0){
                        while($itemrow = mysqli_fetch_array($item_result)) {
                            $itemID = $itemrow['item_ID'];    
                        }       
                    }else{
                        die(mysqli_error($conn));
                    }                                         
                                    
                    

                    $Bi_has_i_query= "INSERT INTO bi_has_i( inventory_ID,item_ID,item_Stock ) VALUES ($inventoryID,$itemID,$Stock)";
                    $Bi_has_i_result = mysqli_query($conn,$Bi_has_i_query);

                    if($Bi_has_i_result){
                        header('location: inventory.php');
                    }else{
                        // die(mysqli_error($conn));
                        header("Location: ./adminHome.php"); 
                    }
                }else{
                    $_SESSION['confirm_err']=1;
                    header('location: inventory.php');
                }                    

        }
        else{
            header("Location: ./adminHome.php"); 
        }

    ?>
