<?php
  require("inc/init.php");
  require_once("inc/nav.php");


  // get id
  if(!isset($_GET['id']) || $_GET['id'] === ''){
    setMessage('Access Denied!','danger');
    redirectHeader('dashboard.php');

  }else{
    // decode id
    $user_id = base64_decode($_GET['id']);
    $user_id = clean($user_id,'num');

    if(!validate($user_id,'num')){
      setMessage('Access Denied!','danger');
      redirectHeader('dashboard.php');
    }else{
      $sql = "SELECT * FROM `users` WHERE `id` = {$user_id} LIMIT 1";
      $getUsersQuery = mysqli_query($conn,$sql);
      $count = mysqli_num_rows($getUsersQuery);
      if($count === 0){
        setMessage('Agent Not found!','danger');
        redirectHeader('dashboard.php');
      }else{
        $userRow = mysqli_fetch_assoc($getUsersQuery); 
      }
      
    }
  }


$notifications = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
    // update user
    if(isset($_POST['update_agent'])){
      $messages  = [];
      $name      = clean($_POST['name'],'string');
      $email     = clean($_POST['email'],'email');
      $role      = clean($_POST['role'],'num');
      $status    = clean($_POST['status'],'num');
      $status    = intval($status);
      $password  = cleanPassword($_POST['password']);
      $con_pass  = cleanPassword($_POST['con_pass']);
      $max       = 40;
      $min       = 3;

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
        // validate password
        elseif(validate($password,'empty')){
          if(!validate($password,'min',6)){
            $messages[] = "min. char for Password is 6";  
          }
          elseif(!validate($password,'max',$max)){
            $messages[] = "max. char for Password is $max"; 
          }
          elseif($password !== $con_pass){
            $messages[] = "Password not match!"; 
          }
        }
        // validate role
        if(!validate($role,'empty')){
          $messages[] = 'Please Choose Role!';  
        }
        elseif(!validate($role,'num')){
          $messages[] = 'Oops, Something went Wrong, Please try again!';  
        }elseif(!in_array($role,roleArray())){
          $messages[] = 'Oops, Something went Wrong, Please try again!';  
        }
         // validate status
         elseif(!validate($status,'empty_2')){
           echo gettype($status);
          $messages[] = 'Please Choose Status!';  
        }
        elseif(!in_array($status,[0,1])){
          $messages[] = 'Oops, Something went Wrong, Please try again!';  
        }



        // count message
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
        }else{
          
          // check if email exist 
          $sql = "SELECT `email` FROM `users` WHERE `email` = '{$email}' AND `id` != '{$userRow['id']}'";
          $query_check_email = mysqli_query($conn,$sql);
          $count = mysqli_num_rows($query_check_email);

          if($count > 0){
            $notifications[] = "<div class='alert alert-danger' role='alert'>Email already Exist!</div>";
          }else{

            if(validate($password,'empty')){
              $password = password_hash($password , PASSWORD_BCRYPT, ['cost' => 12]);
            }
            
              //UPDATE
              $sql = "UPDATE `users` SET `full_name` = '{$name}',`email` = '{$email}', ";
              if(validate($password,'empty')){
              $sql .= "`password` = '{$password}', ";
              }
              $sql .= "`role_id` = {$role},`status` = {$status},`is_approved` = 1,`action_id` = 0 ";
              $sql .= " WHERE `id` = {$userRow['id']}";
                                                        
                                      
              $query_user = mysqli_query($conn,$sql);
              if($query_user){
                setMessage("User Updated Successfully!",'success');
                redirectHeader('dashboard.php'); 
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
      <form action="<?php echo $_SERVER['PHP_SELF']."?id=".base64_encode($userRow['id'])?>" method="POST">
        <div class="form-row">
          <div class="form-group col-md-6">
            <!-- name -->
            <label for="inputEmail4">Full Name</label>
            <!-- will use readonly attr for agents in some areas  -->
            <input type="text" name="name" class="form-control" id="inputEmail4"  
            value="<?= isset($_POST['name']) ? $_POST['name'] : $userRow['full_name'] ?>">
          </div>
          <div class="form-group col-md-6">
            <!-- email -->
            <label for="inputPassword4">email</label>
            <input type="text" name="email" class="form-control" id="inputPassword4" 
            value="<?= isset($_POST['email']) ? $_POST['email'] : $userRow['email'] ?>"
            >
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <!-- password -->
            <label for="inputAddress">New Password</label>
            <input type="password" name="password" class="form-control" id="inputAddress">
          </div>
          <div class="form-group col-md-6">
            <!-- confirm password -->
            <label for="inputAddress2">Confirm password</label>
            <input type="password" name="con_pass" class="form-control" id="inputAddress2">
          </div>
          <div class="form-group col-md-6">
            <!-- status -->
            <label for="inputState">Status</label>
            <select id="inputState" name="status" class="form-control">
              <option value="1" <?= (intval($userRow['status']) === 1 ? 'selected': '')?> >Active</option>
              <option value="0" <?= (intval($userRow['status']) === 0 ? 'selected': '')?>>Deactivate</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <!-- roles -->
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
                  $selected = intval($userRow['role_id']) === intval($rows['id']) ? 'selected' : ''; 
                  echo "<option value='{$row_id}' {$selected} >{$row_name}</option>
                  ";
              } 
            ?> 
            </select>
          </div>
        </div>
        <?php 
          if( isset($_SESSION['message'])){
            displayMessage();
          }
          // err msg
          if(count($notifications) > 0){
           echo $notifications[0];
          }
        ?>
        <button type="submit" name="update_agent" class="btn btn-info">Update Agent</button>
      </form>
    </div>
</div>

<?php
require_once('inc/footer.php');

