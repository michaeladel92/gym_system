<?php 

require("inc/init.php");
require_once("inc/nav.php");

$id = $_GET['id'];
$messages    = [];
# Start Validation .... 
if(!validate($id,'empty')){
    $messages[] = 'Please Enter Field Required!';  
  }
  elseif(!validate($id,'int')){

    $messages[] = "Invalid id";
}



if(count($messages) > 0){

    $_SESSION['Message']  = $messages;
}else{

  # Delete Logic ..... 

  $sql = "delete from roles where id = $id";
  $op  = mysqli_query($conn,$sql);
   
  if($op){
      $messages = ['Raw Removed'];
  }else{
      $messages = ["Error In Process try again"];
  }

   
  $_SESSION['Message']  = $messages;

   header("Location: role_index.php");

}







?>