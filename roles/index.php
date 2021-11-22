<?php 
 
 require("../inc/init.php");
 require_once("../inc/nav.php");

// Session not available 
isSessionIdNotAvailable('Please Login to procceed!','danger','../login.php');
// check if role is admin
isAdmin('Access Denied!','danger','../index.php');
// check if account is active
isStatusActive();
// did agent account approved
isUserApproved("Access Denied!, Please change you're password to active your Account!",'danger');

//  get all roles
 $sql = "select * from roles order by id desc";
 $op  = mysqli_query($conn,$sql);
?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid col-md-6">
                        <h1 class="mt-4">Roles</h1>
                        <?php if(isset($_SESSION['message'])){displayMessage();}?>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                        
                            <a href='role_create.php' class='btn btn-primary m-r-1em'>add role</a>       
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
                                        <tbody>
                                          
                                        
                             <?php while($data = mysqli_fetch_assoc($op)){ ?>           
                                            <tr>
                                                <td><?php echo $data['id'];?></td>
                                                <td><?php echo $data['role']?></td>
                                                <td>
                                                    <a href='role_edit.php?id=<?php echo base64_encode($data['id']);?>' class='btn btn-primary m-r-1em'>Edit</a>       
                                                    <a href='role_delete.php?id=<?php echo base64_encode($data['id']);?>' class='btn btn-danger m-r-1em'>X</a>
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
require_once('../inc/footer.php');