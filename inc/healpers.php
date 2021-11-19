<?php
require_once("init.php");



// clean all
function clean($inputValue,$check){
  global $conn;
  $clean = trim($inputValue);
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


