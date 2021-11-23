<?php
  require("inc/init.php");
  require_once("inc/nav.php");
  // Session not available 
  isSessionIdNotAvailable('Please Login to procceed!','danger','login.php');
  // check if role is admin or manager
  isAdminOrManager('Access Denied!','danger','index.php');
  // check if account is active
  isStatusActive();

  if($_SERVER['REQUEST_METHOD'] === "GET"){
      // DELETE CCOMMENT
      if(isset($_GET['delete_comment']) && $_GET['delete_comment'] !== ''){
        $com_id = base64_decode($_GET['delete_comment']);
        $com_id = clean($com_id,'num');
        $sql   = "DELETE FROM `comments` WHERE `id` = $com_id LIMIT 1";
        $query = mysqli_query($conn,$sql);
        if($query){
          setMessage('Comment Deleted!','success');
          redirectHeader('dashboard.php');
        }else{
          setMessage('Oops, Something went wrong please try again!','danger');
          redirectHeader('dashboard.php');
        }
      }
  }
  if($_SERVER['REQUEST_METHOD'] === "POST"){
        //search agent 
        if(isset($_POST['search_agent'])){
          $search = clean($_POST['agent'],'string');
          $sql = "SELECT  
                      `users`.*,
                      `roles`.`role`
                                FROM 
                                    `users`
                                INNER JOIN 
                                    `roles`
                                ON `users`.`role_id` = `roles`.`id`
                                WHERE 
                                    (`users`.`agent_code` LIKE '%{$search}%' OR 
                                    `users`.`full_name` LIKE '%{$search}%' OR
                                    `users`.`email` LIKE '%{$search}%'
                                    )
                                ORDER BY `users`.`id` DESC
                    ";
        }

  }else{
    //all get agent 
    $sql = "SELECT  
    `users`.*,
    `roles`.`role`
              FROM 
                  `users`
              INNER JOIN 
                  `roles`
              ON `users`.`role_id` = `roles`.`id`
              ORDER BY `users`.`id` DESC
              ";
  }
  $getUsersQuery = mysqli_query($conn,$sql);

  

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
                `membership_track`.`status` = 1 
                AND
                      `membership_track`.`id`
                      IN(
                       SELECT MAX(`membership_track`.`id`) 
                              FROM
                              `membership_track`
                              GROUP BY
                              `membership_track`.`member_id`
                              HAVING 
                              COUNT(*) >= 1 ORDER by `membership_track`.`member_id` ASC
                      ) 
                      ORDER BY `membership_track`.`updated_at` DESC";
  $query_memberTrack = mysqli_query($conn,$sql);
  $count_member      = mysqli_num_rows($query_memberTrack);                      
  

  // GET All Comment
  $sql_comm = "  SELECT 
                      `comments`.*,
                      `membership_info`.`full_name` as `member_name`, 
                      `users`.`full_name` AS `user_name`,
                      `users`.`agent_code`
                    FROM
                      `comments`

                    INNER JOIN
                      `users`
                    ON
                      `users`.`id` = `comments`.`user_id`
                    INNER JOIN
                      `membership_info`
                    ON
                      `membership_info`.`id` = `comments`.`member_id`
                    
                    ORDER BY 
                      `comments`.`created_at` DESC";
  $getCommQuery = mysqli_query($conn,$sql_comm);
  $countComments = mysqli_num_rows($getCommQuery);  

  $greenCircle ="<span style='width: 0.5rem;height: 0.5rem;background-color: green;display: inline-block;border-radius: 50%;box-shadow: 0 0 5px 0.5px green;'></span>";
  $yellowCircle = "<span style='width: 0.5rem;height: 0.5rem;background-color: #d7d72c;display: inline-block;border-radius: 50%;box-shadow: 0 0 5px 0.5px #d7d72c;'></span>";
?>

<style>
.box-container,.box-container .box{display:flex;justify-content:center;align-items:center}.box-container .box{flex-direction:column}.box-container .box:first-child{background-color:#ffadad}.box-container .box:nth-of-type(2){background-color:#ffd6a5}.box-container .box:nth-of-type(3){background-color:#fdffb6}.box-container .box:nth-of-type(4){background-color:#eed7ce}.box-container .box{background:red;padding:1rem;width:14rem;height:12rem;text-align:center;border-radius:2rem;color:#4a4a4a;margin:1rem}.box-container .box .numbers{font-size:3rem}
</style>

 <div class="container mt-5">
   <!-- boxis -->
   <div class="box-container">
      <div class="box">
        <h3>users</h3>
        <h2 class="numbers"><?=countActiveAgents()?></h2>
      </div>
      <div class="box">
        <h3>Membership</h3>
        <h2 class="numbers"><?=$count_member?></h2>
      </div>
      <div class="box">
        <h3>New Members</h3>
        <h2 class="numbers"><?=countDailyNewMember()?></h2>
      </div>
      <div class="box">
        <h3>Daily Earns</h3>
        <h2 class="numbers"><?=number_format(dailyNewMemberEarns())?></h2>
      </div>
   </div>

   <div style="text-align:center;" class="d-flex justify-content-between  mb-3" >
      <h4 class="text-center" style="text-align: center;">Agents</h4>                 
  </div>
  <form class="ml-auto col-md-12" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
      <div class="input-group mb-3 col-md-4">
                 <input type="text" class="form-control" name="agent" placeholder="Search Agent" >
                <div class="input-group-append">
                <button class="btn btn-outline-info" name="search_agent" type="submit" id="button-addon2">Search</button>
                </div>
      </div>
  </form> 
   <!-- users table -->
   <?php if( isset($_SESSION['message'])){displayMessage();}?>

 <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">agent code</th>
          <th scope="col">name</th>
          <th scope="col">email </th>
          <th scope="col">status </th>
          <th scope="col">Approved </th>
          <th scope="col">role </th>
          <th scope="col">Latest Action </th>
          <th scope="col">options </th>
        </tr>
      </thead>
      <tbody>
        <?php while($userLists = mysqli_fetch_assoc($getUsersQuery)):?>
            <tr>
              <th scope="row"><?=$userLists['agent_code']?></th>
              <td><?=$userLists['full_name']?></td>
              <td><?=$userLists['email']?></td>
              <td><?= (intval($userLists['status']) === 1 ? "$greenCircle" : "$yellowCircle") ?></td>
              <td><?= (intval($userLists['is_approved']) === 1 ? "$greenCircle" : "$yellowCircle") ?></td>
              <td><?=$userLists['role']?></td>
              <td><?php
              if(intval($userLists['action_id']) !== 0){
                  $sql = "SELECT 
                                `full_name`,
                                `agent_code`
                                           FROM
                                               `users`
                                            WHERE
                                                `id` = {$userLists['action_id']}";
                  $userQuery = mysqli_query($conn,$sql);
                  $count     = mysqli_num_rows($userQuery);
                  if($count === 1){
                    $result = mysqli_fetch_assoc($userQuery);
                    echo $result['full_name'] ."[".$result['agent_code']."]"; 
                  }else{
                    echo "-";
                  }                              
              }else{
                echo $userLists['full_name'] ."[".$userLists['agent_code']."]";
              }
              
              
              ?></td>
              <td>
                <a href="edit_user.php?id=<?=base64_encode($userLists['id'])?>" type="button" class="btn btn-dark">Edit</a>
              </td>
            </tr>
        <?php endwhile; ?>
     

      </tbody>
    </table>
    <div style="text-align:center;" class="d-flex justify-content-between  mb-3" >
      <h4 class="text-center" style="text-align: center;">Member Tracks</h4>                 
  </div>
    <?php if($count_member > 0):?>
  <!-- member track table -->
  <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Member</th>
          <th scope="col">Bill</th>
          <th scope="col">Price</th>
          <th scope="col">start</th>
          <th scope="col">End</th>
          <th scope="col">Latest Action</th>
          <th scope="col">by</th>
          <th scope="col">Options</th>
        </tr>
      </thead>
      <tbody>
        <?php while($memberRow = mysqli_fetch_assoc($query_memberTrack)): ?>
        <tr>
          <th scope="row"><a href="membership_profile.php?id=<?= base64_encode($memberRow['member_id'])?>"><?=$memberRow['member_name']?></a></th>
          <td><?=$memberRow['bill']?></td>
          <td><?= number_format($memberRow['price']);?>
          </td>
          <td><?=date("j M, Y", strtotime($memberRow['start_date']))?></td>
          <td><?=date("j M, Y", strtotime($memberRow['end_date']))?></td>
          <td><?=date("j M, Y - g:i a", strtotime($memberRow['updated_at']))?> 
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
        </td>
          <td>
            <a href="edit_track.php?id=<?= base64_encode($memberRow['id'])?>" class="btn btn-dark">Edit</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class='alert alert-info alert-dismissible fade show' role='alert'>No Members yet</div>
    <?php endif; ?>
    <div style="text-align:center;" class="d-flex justify-content-between  mb-3" >
      <h4 class="text-center" style="text-align: center;">Comments</h4>                 
  </div>
          <!-- member Comment table -->
        <?php if($countComments > 0): ?>      
      <table class="table">
        <thead class="thead-dark">
        <tr>
          <th scope="col">Member</th>
          <th scope="col">comment	</th>
          <th scope="col">Created at  </th>
          <th scope="col">By</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($commentRow = mysqli_fetch_assoc($getCommQuery)): ?>
        <tr>
          <th scope="row"><a href="membership_profile.php?id=<?= base64_encode($commentRow['member_id'])?>"><?=$commentRow['member_name']?></a></th>
          <td><?=$commentRow['comment']?></td>
          <td><?=date("j M, Y - g:i a", strtotime($commentRow['created_at']))?> </td>        
          <td><?= $commentRow['user_name']."[".$commentRow['agent_code']."]"?></td>
          <td>
            <a href="dashboard.php?delete_comment=<?= base64_encode($commentRow['id'])?>" class="btn btn-danger">X</a>
          </td>
        </tr>
        <?php endwhile;?>
      </tbody>
    </table>
      <?php else:?> 
        <div class='alert alert-info alert-dismissible fade show' role='alert'>no comments</div>
      <?php endif;?> 
</div>

<?php
require_once('inc/footer.php');