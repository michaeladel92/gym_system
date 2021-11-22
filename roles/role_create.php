<?php 
  require("../inc/init.php");
  require_once("../inc/nav.php");
// Session not available 
isSessionIdNotAvailable('Please Login to procceed!','danger','../login.php');
// check if role is admin
isAdmin('Access Denied!','danger','../index.php');
// check if account is active
isStatusActive();
  
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
       $sql = "SELECT `role` FROM `roles` WHERE `role` = '{$title}'";
       $check_query = mysqli_query($conn,$sql);
       $count = mysqli_num_rows($check_query);
       
       if($count > 0){
        $notifications[] = "<div class='alert alert-danger' role='alert'>[<b>$title</b>] Exist in our database!</div>";

       }else{
             # INSERT Role
            $sql = "insert into roles (role) values ('$title')";
            $op  = mysqli_query($conn,$sql);
            if($op){
                setMessage("Role Added Successfully!",'success');
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
            <h1>Add Role</h1>
        <?php 
          if( isset($_SESSION['message'])){displayMessage();}
          // err msg
          if(count($notifications) > 0){echo $notifications[0];}
       
        ?>
            <div class="container">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">

                    <div class="form-group">
                        <input type="text" class="form-control" name="title" id="exampleInputName" aria-describedby=""
                            placeholder="Enter Role Title">
                    </div>

                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </main>
    <?php 
    require_once('../inc/footer.php');