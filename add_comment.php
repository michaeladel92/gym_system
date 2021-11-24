<?php
  require("inc/init.php");
  require_once("inc/nav.php");

  // check if session not set
  isSessionIdNotAvailable('Please login to procceed!','danger','login.php');
  // check if account is active
  isStatusActive();
  // did agent account approved
  isUserApproved("Access Denied!, Please change you're password to active your Account!",'danger');
  
  // get id
  if(!isset($_GET['id']) || $_GET['id'] === '' ){
    setMessage('Access Denied!','danger');
    redirectHeader('index.php');

  }else{
    // decode id
    $member_info_id = base64_decode($_GET['id']);
    $member_info_id = clean($member_info_id,'num');


    if(!validate($member_info_id,'num')){
      setMessage('Access Denied!','danger');
      redirectHeader('index.php');
    }
  }

  $notifications = [];
  if($_SERVER['REQUEST_METHOD'] === "POST"){
      if(isset($_POST['extend_member'])){
        $messages    = [];
     
    
        $comment     = clean($_POST['comments'],'string');

       

          // validate Comment
          if(validate($comment,'empty')){
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
        
      
                    
             
                
                  // INSER Comment 
                      if(validate($comment,'empty')){
                        $member_track_id = intval(mysqli_insert_id($conn));
                        $sql = "INSERT INTO `comments` (`comment`,`user_id`,`member_id`) ";
                        $sql .= "VALUES ('{$comment}',{$_SESSION['id']},{$member_info_id})";
                        $query_comment = mysqli_query($conn,$sql);
   
                        if($query_comment){
                          setMessage("comment Added Successfully!",'success');
                          redirectHeader('index.php');
                     
                      }else{
                        setMessage("error!",'danger');
                        redirectHeader('index.php'); 
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
        <button type="submit" name="extend_member" class="btn btn-info">add comment</button>
      </form>
    </div>
</div>

<?php
require_once('inc/footer.php');