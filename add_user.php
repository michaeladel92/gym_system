<?php
  require("inc/init.php");
  require_once("inc/nav.php");
  

  $notifications = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['new_agent'])){
      $messages    = [];
      $name  = clean($_POST['name'],'string');
      $email = clean($_POST['email'],'email');
      $role  = clean($_POST['role'],'num');
      $max   = 40;
      $min   = 3;
          //validate name
        if(!validate($name,'empty')){
          $messages[] = 'Please Enter full Name!';  
        }
        elseif(!validate($name,'min',$min)){
          $messages[] = "min. char for Agent Name is $min";  
        }
        elseif(!validate($name,'max',$max)){
          $messages[] = "max. char for Agent Name is $max";  
        }
        //validate email
        elseif(!validate($email,'empty')){
          $messages[] = 'Please Enter Email Address!';  
        }
        elseif(!validate($email,'max',$max)){
          $messages[] = "max. char for Agent Email is $max";  
        }
        elseif(!validate($email,'email')){
          $messages[] = 'Email Is Not Valid';  
        }
        // validate role
        elseif(!validate($role,'empty')){
          $messages[] = 'Please Choose Role!';  
        }
        // validate role
        elseif(!validate($role,'num')){
          $messages[] = 'Oops, Something went Wrong, Please try again!';  
        }elseif(!in_array($role,roleArray())){
          $messages[] = 'Oops, Something went Wrong, Please try again!';  
        }



        // count message
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
        }else{

          // check if email exist in db
          $sql = "SELECT `email` FROM `users` WHERE `email` = '{$email}'";
          $query_check_email = mysqli_query($conn,$sql);
          $count = mysqli_num_rows($query_check_email);

          if($count > 0){
            $notifications[] = "<div class='alert alert-danger' role='alert'>Email already Exist!</div>";
          }else{
              // random Number Generator
              $sql         = "SELECT `agent_code` FROM `users`";
              $existCodes  = mysqli_query($conn,$sql); 
              while($codes = mysqli_fetch_assoc($existCodes)){
                $arr_code[] = $codes['agent_code'];
              }
            
              do {$rand = rand(1000, 9999);} 
              while(in_array($rand, $arr_code));
              $password = password_hash($rand , PASSWORD_BCRYPT, ['cost' => 12]);

              //INSERT
              $sql = "INSERT INTO `users` (`full_name`,`agent_code`,`email`,`password`,`role_id`)";
              $sql .= "VALUES('{$name}','{$rand}','{$email}','{$password}','{$role}')";
              $query_user = mysqli_query($conn,$sql);
              if($query_user){
                setMessage("User Added Successfully!",'success');
                redirectHeader('add_user.php'); 
              }else{
                $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went Wrong, Please try again!</div>";

              }
          }
        }
    }
}


?>
 <div class="container mt-5">
 <div class="offset-md-3 col-md-6">
      <form actions="<?php $_SERVER["PHP_SELF"];?>" method="POST">
        <div class="form-row">
          <!-- name -->
          <div class="form-group col-md-6">
            <label for="inputEmail4">Full Name</label>
            <input type="text" name="name" class="form-control" id="inputEmail4" 
            value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
          </div>
          <!-- email -->
          <div class="form-group col-md-6">
            <label for="inputPassword4">Email</label>
            <input type="text" name="email" class="form-control" id="inputPassword4"
            value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
          </div>
        </div>
          <!-- role -->
        <div class="form-group offset-md-3 col-md-6">
          <label for="inputState">Role</label>
          <select name="role" id="inputState" class="form-control">
            <?php
            // get role
               $sql        = "SELECT * FROM `roles` ORDER BY `id` DESC";
               $role_query = mysqli_query($conn,$sql);
              while($rows = mysqli_fetch_assoc($role_query)){
                $role_array[] = $rows['id'];
                $row_id   = $rows['id'];
                $row_name = $rows['role'];
                echo "<option value='{$row_id}'>{$row_name}</option>
                ";
            } 
            ?>  
          </select>
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
        <button type="submit" name="new_agent" class="btn btn-info">New Agent</button>
      </form>
    </div>
</div>

<?php
require_once('inc/footer.php');