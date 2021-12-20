<?php

    include '../env/connection.php';
    include '../env/adminAuth.php';
   

    $user = $_SESSION['admin_User'] ;
    $branchID_query = "SELECT *FROM branch NATURAL JOIN (b_has_bi) NATURAL JOIN branchinventory NATURAL JOIN a_manages_b NATURAL JOIN admin WHERE admin.admin_Username= '$user' ;"; #check if in admin table
    $branchID_result = mysqli_query($conn,$branchID_query);
    $branchID_Check = mysqli_num_rows($branchID_result); #should be same with eigram

    if ($branchID_Check>0) {                                               #username and password in admin table
        while($branchID_row = mysqli_fetch_assoc($branchID_result)) {
            $_SESSION['branchID'] = $branchID_row['branch_ID'];                #store in $_SESSION for referencing later
            $_SESSION['inventoryID'] = $branchID_row['inventory_ID']; 
            $_SESSION['branchName'] = $branchID_row['branch_Name']; 
            $_SESSION['branchAddress'] = $branchID_row['branch_Address']; 
           
        }                    
    }

    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- ajax -->
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- jquery -->
        <script src="jquery-3.5.1.min.js"></script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>

 
    <title>Admin | Financial Report</title>
    <script src="../env/idle.js"></script>
  </head>
  <body>
        <?php include "./components/nav.php"?>

       
        


        <div class=" d-flex flex-column container  mt-5">


            <div class="col align-self-center ">
                <div class=" d-flex flex-column bd-highlight mb-3">
                    <div class="p-2 bd-highlight">
                       
                        <form method="post" action="financialReport.php">
                        <div class="row g-3">
                            <div class="col-md-6">
                                    <label for="month" class="form-label">Select Month</label>
                                    <select class="form-select text-center bg-primary bg-opacity-25" name="selectedMonth" required>
                                        <option selected value="0">Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                            </div>
                            <div class="col-md-6">
                                <label for="month" class="form-label">Enter Year</label>
                                <input type="number" class="form-control" placeholder="ex. 2009" name="selectedYear" min="2018" step=1 max="<?php echo date('Y') ?>" required>
                            </div>

                   

                            <div class="col-12">
                            <button type="submit" name="reportMonth" class="btn btn-primary">Submit</button>
                            </div>
                            
                        </div>
                        
                           
                        </form>
                        
                    </div>

                
                    <?php
                   if($_SESSION['admin']!=1){ 
                        if(isset($_POST['reportMonth'])) {

                            // $month = mysql_real_escape_string($_POST['reportMonth']);
                            $month= $_POST['selectedMonth'];
                           if($month ==0){
                               unset($_POST['reportMonth']);
                           }
                            $year= $_POST['selectedYear'];
                            $branchID = $_SESSION['branchID'];
                            $branchName =$_SESSION['branchName'];
                            $branchAddress =$_SESSION['branchAddress'];
                                $reportquery = "SELECT sum(total) as total, sum(item_WholesalePrice) as expenses, sum(total)-sum(item_WholesalePrice) as profit FROM `ca_contains_i` natural join cu_orders_ca natural join item WHERE MONTH(order_Date)=$month and YEAR(order_Date)=$year AND branch_ID= $branchID;";
                                $reportresult = mysqli_query($conn,$reportquery);
                                $row = mysqli_fetch_assoc( $reportresult);
                                 $profit = $row['profit'];
                                 $expenses = $row['expenses'];
                                 $revenue = $row['total'];
                                           
                             
                                     
                                
                                    ?>
                <div class="p-2 bd-highlight">
                        
                         
                         <div class="container py-5 h-100">
                    <div class="row justify-content-center align-items-center h-100">
                        <div class="col-12 col-lg-9 col-xl-7">
                            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">

    
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <img src="../img/logo.png" width="50%">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                    <h3><p class="text-end">Financial Report</p></h3>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        Branch Name: <?php echo  $branchName; ?>
                                    </div>
                              
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        Address: <?php echo  $branchAddress; ?>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        Month:   <?php echo  date('F', mktime(0, 0, 0,$month, 10)); ?>, <?php echo  $year; ?>  
                                    </div>
                                </div>
    
                                <div class="row  ps-5  border-top pt-4">
                                    <div class="col-md-6 ps-5"> <strong>Revenue</strong></div>
                                
                                    <div class="col-md-2 ">  <?php echo  $revenue; ?>  </div>
                                
                                </div>
                                <div class="row  ps-5 pt-2">
                                    <div class="col-md-6 ps-5"> <strong>Expenses</strong></div>
                                
                                    <div class="col-md-2 "> <?php echo  $expenses; ?>  </div>
                                
                                </div>
                                <div class="row  ps-5 pt-2">
                                    <div class="col-md-6 ps-5"> <strong>Profit</strong></div>
                                
                                    <div class="col-md-2 ">  <?php echo  $profit; ?>   </div>
                                
                                </div>
          

 
    
        
   
            
                            </div>
                        </div>
                    </div>
                </div>
                    </div>

<?php
                           
                        
                    
                            
                    
                        } else {
                    
                        ?>


            <!-- ------------- -->
            <div class="p-2 bd-highlight">
                 
                 <div class="container py-5 h-100">
                     <div class="row justify-content-center align-items-center h-100">
                         <div class="col-12 col-lg-9 col-xl-7">
                            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                                <div class="card-body p-4 p-md-5">
 
     
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <img src="../img/logo.png" width="50%">
                                        </div>
                                        <div class="col-md-6 mb-4">
                                        <h3><p class="text-end">Financial Report</p></h3>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-6">
                                            Branch Name: <?php echo  $_SESSION['branchName']; ?>
                                        </div>
                                
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            Address: <?php echo  $_SESSION['branchAddress']; ?>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            Month: N/A
                                        </div>
                                    </div>
        
                                    <div class="row  ps-5  border-top pt-4">
                                        <div class="col-md-6 ps-5"> <strong>No date selected</strong></div>
                                    
                                        
                                    
                                    </div>
                               
           
 
  
     
         
    
             
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
                        <!-- ----------- -->
                    <?php
                          
                            
                        }
                    ?>

          




                        <?php
                    }
                        
                    ?>
                    
                    <?php
                   if($_SESSION['admin']==1){ 
                        if(isset($_POST['reportMonth'])) {

                            // $month = mysql_real_escape_string($_POST['reportMonth']);
                            $month= $_POST['selectedMonth'];
                           if($month ==0){
                               unset($_POST['reportMonth']);
                           }
                            $year= $_POST['selectedYear'];
                 
                                $reportquery = "SELECT sum(total) as total, sum(item_WholesalePrice) as expenses, sum(total)-sum(item_WholesalePrice) as profit FROM `ca_contains_i` natural join cu_orders_ca natural join item WHERE MONTH(order_Date)=$month and YEAR(order_Date)=$year ;";
                                $reportresult = mysqli_query($conn,$reportquery);
                                $row = mysqli_fetch_assoc( $reportresult);
                                 $profit = $row['profit'];
                                 $expenses = $row['expenses'];
                                 $revenue = $row['total'];
                                           
                             
                                     
                                
                                    ?>
                <div class="p-2 bd-highlight">
                        
                         
                         <div class="container py-5 h-100">
                    <div class="row justify-content-center align-items-center h-100">
                        <div class="col-12 col-lg-9 col-xl-7">
                            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">

    
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <img src="../img/logo.png" width="50%">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                    <h3><p class="text-end">Financial Report</p></h3>
                                    </div>
                                </div>

                               
                                <div class="row mb-3">
                                  
                                    <div class="col-md-6 mb-2">
                                        Month:   <?php echo  date('F', mktime(0, 0, 0,$month, 10)); ?>, <?php echo  $year; ?>  
                                    </div>
                                </div>
    
                                <div class="row  ps-5  border-top pt-4">
                                    <div class="col-md-6 ps-5"> <strong>Revenue</strong></div>
                                
                                    <div class="col-md-2 ">  <?php echo  $revenue; ?>  </div>
                                
                                </div>
                                <div class="row  ps-5 pt-2">
                                    <div class="col-md-6 ps-5"> <strong>Expenses</strong></div>
                                
                                    <div class="col-md-2 "> <?php echo  $expenses; ?>  </div>
                                
                                </div>
                                <div class="row  ps-5 pt-2">
                                    <div class="col-md-6 ps-5"> <strong>Profit</strong></div>
                                
                                    <div class="col-md-2 ">  <?php echo  $profit; ?>   </div>
                                
                                </div>
          

 
    
        
   
            
                            </div>
                        </div>
                    </div>
                </div>
                    </div>

<?php
                           
                        
                    
                            
                    
                        } else {
                    
                        ?>


            <!-- ------------- -->
            <div class="p-2 bd-highlight">
                 
                 <div class="container py-5 h-100">
                     <div class="row justify-content-center align-items-center h-100">
                         <div class="col-12 col-lg-9 col-xl-7">
                            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                                <div class="card-body p-4 p-md-5">
 
     
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <img src="../img/logo.png" width="50%">
                                        </div>
                                        <div class="col-md-6 mb-4">
                                        <h3><p class="text-end">Financial Report</p></h3>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                       
                                
                                    </div>
                                    <div class="row mb-3">
                                       
                                        <div class="col-md-6 mb-2">
                                            Month: N/A
                                        </div>
                                    </div>
        
                                    <div class="row  ps-5  border-top pt-4">
                                        <div class="col-md-6 ps-5"> <strong>No date selected</strong></div>
                                    
                                        
                                    
                                    </div>
                               
           
 
  
     
         
    
             
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
                        <!-- ----------- -->
                    <?php
                          
                            
                        }
                    } ?>
            
        </div>
               
                 
                </div>
            </div>


        </div>

    

    





  </body>
</html>