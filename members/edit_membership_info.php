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
 
    if(!validate($member_info_id,'num')){
      setMessage('Access Denied!','danger');
      redirectHeader('../index.php');
    }else{
      $sql = "SELECT * FROM `membership_info` WHERE `id` = {$member_info_id}";
      $getMembersQuery = mysqli_query($conn,$sql);
      $count = mysqli_num_rows($getMembersQuery);
      if($count === 0){
        setMessage('Member Not found!','danger');
        redirectHeader('../index.php');
      }else{
        $MemberRow = mysqli_fetch_assoc($getMembersQuery); 
    
      }
    }
  }

  $notifications = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['update_info'])){
      $messages    = [];
      $name        = clean($_POST['name'],'string');
      $phone       = clean($_POST['phone'],'string');
      $comment     = clean($_POST['comments'],'string');
      $member_id   = intval($MemberRow['id']);
      $max         = 40;
      $min         = 3;

    
          //validate name
        if(!validate($name,'empty')){
          $messages[] = 'Please Enter full Member Name!';  
        }
        elseif(!validate($name,'string')){
          $messages[] = 'Invalid String, Accept Char only [a - z]!';  
        }
        elseif(!validate($name,'min',$min)){
          $messages[] = "min. char for Member Name is $min";  
        }
        elseif(!validate($name,'max',$max)){
          $messages[] = "max. char for Member Name is $max";  
        }
        //validate phone
        elseif(!validate($phone,'empty')){
          $messages[] = 'Please Enter Member Phone!';  
        }
        elseif(!validate($phone,'phone')){
          $messages[] = "Incorrect Phone Format!";
        }
        // validate Comment
        elseif(validate($comment,'empty')){
          if(!validate($comment,'min',10)){
            $messages[] = "Minimum Accepted char for Comment is 10";  
          }elseif(!validate($comment,'max',250)){
            $messages[] = "Maximum Accepted char for Comment is 250"; 
          }
        }

       
        // count message
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
        }
        else{
          $location = "membership_profile.php?id=".base64_encode($member_info_id);
          // check if phone exist in db
          $sql = "SELECT `phone` FROM `membership_info` WHERE `phone` = '{$phone}' AND `id` != {$member_id}";
          $query_check_phone = mysqli_query($conn,$sql);
          $count = mysqli_num_rows($query_check_phone);

          if($count > 0){
            $notifications[] = "<div class='alert alert-danger' role='alert'>Phone already Exist!</div>";
          }else{
                
              $today = date("Y-m-d G:i:s",strtotime('now'.' '.$currentTime)); 
              $sessionUserId = $_SESSION['id'];
             
              // UPDATE MemberInfo
              $sql = "UPDATE `membership_info` SET `full_name` = '{$name}',`phone` = '{$phone}' ";
              $sql .= "WHERE `id` =  {$member_id} ";
              $query_member_info = mysqli_query($conn,$sql);
              if($query_member_info){
                
                  //INSERT membership_user
                  $sql = "INSERT INTO `member_user` (`user_id`,`member_id`) ";
                  $sql .= "VALUES({$sessionUserId},{$member_id})";
                  $query_member_user = mysqli_query($conn,$sql);
                  if($query_member_user){
                     
                        // INSER Comment if available
                            if(validate($comment,'empty')){
                              $sql = "INSERT INTO `comments` (`comment`,`user_id`,`member_id`) ";
                              $sql .= "VALUES ('{$comment}',{$sessionUserId},{$member_id})";
                              $query_comment = mysqli_query($conn,$sql);
                              if($query_comment){
                                setMessage("Member Info Updated Successfully!",'success');
                                redirectHeader($location);
                              }else{                                
                                $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went Wrong, Please try again!</div>";
                              }
                            }else{

                              $message = "Old Info:( .".$MemberRow['full_name'].
                                         " - " .$MemberRow['phone'].") ";
                              $message .= "Changed by:(".$_SESSION['full_name']. "[".$_SESSION['agent_code']."]" . "ON:".$today. ")";

                              $sql = "INSERT INTO `comments` (`comment`,`user_id`,`member_id`) ";
                              $sql .= "VALUES ('{$message}',{$sessionUserId},{$member_id})";
                              $query_comment = mysqli_query($conn,$sql);
                              if($query_comment){
                                setMessage("Member Info Updated Successfully!",'success');
                                redirectHeader($location);
                              }else{                                
                                $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went Wrong, Please try again!</div>";
                              }
                            }
                  }else{
                    $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went Wrong, Please try again!</div>";
                  }  
              }else{
                $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went Wrong, Please try again!</div>";

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
          <div class="form-group col-md-6">
            <!-- name -->
            <label for="inputEmail4">Full Name</label>
            <input name="name" type="text" class="form-control" id="inputEmail4"
            value="<?= isset($_POST['name']) ? $_POST['name'] : $MemberRow['full_name'] ?>">
          </div>
          <div class="form-group col-md-6">
            <!-- phone -->
            <label for="inputPassword4">phone</label>
            <input name="phone" type="number" class="form-control" 
            value="<?= isset($_POST['phone']) ? $_POST['phone'] : $MemberRow['phone'] ?>"
            >
          </div>
          <!-- comment -->
          <div class="form-group col-md-12">
            <label for="exampleFormControlTextarea1">Comment</label>
            <textarea name="comments" class="form-control" style="resize:none;height:10rem;"><?= isset($_POST['comments']) ? $_POST['comments'] : '' ?></textarea>
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
        <button type="submit" name="update_info" class="btn btn-info">Update Info</button>
      </form>
    </div>
</div>

<?php
require_once('../inc/footer.php');