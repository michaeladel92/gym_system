<?php
  require("../inc/init.php");
  require_once("../inc/nav.php");
  // check if session not set
  isSessionIdNotAvailable('Please login to procceed!','danger','../login.php');
  // check if account is active
  isStatusActive();
  // did agent account approved
  isUserApproved("Access Denied!, Please change you're password to active your Account!",'danger');
 
 
  if($_SERVER['REQUEST_METHOD'] === "GET"){
    // DELETE CCOMMENT
    if(isset($_GET['delete_comment']) && $_GET['delete_comment'] !== ''){
      // check if role is admin or manager
      isAdminOrManager('Access Denied!','danger','../index.php');
      $com_id = base64_decode($_GET['delete_comment']);
      $com_id = clean($com_id,'num');
      $sql   = "DELETE FROM `comments` WHERE `id` = $com_id LIMIT 1";
      $query = mysqli_query($conn,$sql);
      if($query){
        setMessage('Comment Deleted!','success');
        redirectHeader('../index.php');
      }else{
        setMessage('Oops, Something went wrong please try again!','danger');
        redirectHeader('../index.php');
      }
    }
}



  // get id
if(!isset($_GET['id']) || $_GET['id'] === '' ){
  setMessage('Access Denied!','danger');
  redirectHeader('../index.php');

}else{
  // decode id
  $member_id = base64_decode($_GET['id']);
  $member_id = clean($member_id,'num');


  if(!validate($member_id,'num')){
    setMessage('Access Denied!','danger');
    redirectHeader('../index.php');
  }else{
    
    $sql_info = "SELECT * FROM `membership_info` WHERE `id` = $member_id LIMIT 1";
    $getInfoQuery = mysqli_query($conn,$sql_info);
    $count = mysqli_num_rows($getInfoQuery);
    if($count === 0){
      setMessage('Member Info Not found!','danger');
      redirectHeader('../index.php');
    }else{
      $member_info = mysqli_fetch_assoc($getInfoQuery); 
    }
  }
}

  // GET all Active Members Track
  $sql = "SELECT 
                `membership_track`.*, 
                `membership_info`.`full_name` AS `member_name`, 
                `users`.`full_name` AS `user_name`,
                `users`.`agent_code`
              FROM
                `membership_track`
              INNER JOIN
                `membership_info`
              ON
                `membership_track`.`member_id` = `membership_info`.`id`
              INNER JOIN
                `users`
              ON
                `users`.`id` = `membership_track`.`user_id`
              WHERE
                `membership_track`.`member_id` = $member_id
              ORDER BY 
                 `membership_track`.`updated_at` DESC";
  $query_memberTrack = mysqli_query($conn,$sql);
  // GET All Comment
  $sql_comm = "  SELECT 
                      `comments`.*, 
                    
                      `users`.`full_name` AS `user_name`,
                      `users`.`agent_code`
                    FROM
                      `comments`

                    INNER JOIN
                      `users`
                    ON
                      `users`.`id` = `comments`.`user_id`
                    WHERE
                      `comments`.`member_id` = $member_id
                    ORDER BY 
                      `comments`.`created_at` DESC";
  $getCommQuery = mysqli_query($conn,$sql_comm);
  $countComment = mysqli_num_rows($getCommQuery);
  if($_SESSION['role_id'] === 1 || $_SESSION['role_id'] === 2){
  // Latest Update
    $sql_update = "  SELECT 
                        `member_user`.*, 
                      
                        `users`.`full_name` AS `user_name`,
                        `users`.`agent_code`
                      FROM
                        `member_user`

                      INNER JOIN
                        `users`
                      ON
                        `users`.`id` = `member_user`.`user_id`
                      WHERE
                        `member_user`.`member_id` = $member_id
                      ORDER BY 
                        `member_user`.`created_at` DESC";
    $getUpdateQuery = mysqli_query($conn,$sql_update);
  }

  $greenCircle ="<span style='width: 0.5rem;height: 0.5rem;background-color: green;display: inline-block;border-radius: 50%;box-shadow: 0 0 5px 0.5px green;'></span>";
  $yellowCircle = "<span style='width: 0.5rem;height: 0.5rem;background-color: #d7d72c;display: inline-block;border-radius: 50%;box-shadow: 0 0 5px 0.5px #d7d72c;'></span>";
  ?>
   <style>
body{background:#632778}.form-control:focus{box-shadow:none;border-color:#ba68c8}.profile-button{background:#632778;box-shadow:none;border:none}.profile-button:hover{background:#682773}.profile-button:focus{background:#682773;box-shadow:none}.profile-button:active{background:#682773;box-shadow:none}.back:hover{color:#682773;cursor:pointer}.labels{font-size:11px}.add-experience:hover{background:#ba68c8;color:#fff;cursor:pointer;border:solid 1px #ba68c8}
  </style>
<div class="container rounded bg-white">
    <div class="row">
        <div class="col-md-12 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
              <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
              <span class="font-weight-bold"><?php echo  $member_info['full_name']?></span>
              <span class="text-black-50"><?php echo  $member_info['phone']?></span>
              <span>
              <a  href="add_comment.php?id=<?= base64_encode($member_info['id'])?>"class="btn btn-primary profile-button" >Add Comment</a>
                <?php if($_SESSION['role_id'] === 1 || $_SESSION['role_id'] === 2):?>
                <a  href="edit_membership_info.php?id=<?= base64_encode($member_info['id'])?>"class="btn btn-primary profile-button" >edit member info</a>
                <?php endif; ?>    
              <a  href="extend_membership.php?id=<?= base64_encode($member_info['id'])?>"class="btn btn-primary profile-button" >extend</a>
              <?php if($_SESSION['role_id'] === 1 || $_SESSION['role_id'] === 2):?>
              <a style="background-color:#dc3545;"  href="cancel_membership.php?id=<?= base64_encode($member_info['id'])?>"class="btn btn-primary profile-button" >Cancel Membership</a>
              <?php endif; ?> 
            </span></div>
        </div>
    </div>
   
    <div class="row">
            <div class="col-md-12 border-right">
            <?php if( isset($_SESSION['message'])){displayMessage();}?>
            <div class="p-3 py-5">
            <div style="  text-align: center; " class="d-flex justify-content-between  mb-3" >
                    <h4 class="text-center"   style="  text-align: center; "> Member Tracks </h4>
                </div>
  <!-- member track table -->
  <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">price	</th>
          <th scope="col">bill </th>
          <th scope="col">start</th>
          <th scope="col">end</th>
          <th scope="col">Latest Action</th>
          <th scope="col">By  </th>
          <th scope="col">status </th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i_track = 0;
        while($memberRow = mysqli_fetch_assoc($query_memberTrack)): ?>
        <tr>
          <th scope="row"><?=++$i_track?></th>
          <td><?= number_format($memberRow['price']);?>   </td>
          <td><?=$memberRow['bill']?></td>
          
        
          <td><?=date("j M, Y", strtotime($memberRow['start_date']))?></td>
          <td><?=date("j M, Y", strtotime($memberRow['end_date']))?></td>
          <td><?=date("j M, Y - g:i a", strtotime($memberRow['updated_at']))?> </td>
          <td>
            <?php 
              
              if(intval($memberRow['action_id']) !== 0){
                $sql = "SELECT 
                              `full_name`,
                              `agent_code`
                                        FROM
                                            `users`
                                          WHERE
                                              `id` = {$memberRow['action_id']}";
                $userQuery = mysqli_query($conn,$sql);
                $count     = mysqli_num_rows($userQuery);
                if($count === 1){
                  $result = mysqli_fetch_assoc($userQuery);
                  echo $result['full_name'] ."[".$result['agent_code']."]"; 
                }else{
                  echo "-";
                }                              
            }else{
              echo $memberRow['user_name'] ."[".$memberRow['agent_code']."]";
            }
            ?>
          </td>
        
        <td><?= (intval($memberRow['status']) === 1 ? $greenCircle : $yellowCircle) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    
    <div style="  text-align: center; " class="d-flex justify-content-between  align-items-center text-center mb-3" >
    <h4 class="text-center align-items-center text-center"   style="  text-align: center; "> Member Comments </h4>
    </div>
    <!-- member Comment table -->
    <?php if($countComment > 0): ?>        
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">comment	</th>
          <th scope="col">Created at  </th>
          <th scope="col">By</th>
          <?php if($_SESSION['role_id'] === 1 || $_SESSION['role_id'] === 2): ?>
          <th scope="col">Actions</th>
          <?php endif;?>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i_com = 0;
        while($commentRow = mysqli_fetch_assoc($getCommQuery)):
        ?>
        <tr>
          <th scope="row"><?=++$i_com;?></th>
          <td><?=$commentRow['comment']?></td>
          <td><?=date("j M, Y - g:i a", strtotime($commentRow['created_at']))?> </td>        
          <td><?= $commentRow['user_name']."[".$commentRow['agent_code']."]"?></td>
          <?php if($_SESSION['role_id'] === 1 || $_SESSION['role_id'] === 2): ?>
          <td>
            <a href="membership_profile.php?id=<?= base64_encode($member_id)?>&delete_comment=<?= base64_encode($commentRow['id'])?>" class="btn btn-danger">X</a>
          </td>
          <?php endif;?>
        </tr>
        <?php endwhile;?>
      </tbody>
    </table>
    <?php else: ?>
      <div class='alert alert-info alert-dismissible fade show' role='alert'>no comments added</div>
    <?php endif;?>     
    <?php if($_SESSION['role_id'] === 1 || $_SESSION['role_id'] === 2): ?>
    <div style="  text-align: center; " class="d-flex justify-content-between  mb-3" >
        <h4 class="text-center"   style="  text-align: center; ">Latest Actions</h4>
    </div>
  
  <!-- Latest Actions -->
  <table class="table offset-md-3 col-md-6">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">updated_by	</th>
          <th scope="col">created_at  </th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i_users = 0;
        while($updateRow = mysqli_fetch_assoc($getUpdateQuery)):
          ?>
        <tr>
          <th scope="row"><?=++$i_users?></th>
          <td><?= $updateRow['user_name']."[".$updateRow['agent_code']."]"?></td>
          <td><?=date("j M, Y - g:i a", strtotime($updateRow['created_at']))?> </td>
        </tr>
        <?php endwhile;?>
      </tbody>
    </table>
    <?php endif; ?>
        </div>
        </div>
    </div>
    <br>
    <br>

</div>
</div>
</div>
</div>

<?php
require_once('../inc/footer.php');