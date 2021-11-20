<?php
  require("inc/init.php");
  require_once("inc/nav.php");
  

  $notifications = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['new_member'])){
      $messages    = [];
      $name        = clean($_POST['name'],'string');
      $phone       = clean($_POST['phone'],'string');
      $start_date  = clean($_POST['start_date'],'string');
      $end_date    = clean($_POST['end_date'],'string');
      $subscribe   = $_POST['subscribe'];
      $price       = clean($_POST['price'],'num');
      $price       =  intval($price);
      $bill        = clean($_POST['bill'],'string');
      $currentTime = date("G:i:s",strtotime('now'));
      $yesterday   = date("Y-m-d",strtotime('yesterday'));
      $max         = 40;
      $min         = 3;
      $subscribtionArr = ["+1 Day","+1 Month","+2 Month","+3 Month","+4 Month","+5 Month","+6 Month","+7 Month","+8 Month","+9 Month","+10 Month","+11 Month","+1 Year","+2 Year"];
    
          //validate name
        if(!validate($name,'empty')){
          $messages[] = 'Please Enter full Member Name!';  
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
        elseif(!validate($phone,'phone',11)){
          $messages[] = "Phone number must include 11 digits";  
        }
        // validate start_date
        elseif(validate($start_date,'empty')){

            if(date($start_date) <= date($yesterday)){
              $messages[] = "Its not Possible to Reserve PASS DUE Date!";
              // validate end_date
            } elseif(!validate($end_date,'empty')){
              $messages[] = "Please Enter End Date!";
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



        // count message
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
        }
        else{
          
          // check if email exist in db
          $sql = "SELECT `phone` FROM `membership_info` WHERE `phone` = '{$phone}'";
          $query_check_phone = mysqli_query($conn,$sql);
          $count = mysqli_num_rows($query_check_phone);

          if($count > 0){
            $notifications[] = "<div class='alert alert-danger' role='alert'>Phone already Exist!</div>";
          }else{
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
              $user_test_id = 1;

              // INSERT MemberInfo
              $sql = "INSERT INTO `membership_info` (`full_name`,`phone`) ";
              $sql .= "VALUES('{$name}','{$phone}')";
              $query_member_info = mysqli_query($conn,$sql);
              if($query_member_info){
                  $member_info_id = intval(mysqli_insert_id($conn));
                  //INSERT membership_user
                  $sql = "INSERT INTO `member_user` (`user_id`,`member_id`) ";
                  $sql .= "VALUES({$user_test_id},{$member_info_id})";
                  $query_member_user = mysqli_query($conn,$sql);
                  if($query_member_user){
                      $member_user_table_id = intval(mysqli_insert_id($conn));
                      //INSERT membership_track
                      $sql = "INSERT INTO `membership_track` (`price`,`bill`,`start_date`,`end_date`,`user_id`,`member_id`,`updated_at`) ";
                      $sql .= "VALUES({$price},'{$bill}','{$final_start_date}','{$final_end_date}',{$user_test_id},{$member_info_id},'{$today}')";
                      $query_member_track = mysqli_query($conn,$sql);
                      if($query_member_track){
                            setMessage("Member Added Successfully!",'success');
                            redirectHeader('add_membership.php'); 
                      }else{
                        $sql = "DELETE FROM `membership_track` WHERE `id` = {$member_user_table_id} LIMIT 1";
                        mysqli_query($conn,$sql);
                        $sql = "DELETE FROM `membership_info` WHERE `id` = {$member_info_id} LIMIT 1";
                        mysqli_query($conn,$sql);
                        $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, Something went Wrong, Please try again!</div>";
                      }
                  }else{
                    $sql = "DELETE FROM `membership_info` WHERE `id` = {$member_info_id} LIMIT 1";
                    mysqli_query($conn,$sql);
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
            value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
          </div>
          <div class="form-group col-md-6">
            <!-- phone -->
            <label for="inputPassword4">phone</label>
            <input name="phone" type="number" class="form-control" 
            value="<?= isset($_POST['phone']) ? $_POST['phone'] : '' ?>"
            >
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <!-- start date -->
            <label for="inputAddress">start date</label>
            <input name="start_date" type="date" class="form-control" id="inputAddress">
          </div>
          <div class="form-group col-md-6">
            <!-- end date -->
            <label for="inputAddress2">end date</label>
            <input name="end_date" type="date" class="form-control" id="inputAddress2">
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
            value="<?= isset($_POST['price']) ? $_POST['price'] : '' ?>">
          </div>
          <div class="form-group col-md-3">
            <!-- bill -->
            <label for="inputState">bill</label>
            <input type="text" name="bill" class="form-control"
            value="<?= isset($_POST['bill']) ? $_POST['bill'] : '' ?>">
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
        <button type="submit" name="new_member" class="btn btn-info">New Member</button>
      </form>
    </div>
</div>

<?php
require_once('inc/footer.php');