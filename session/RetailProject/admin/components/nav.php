
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Ninth navbar example">
  <div class="container-xl">
      <!-- <a class="navbar-brand" href="#"><img src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24"></a> -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07XL" aria-controls="navbarsExample07XL" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

      <div class="collapse navbar-collapse " id="navbarsExample07XL">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
  
              <li class="nav-item dropdown">
                  <!-- <a class="nav-link dropdown-toggle" href="#" id="dropdown07XL" data-bs-toggle="dropdown" aria-expanded="false"><img style="height:40px; width:40px;"src="https://scontent.fcyz2-1.fna.fbcdn.net/v/t39.30808-6/244976937_4755597054502756_2532027396837009658_n.jpg?_nc_cat=102&ccb=1-5&_nc_sid=09cbfe&_nc_ohc=ZROV0RFscw4AX8mWKB7&_nc_oc=AQlG-yw4ReOnGBii9qNCnr6nbkV1UJEiorGnZEUAumEx4A5g_P-Sl99BXz_izO0vsaA&_nc_ht=scontent.fcyz2-1.fna&oh=54f2088dca621bc979648d163537c9d8&oe=61B5984E" class="rounded-circle" alt="profile"></a> -->
                  <a class="nav-link dropdown-toggle active" href="#" id="dropdown07XL" data-bs-toggle="dropdown" aria-expanded="false">Hi <?php echo $_SESSION['admin_User'];?>!</a>
                  <ul class="dropdown-menu" aria-labelledby="dropdown07XL">
                    <li>
                        <form action="adminHome.php" method="post">
                        <input  class="dropdown-item" type='submit' value='Logout' name='logout'>
                        </form>
                    </li>
                    <li><button class="dropdown-item" onclick="updateProfile()">Update Profile</button></li>
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link active" aria-current="page"href="adminHome.php">Home</a>
              </li>



              <li class="nav-item">
                  <a class="nav-link active" aria-current="page"  href="inventory.php">Inventory</a>
              </li>

              <li class="nav-item">
                  <a class="nav-link active" aria-current="page"  href="products.php">Products</a>
              </li>

              <li class="nav-item dropdown">
                  <!-- <a class="nav-link dropdown-toggle" href="#" id="dropdown07XL" data-bs-toggle="dropdown" aria-expanded="false"><img style="height:40px; width:40px;"src="https://scontent.fcyz2-1.fna.fbcdn.net/v/t39.30808-6/244976937_4755597054502756_2532027396837009658_n.jpg?_nc_cat=102&ccb=1-5&_nc_sid=09cbfe&_nc_ohc=ZROV0RFscw4AX8mWKB7&_nc_oc=AQlG-yw4ReOnGBii9qNCnr6nbkV1UJEiorGnZEUAumEx4A5g_P-Sl99BXz_izO0vsaA&_nc_ht=scontent.fcyz2-1.fna&oh=54f2088dca621bc979648d163537c9d8&oe=61B5984E" class="rounded-circle" alt="profile"></a> -->
                  <a class="nav-link dropdown-toggle active" href="#" id="dropdown07XL" data-bs-toggle="dropdown" aria-expanded="false">Orders</a>
                  <ul class="dropdown-menu" aria-labelledby="dropdown07XL">
                    <li>
                      <a class="dropdown-item " href="order.pending.php">Ordered</a>
                    </li>
                 
                    <li>
                        <a class="dropdown-item " href="order.delivered.php">Delivered</a>
                    </li>
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link active" aria-current="page"  href="financialReport.php">Financial Report</a>
              </li>

             


          </ul>

                     

      </div>
  </div>
</nav>

<script type="text/javascript">
    function updateProfile(){
        let username = "<?php echo"$user"?>";

    //   alert(username);
      $('#userUpdateModal').modal('show');
     

      $.post("update.php",{adminUser:username},function(data,status){
          
          let json=JSON.parse(data);

          $("#userUpdate_ID").val(json.admin_ID);
          $("#userUpdate_FN").val(json.admin_Firstname);
          $("#userUpdate_LN").val(json.admin_Lastname);
          $("#userUpdate_UserName").val(json.admin_Username);
          $("#userUpdate_contact").val(json.contact);
          $("#userUpdate_pass").val(json.admin_Password);
        //   alert("Data: " + data );
        
      });
      
  };

</script>

 <!-- delete Stock Modal ##################################-->
<div class="modal fade" id="userUpdateModal" tabindex="-1" aria-labelledby="updatePModalLabel"  aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="updatePModalLabel">Update Profile</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
              
              <form class="row g-3"  action="updateProfile.php" method="post">
                

                <input type="hidden" class="form-control" id="userUpdate_ID" name="userUpdate_ID">

                                                    
                <div class="col-md-12">
                    <label for="userUpdate_UserName" class="form-label ">Username</label>
                    <input type="text" class="form-control " id="userUpdate_UserName" name="userUpdate_UserName" >
                </div>
                <div class="col-md-6">
                    <label for="userUpdate_FN" class="form-label ">Firstname</label>
                    <input type="text" class="form-control " id="userUpdate_FN" name="userUpdate_FN" >
                </div>
                <div class="col-md-6">
                    <label for="userUpdate_LN" class="form-label ">Lastname</label>
                    <input type="text" class="form-control " id="userUpdate_LN" name="userUpdate_LN" >
                </div>

                <div class="col-md-6">
                  <label for="userUpdate_contact" class="form-label">Contact Number</label>
                  <input type="text" class="form-control" id="userUpdate_contact" name="userUpdate_contact" placeholder="ex. 09056447337" >
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="userUpdate_pass" >Password: </label>
                    <input type="password" class="form-control" id="userUpdate_pass" name="userUpdate_pass"  required>
                    <!-- <span id="toggle" onclick="toggle('password')"><i class="fa fa-eye"></i> </span> -->
              </div>
              <div class="col-md-6">
                    <label class="form-label" for="userOld_pass" >Enter Old Password to confirm: </label>
                    <input type="password" class="form-control"  name="userOld_pass"  required>
                    <!-- <span id="toggle" onclick="toggle('password')"><i class="fa fa-eye"></i> </span> -->
              </div>
                           
                  <div class="col-12">
                      <button type="submit" class="btn btn-primary" name="updateProfile" >UPDATE</button>
                  </div>
              </form>
          </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                                               
            </div>
      </div>
  </div>
</div>