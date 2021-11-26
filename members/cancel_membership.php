<?php
  require("../inc/init.php");
  require_once("../inc/nav.php");
  // check if session not set
  isSessionIdNotAvailable('Please login to procceed!','danger','../login.php');
  // check if role is admin or manager
  isAdminOrManager('Access Denied!','danger','../index.php');
  // check if account is active
  isStatusActive();
  // did agent account approved
  isUserApproved("Access Denied!, Please change you're password to active your Account!",'danger');
  
  // get id
  if(!isset($_GET['id']) || $_GET['id'] === '' ){
    setMessage('Access Denied!','danger');
    redirectHeader('../index.php');

  }else{
    // decode id
    $member_info_id = base64_decode($_GET['id']);
    $member_info_id = clean($member_info_id,'num');
    $location = "membership_profile.php?id=".base64_encode($member_info_id);

    if(!validate($member_info_id,'num')){
      setMessage('Access Denied!','danger');
      redirectHeader('../index.php');
    }
  }

  $notifications = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['update_info'])){
      $messages    = [];
      $pass        = $_POST['pass'];
   
          //validate name
        if(!validate($pass,'empty')){
          $messages[] = 'Please Enter password!';  
        }
             
        // count message
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
        }
        else{

           // check if email exist in db
           $sql = "SELECT `password` FROM `users` WHERE `id` = '{$_SESSION['id']}' LIMIT 1";
           $query_check_pass = mysqli_query($conn,$sql);
           $row = mysqli_fetch_assoc($query_check_pass);
         
          
         if(!password_verify($pass,$row['password'])){
             $notifications[] = "<div class='alert alert-danger' role='alert'>Incorrect Entry password!</div>";
        }else{
          //update status TRACK 
          $sql =  "UPDATE `membership_track` SET `status` = 0 WHERE  `member_id` = {$member_info_id}";
          $op  = mysqli_query($conn,$sql);
    
          $sql = "DELETE FROM `membership_info` WHERE `id` = {$member_info_id} LIMIT 1";
          $del_query = mysqli_query($conn,$sql);
          if($del_query){
            setMessage("Membership Canceled Successfully!",'success');
            redirectHeader('../index.php');
          }else{
              setMessage("Oops, Something Went Wrong, Please try again!",'danger');
              redirectHeader($location);
          }
                  
          }
        }
    }
}  


?>
<style>
  /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
 <div class="container mt-5">
 <div class="offset-md-3 col-md-6">
      <form actions="<?php $_SERVER["PHP_SELF"];?>" method="POST">
        <div class="form-row">
        <h5 class="m-3">Please Confirm to Cancel Membership? </h5>

          <div class="form-group col-md-6">
            <!-- password -->
            <input name="pass" type="password" class="form-control" placeholder="Password" id="inputEmail4">
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
         <button type="submit" name="update_info" class="btn btn-success">Yes</button>
                    <a href="<?=$location?>" class="btn btn-danger">No</a>
      
      </form>
    </div>
</div>

<?php
require_once('../inc/footer.php');