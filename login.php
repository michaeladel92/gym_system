<?php
  require("inc/init.php");
  require_once("inc/nav.php");
  

  $notifications = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['new_agent'])){
      $messages    = [];
    
      $email = clean($_POST['email'],'email');
      $password= $_POST['password'];
    
      $max   = 40;
      $min   = 3;
 
        //validate email
        if(!validate($email,'empty')){
          $messages[] = 'Please Enter Email Address!';  
        }
        elseif(!validate($email,'max',$max)){
          $messages[] = "max. char for Agent Email is $max";  
        }
        elseif(!validate($email,'email')){
          $messages[] = 'Email Is Not Valid';  
        }
      
   
        if(!validate($password,'empty')){
          $messages[] = 'Please Enter password';  
        }

        // count message
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
        }else{

          // check if email exist in db
          $sql = "SELECT * FROM `users` WHERE `email` = '{$email}' and  `password` = '{$password}' ";
          $query_check_email = mysqli_query($conn,$sql);
          $user = mysqli_fetch_assoc( $query_check_email)  ;

          if( $user){
            session_start();
          $_SESSION['id']=$user['id'];
        
          $_SESSION['email']=$user['email'];
          //$_SESSION['id']=$user['id'];
            
           if ($user['role_id']==1) {
            setMessage("User Added Successfully!",'success');
            redirectHeader('add_user.php'); 
           }
           if ($user['role_id']==2) {
            setMessage("User Added Successfully!",'success');
            redirectHeader('add_user.php'); 
           }
           if ($user['role_id']==3) {
            setMessage("User Added Successfully!",'success');
            redirectHeader('add_user.php'); 
           }
             
                    
            }else{
              
              $notifications[] = "<div class='alert alert-danger' role='alert'>Email or password not  Exist!</div>";
        
          }
        }
    }
}


?>


 <div class="container"> 
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">log in</div>

                <div class="card-body">
                    <form method="POST" actions="<?php $_SERVER["PHP_SELF"];?>"">
                       

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                            <input type="text" name="email" class="form-control" id="inputPassword4"
                                    value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">password</label>

                            <div class="col-md-6">
                            <input type="text"  name="password" class="form-control" id="exampleFormControlInput1" placeholder="password">

                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit"  name="new_agent" class="btn btn-primary">
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