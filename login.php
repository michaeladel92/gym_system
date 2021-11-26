<?php
  require("inc/init.php");
  require_once("inc/nav.php");
  // Param message | type alert | location
  isSessionIdAvailable('Access Denied!','danger','index.php');

  $notifications = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['log_agent'])){
      $messages    = [];
      $email       = clean($_POST['email'],'email');
      $password    = cleanPassword($_POST['password']);
    
      $max   = 40;
      $min   = 3;
 
        //validate email
        if(!validate($email,'empty')){
          $messages[] = 'Please Enter Email Address!';  
        }elseif(!validate($password,'empty')){
          $messages[] = 'Please Enter password';  
        }

        // count message
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
        }else{

          // check if email exist in db
          $sql = "SELECT * FROM `users` WHERE `email` = '{$email}' LIMIT 1";
          $query_check_email = mysqli_query($conn,$sql);
          
          if($query_check_email){
              $count = mysqli_num_rows($query_check_email);

              if($count > 0){
                  $row = mysqli_fetch_assoc($query_check_email);
            
                  if(!password_verify($password,$row['password'])){
                    $notifications[] = "<div class='alert alert-danger' role='alert'>Incorrect Entry!</div>";
                  }else{
                    // session users
                    $_SESSION['id']           = intval($row['id']);
                    $_SESSION['full_name']    = $row['full_name'];
                    $_SESSION['agent_code']   = $row['agent_code'];
                    $_SESSION['email']        = $row['email'];
                    $_SESSION['status']       = intval($row['status']);
                    $_SESSION['is_approved']  = intval($row['is_approved']);
                    $_SESSION['role_id']      = intval($row['role_id']);
                  //need to change password 
                    if($_SESSION['is_approved'] === 0){
                      setMessage("Please Change Your Password to be able to procceed!",'warning');
                      $editLink = "agents/edit_user.php?id=".base64_encode($_SESSION['id']);
                      redirectHeader($editLink); 
                    }else{
                      setMessage("Logged In Successfully!",'success');
                      redirectHeader('index.php'); 
                    }
                  } 
              }else{
                $notifications[] = "<div class='alert alert-danger' role='alert'>Incorrect Entry!</div>";
              }

            }else{
              
              $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went wrong, please try again!</div>";
        
          }
        }
    }
}


?>
 <div class="container mt-5">
 <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">log in</div>
                <div class="card-body">
                    <form method="POST" actions="<?php $_SERVER["PHP_SELF"];?>"">
                       <!-- email -->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                            <input type="text" name="email" class="form-control" id="inputPassword4"
                                    value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                            </div>
                        </div>
                        <!-- password -->
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">password</label>
                            <div class="col-md-6">
                            <input type="password"  name="password" class="form-control" id="exampleFormControlInput1" placeholder="password">

                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit"  name="log_agent" class="btn btn-primary">
                                log in
                                </button>

                            </div>
                        </div>
                        
                      <?php 
                      if( isset($_SESSION['message'])){
                        displayMessage();
                      }
                      // err msg
                      if(count($notifications) > 0){
                        foreach($notifications as $notification){
                          echo $notification;
                        }
                      }
                    ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
require_once('inc/footer.php');