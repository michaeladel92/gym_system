<?php
require_once("init.php");


/*==============
sql Querys
==============*/ 

// role ids in array
function roleArray(){
  global $conn;
  $sql            = "SELECT `id` FROM `roles`";
  $role_id_query  = mysqli_query($conn,$sql); 
  while($role_ids = mysqli_fetch_assoc($role_id_query)){
    $role_id_array[] = $role_ids['id'];
  }
  return $role_id_array;
}

// count new members
function countDailyNewMember(){
  global $conn;
$sqlNewMembers = "SELECT `id` FROM `membership_track` WHERE `updated_at` >= CURDATE()";
$newMembers = mysqli_query($conn,$sqlNewMembers);
return $countNewMembers = mysqli_num_rows($newMembers);
}

// is status active
function isStatusActive(){
  global $conn;
  $sql   = "SELECT `status` FROM `users` WHERE `id` = {$_SESSION['id']} LIMIT 1";
  $query = mysqli_query($conn,$sql);
  $row   = mysqli_fetch_assoc($query);  

  if(intval($row['status']) !== 1){ //Agent account not active 
    unset($_SESSION['id']);
    unset($_SESSION['full_name']);
    unset($_SESSION['agent_code']);
    unset($_SESSION['email']);
    unset($_SESSION['status']);
    unset($_SESSION['is_approved']);
    unset($_SESSION['role_id']);
    $host = $_SERVER['HTTP_HOST'];
    $path = "http://$host/gym/login.php";
    setMessage("Your Account has been Deactivated, please contact your Administrator ",'danger');
    redirectHeader($path);
  }
}

// is user approved
function isUserApproved($message,$type){
  global $conn;
  $sql   = "SELECT `is_approved` FROM `users` WHERE `id` = {$_SESSION['id']} LIMIT 1";
  $query = mysqli_query($conn,$sql);
  $row   = mysqli_fetch_assoc($query);  

  if(intval($row['is_approved']) !== 1){ //didnt change password yet
    setMessage($message,$type);
    $location = "edit_user.php?id=". base64_encode($_SESSION['id']);
    redirectHeader($location);
  }
}

/*==============
SESSION Check
==============*/ 
// redirect to certian page if session id  set
function isSessionIdAvailable($message,$type,$location){
  if(isset($_SESSION['id'])){
    setMessage($message,$type);
    redirectHeader($location);
  }
}

// redirect to certian page if session id not set
function isSessionIdNotAvailable($message,$type,$location){
  if(!isset($_SESSION['id'])){
    setMessage($message,$type);
    redirectHeader($location);
  }
}
// check if user type is admin or not
function isAdmin($message,$type,$location){
  if($_SESSION['role_id'] !== 1){ // 1 id is admin
    setMessage($message,$type);
    redirectHeader($location);
  }
}

 // check if user type is admin or or manager
 function isAdminOrManager($message,$type,$location){
  if($_SESSION['role_id'] !== 1 && $_SESSION['role_id'] !== 2){ // 1 id is admin
    setMessage($message,$type);
    redirectHeader($location);
  }
}







// clean all
function clean($inputValue,$check){
  global $conn;
  $clean = trim($inputValue);
  // $clean = str_replace("  "," ",$clean);
  $clean = preg_replace('/\s+/', ' ', $clean);
  $clean = htmlspecialchars($clean);
  $clean = stripslashes($clean);
  $clean = strtolower($clean);
  $clean = mysqli_real_escape_string($conn,$clean);
  switch ($check) {
    case ($check === 'string'):
            $clean = filter_var($clean, FILTER_SANITIZE_STRING);
          break;
    case ($check === 'email'):
            $clean = filter_var($clean, FILTER_SANITIZE_EMAIL);
          break;
    case ($check === 'url'):
            $clean = filter_var($clean, FILTER_SANITIZE_URL);
          break;
    case ($check === 'num'):
            $clean = filter_var($clean, FILTER_SANITIZE_NUMBER_INT);
          break;
          
  }
  return $clean;
}

// trim password
function cleanPassword($input){
  $password    = trim($input);
  $password    = str_replace(' ', '', $password);
  return $password;
}

// validate
function validate($input,$flag,$length = 6){
  $status = true;
  switch($flag){
      case"empty":
                if(empty($input)){$status = false;}
        break;
      case"empty_2":
          if($input === ''){$status = false;}
        break;
      case "email":
                if(!filter_var($input,FILTER_VALIDATE_EMAIL)){$status = false;}
        break;  
      case "url":
                if(!filter_var($input,FILTER_VALIDATE_URL)){$status = false;}   
        break;
      case "min":
          if(strlen($input) < $length){$status = false;}   
        break;
      case "max":
          if(strlen($input) > $length){$status = false;}   
        break;
      case "phone":
          if(!preg_match('/^01[0-2,5][0-9]{8}$/',$input)){$status = false;}   
        break;
      case "string": 
          if(!preg_match('/^[a-zA-Z\s]*$/',$input)){$status = false;}
        break;
      case "int": 
          if(!filter_var($input,FILTER_VALIDATE_INT)){$status = false;}
        break;
      case "num":
          if(!filter_var($input,FILTER_VALIDATE_INT)){$status = false;}   
        break;  
  }
  return $status;
}

// set message
function setMessage($message,$type = 'warning'){
  $container = "
  <div class='alert alert-$type alert-dismissible fade show' role='alert'>
                            $message
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span>
                                </button>
                             </div>
  ";
  
  $_SESSION['message'] = $container ;
}

//display message
function displayMessage(){
  echo $_SESSION['message'];
  unset($_SESSION['message']);
}

// redirect page
function redirectHeader($location){
  header("location:$location");
  exit;
  }

 //close connection
 function closeConn(){
   global $conn;
  if($conn){
    mysqli_close($conn);
  }
 } 


// date validate
function isRealDate($date) { 
  if (false === strtotime($date)) { 
      return false;
  } 
  list($year, $month, $day) = explode('-', $date); 
  return checkdate($month, $day, $year);
}