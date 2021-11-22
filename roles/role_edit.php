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

    $title       = Clean($_POST['title'],'string');
    $messages    = [];
    
    if(!validate($title,'empty')){
        $messages[] = 'Please Enter Role Name!';  
      }
    elseif(!validate($title,'string')){
        $messages[] = "Invalid String";
    }
    elseif(!validate($title,'max',20)){
        $messages[] = "Maximum length is 20";
    }
    


    if(count($messages) > 0){
        foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
    }else{
       #check if exist
       $sql = "SELECT `role` FROM `roles` WHERE `role` = '{$title}' AND `id` != {$roleRow['id']}";
       $check_query = mysqli_query($conn,$sql);
       $count = mysqli_num_rows($check_query);
       
       if($count > 0){
        $notifications[] = "<div class='alert alert-danger' role='alert'>[<b>$title</b>] Exist in our database!</div>";

       }else{

             # Update Role
            $sql = "UPDATE roles SET role = '$title' WHERE `id` = {$roleRow['id']}";
            $op  = mysqli_query($conn,$sql);
            if($op){
                setMessage("Role Updated Successfully!",'success');
                redirectHeader('index.php');
            }else{
                $notifications[] = 'Oops, Something went wrong, Please try again!';
            }
       }
    }
  }
 

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid col-md-3">
            <h1>Edit Role</h1>
        <?php 
          if( isset($_SESSION['message'])){displayMessage();}
          // err msg
          if(count($notifications) > 0){echo $notifications[0];}
       
        ?>
            <div class="container">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=". base64_encode( $roleRow['id']);?>" method="post">

                    <div class="form-group">
                        <input type="text" class="form-control" name="title" id="exampleInputName" aria-describedby=""
                            placeholder="Enter Role Title" 
                            value = "<?=(isset($_POST['title']) ? $_POST['title']: $roleRow['role'] )?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </main>
    <?php 
    require_once('../inc/footer.php');