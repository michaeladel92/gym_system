<?php 

require("../inc/init.php");
require_once("../inc/nav.php");
// Session not available 
isSessionIdNotAvailable('Please Login to procceed!','danger','../login.php');
// check if role is admin
isAdmin('Access Denied!','danger','../index.php');
// check if account is active
isStatusActive();

 
  // get id
  if(!isset($_GET['id']) || $_GET['id'] === ''){
    setMessage('Access Denied!','danger');
    redirectHeader('index.php');

  }else{
    // decode id
    $role_id = base64_decode($_GET['id']);
    $role_id = clean($role_id,'num');

    if(!validate($role_id,'num')){
      setMessage('Access Denied!','danger');
      redirectHeader('index.php');
    }else{
      $sql = "SELECT * FROM `roles` WHERE `id` = {$role_id} LIMIT 1";
      $getRolesQuery = mysqli_query($conn,$sql);
      $count = mysqli_num_rows($getRolesQuery);
      if($count === 0){
        setMessage('Role Not found!','danger');
        redirectHeader('index.php');
      }else{
        $roleRow = mysqli_fetch_assoc($getRolesQuery); 
      }
    }
  }



  $notifications = [];
  if($_SERVER['REQUEST_METHOD'] == "POST"){

     #check if exist
     $sql = "SELECT `role` FROM `roles` WHERE `id` = {$roleRow['id']} LIMIT 1";
     $check_query = mysqli_query($conn,$sql);
     $count = mysqli_num_rows($check_query);
     
     if($count > 0){
         $sql = "DELETE FROM `roles` WHERE `id` = {$roleRow['id']} LIMIT 1";
         $del_query = mysqli_query($conn,$sql);
         if($del_query){
         setMessage("Role Deleted Successfully!",'success');
         redirectHeader('index.php');
         }
         else{
            setMessage("Oops, Something went wrong, Please make sure that No Agent enrolled with that role!",'danger');
            redirectHeader('index.php');
         }   
     
     }else{
        setMessage("Role Not Found!",'danger');
        redirectHeader('index.php');
     }
  }
 

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid col-md-3">
            <h5 class="m-3">Are your Sure you want to Delete "<b><?=$roleRow['role']?>"</b></h5>
        <?php 
          if( isset($_SESSION['message'])){displayMessage();}
          // err msg
          if(count($notifications) > 0){echo $notifications[0];}
       
        ?>
            <div class="container">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=". base64_encode( $roleRow['id']);?>" method="post">

                    <button type="submit" class="btn btn-success">Yes</button>
                    <a href="index.php" class="btn btn-danger">No</a>
                </form>
            </div>
        </div>
    </main>
    <?php 
    require_once('../inc/footer.php');