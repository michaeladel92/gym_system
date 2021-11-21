<?php 

require("inc/init.php");
require_once("inc/nav.php");

  
# GET RAW Data .... 
$id = $_GET['id'];
$messages    = [];

# Start Validation .... 

if(!validate($id,'empty')){
    $messages[] = 'Please Enter Field Required!';  

  }elseif(!validate($id,'int')){

    $messages[] = "Invalid id";
}


if(count($messages) > 0){

    

    $_SESSION['Message'] = $messages;

    header("Location: role_index.php");
    exit();
}else{

 $sql = "select * from roles where id = $id";
 $op  = mysqli_query($conn,$sql);
 $data = mysqli_fetch_assoc($op);

}


  if($_SERVER['REQUEST_METHOD'] == "POST"){

    $title = Clean($_POST['title'],'string');

    
    if(!validate($title,'empty')){
        $messages[] = 'Please Enter Field Required!';  
    }
  elseif(!validate($title,'string')){
        $messages[] = "Invalid String";
    }


    if(count($messages) > 0){
        $_SESSION['Message'] = $messages;
    }else{

       # Db Operation ..... 

       $sql = "update roles set role = '$title'  where id = $id ";
       $op  = mysqli_query($conn,$sql);

       if($op){
        $messages = ['Raw Updated'];
       }else{
        $messages = ['Error Try Again'];
       }
       $_SESSION['Message'] =$messages;
       
       header("Location: role_index.php");
       exit();

    }


  }  // end form Logic ..... 

 

?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
               
            <?php 
               
               if(isset($_SESSION['Message'])){
                   foreach($_SESSION['Message'] as $key => $val){
                      
                    echo '* '.$key.' : '.$val.'<br>';

                   }
                   unset($_SESSION['Message']);
               }else{  ?>

                    <li class="breadcrumb-item active">Update Role .</li>

            <?php   }   ?>

        
            
        
        </ol>




            <div class="container">

                <form action="role_edit.php?id=<?php echo $data['id']; ?>" method="post">


                    <div class="form-group">
                        <label for="exampleInputName">Title</label>
                        <input type="text" class="form-control" name="title" value="<?php echo $data['role'];?>" id="exampleInputName" aria-describedby=""
                            placeholder="Enter Role Title">
                    </div>



                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>





        </div>
    </main>


    <?php 

require_once('inc/footer.php');
?>