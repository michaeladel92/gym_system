<?php 
 
 require("inc/init.php");
 require_once("inc/nav.php");
 

 $sql = "select * from roles order by id desc";
 $op  = mysqli_query($conn,$sql);





?>


            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <?php 
               
               if(isset($_SESSION['Message'])){
                   foreach($_SESSION['Message'] as $key => $val){
                      
                    echo '* '.$val.'<br>';

                   }
                   unset($_SESSION['Message']);
               }else{  ?>

                    <li class="breadcrumb-item active">Display Roles</li>

            <?php   }   ?>



                        </ol>
                       

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                List Roles
                                <a href='role_create.php?' class='btn btn-primary m-r-1em'>add new role</a>       

                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Role Title</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Role Title</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                          
                                        
                             <?php  
                             
                                while($data = mysqli_fetch_assoc($op)){
                             
                             ?>           
                                            <tr>
                                                <td><?php echo $data['id'];?></td>
                                                <td><?php echo $data['role']?></td>
                                                <td>
                                                <a href='role_delete.php?id=<?php echo $data['id'];?>' class='btn btn-danger m-r-1em'>Delete</a>
                                                <a href='role_edit.php?id=<?php echo $data['id'];?>' class='btn btn-primary m-r-1em'>Edit</a>       
                                               </td>
                                            </tr>
                            <?php } ?>             

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
              

<?php 
require_once('inc/footer.php');

?>