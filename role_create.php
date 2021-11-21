<?php 
  require("inc/init.php");
  require_once("inc/nav.php");
  
  if($_SERVER['REQUEST_METHOD'] == "POST"){

    $title = Clean($_POST['title'],'string');

    $messages    = [];

   
    
    
    if(!validate($title,'empty')){
        $messages[] = 'Please Enter Field Required!';  
      }  elseif(!validate($title,'string')){
        $messages[] = "Invalid String";
    }
    


    if(count($messages) > 0){
        $_SESSION['Message'] = $messages;
    }else{

       # Db Operation ..... 

       $sql = "insert into roles (role) values ('$title')";
       $op  = mysqli_query($conn,$sql);

       if($op){
        setMessage("role Added Successfully!",'success');
       }else{
        $messages[] = ['Error Try Again'];
       }
       $_SESSION['Message'] = $messages;
       
       header("Location: role_index.php");
       exit();

    }


  }  // end form Logic ..... 



?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            
            <ol class="breadcrumb mb-4">
               
            <?php 
               
               if(isset($_SESSION['Message'])){
                   foreach($_SESSION['Message'] as $key => $val){
                      
                    echo '* '.$key.' : '.$val.'<br>';

                   }
                   unset($_SESSION['Message']);
               }else{  ?>

                    <li class="breadcrumb-item active">ADD NEW ROLE</li>

            <?php   }   ?>

        
            
        
        </ol>




            <div class="container">

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">


                    <div class="form-group">
                        <label for="exampleInputName">Title</label>
                        <input type="text" class="form-control" name="title" id="exampleInputName" aria-describedby=""
                            placeholder="Enter Role Title">
                    </div>



                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>





        </div>
    </main>


    <?php 

require_once('inc/footer.php');
?>