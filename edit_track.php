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
  // redirectHeader('index.php');

}else{
  // decode id
  $member_track_id = base64_decode($_GET['id']);
  $member_track_id = clean($member_track_id,'num');


  if(!validate($member_track_id,'num')){
    setMessage('Access Denied!','danger');
    // redirectHeader('index.php');
  }else{
    $sql = "SELECT 
                `membership_track`.*,
                `membership_info`.*
              FROM 
                `membership_track`
              INNER JOIN 
                `membership_info`
              ON
              `membership_info`.`id` = `membership_track`.`member_id` 
              WHERE 
                  `membership_track`.`id` = {$member_track_id}
                   ";
    $getTrackQuery = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($getTrackQuery);
    if($count === 0){
      setMessage('Member Track Not found!','danger');
      redirectHeader('index.php');
    }else{
      $TrackRow = mysqli_fetch_assoc($getTrackQuery); 
    }
  }
}


  $notifications = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['update_member_track'])){
      $messages    = [];
      $start_date  = clean($_POST['start_date'],'string');
      $end_date    = clean($_POST['end_date'],'string');
      $subscribe   = $_POST['subscribe'];
      $price       = clean($_POST['price'],'num');
      $price       = intval($price);
      $bill        = clean($_POST['bill'],'string');
      $currentTime = date("G:i:s",strtotime('now'));
      $yesterday   = date("Y-m-d",strtotime('yesterday'));
      $comment     = clean($_POST['comments'],'string');
      $max         = 40;
      $min         = 3;
      $subscribtionArr = ["+1 Day","+1 Month","+2 Month","+3 Month","+4 Month","+5 Month","+6 Month","+7 Month","+8 Month","+9 Month","+10 Month","+11 Month","+1 Year","+2 Year"];
    
       
        // validate start_date
        if(validate($start_date,'empty')){
            if(!isRealDate($start_date)){
                $messages[] = "Invalid Start-Date Format";
            }elseif(date($start_date) <= date($yesterday)){
              $messages[] = "Its not Possible to Reserve PASS DUE Date!";
              // validate end_date
            } elseif(!validate($end_date,'empty')){
              $messages[] = "Please Enter End Date!";
            } elseif(!isRealDate($end_date)){
              $messages[] = "Invalid End-Date Format";
            } elseif(date($start_date) > date($end_date)){
              $messages[] = "The end date Entered cannot be before the start date!";
            }
        }
        // validate Subscription
        if(!validate($start_date,'empty') && !validate($end_date,'empty')){
            if(!validate($subscribe,'empty')){
              $messages[] = 'Please Choose Membership Subscribtions!';  
            } elseif(!in_array($subscribe,$subscribtionArr)){
              $messages[] = 'Oops, Something went Wrong, Please try again!';  
             }
        }
        // validate Price
        if(!validate($price,'empty')){
          $messages[] = 'Please Enter Subscription Price!';  
        }elseif(!validate($price,'max',99999)){
          $messages[] = 'Maximum Accepted Price per Subscribtion is 99,999LE!';  
        }
        // validate bill
        elseif(!validate($bill,'empty')){
          $messages[] = 'Please Enter Bill Code!';  
        }elseif(!validate($bill,'max',$max)){
          $messages[] = 'Maximum Accepted Price per Subscribtion is 99,999LE!';  
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
         

              // Adjust date and time
              $final_start_date = '';
              $final_end_date   = '';
              if(validate($start_date,'empty')){
                  $final_start_date = date("Y-m-d G:i:s",strtotime($start_date.' '.$currentTime));
                  $final_end_date   = date("Y-m-d G:i:s",strtotime($end_date.' '.$currentTime));
              }else{
                $final_start_date = date("Y-m-d G:i:s",strtotime('now'.' '.$currentTime));
                $final_end_date   = date("Y-m-d G:i:s",strtotime($subscribe.' '.$currentTime));
              }
                  
              $today = date("Y-m-d G:i:s",strtotime('now'.' '.$currentTime)); 
              $sessionUserId = $_SESSION['id'];
             
              //UPDATE membership_track
              $sql = "UPDATE `membership_track` SET
                                                 `price` = {$price},
                                                 `bill` = '{$bill}',
                                                 `start_date` = '{$final_start_date}',
                                                 `end_date` = '{$final_end_date}',
                                                 `updated_at` = '{$today}',
                                                 `action_id` = {$sessionUserId}
                                                WHERE 
                                                  `id` = {$member_track_id}  
                                                  ";
              $query_member_track = mysqli_query($conn,$sql);
              if($query_member_track){
                // INSER Comment if available
                    if(validate($comment,'empty')){
                      $member_id   = intval($TrackRow['member_id']);
                      $sql = "INSERT INTO `comments` (`comment`,`user_id`,`member_id`) ";
                      $sql .= "VALUES ('{$comment}',{$sessionUserId},{$member_id })";
                      $query_comment = mysqli_query($conn,$sql);
                      if($query_comment){
                        setMessage("Track Updated Successfully!",'success');
                        redirectHeader('index.php');
                      }else{                                
                        $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went Wrong, Please try again!</div>";
                      }
                    }else{
                      setMessage("Track Updated Successfully!",'success');
                      redirectHeader('index.php'); 
                    }
              }else{
                $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went Wrong, Please try again!</div>";
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
            <input disabled name="name" type="text" class="form-control" id="inputEmail4"
            value="<?= isset($_POST['name']) ? $_POST['name'] : $TrackRow['full_name'] ?>">
          </div>
          <div class="form-group col-md-6">
            <!-- phone -->
            <label for="inputPassword4">phone</label>
            <input disabled name="phone" type="number" class="form-control" 
            value="<?= isset($_POST['phone']) ? $_POST['phone'] : $TrackRow['phone'] ?>"
            >
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <!-- start date -->
            <label for="inputAddress">start date</label>
            <input  name="start_date" value="<?=(isset($_POST['start_date']) ? $_POST['start_date'] : date("Y-m-d",strtotime($TrackRow['start_date'])))?>" type="date" class="form-control" id="inputAddress">
          </div>
          <div class="form-group col-md-6">
            <!-- end date -->
            <label for="inputAddress2">end date</label>
            <input name="end_date" value="<?=(isset($_POST['end_date']) ? $_POST['end_date'] : date("Y-m-d",strtotime($TrackRow['end_date'])))?>" type="date" class="form-control" id="inputAddress2">
          </div>
          <div class="form-group col-md-6">
            <!-- subscribe -->
            <label for="inputState">Subscription</label>
            <select name="subscribe" id="inputState" class="form-control">
              <option value ="">choose Subscription</option>
              <option value ="+1 Day">1 day</option>
              <option value ="+1 Month">1 Month</option>
              <option value ="+2 Month">2 Month</option>
              <option value ="+3 Month">3 Month</option>
              <option value ="+4 Month">4 Month</option>
              <option value ="+5 Month">5 Month</option>
              <option value ="+6 Month">6 Month</option>
              <option value ="+7 Month">7 Month</option>
              <option value ="+8 Month">8 Month</option>
              <option value ="+9 Month">9 Month</option>
              <option value ="+10 Month">10 Month</option>
              <option value ="+11 Month">11 Month</option>
              <option value ="+1 Year">1 year</option>
              <option value ="+2 Year">2 year</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <!-- price -->
            <label for="inputState">Price</label>
            <input type="number" name="price" class="form-control"  
            value="<?= isset($_POST['price']) ? $_POST['price'] : $TrackRow['price'] ?>">
          </div>
          <div class="form-group col-md-3">
            <!-- bill -->
            <label for="inputState">bill</label>
            <input type="text" name="bill" class="form-control"
            value="<?= isset($_POST['bill']) ? $_POST['bill'] : $TrackRow['bill'] ?>">
          </div>
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
        <button type="submit" name="update_member_track" class="btn btn-info">Update Track</button>
      </form>
    </div>
</div>

<?php
require_once('inc/footer.php');