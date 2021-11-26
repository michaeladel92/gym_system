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



   // DELETE
   if(isset($_GET['delete']) && $_GET['delete'] !== '' ){
    // decode id
    $member_track_id = base64_decode($_GET['delete']);
    $member_track_id = clean($member_track_id,'num');
 
    if(!validate($member_track_id,'num')){
      setMessage('Oops, Something Went Wrong Please try again!','danger');
      redirectHeader('list_tracks.php');
    }else{
      $sql = "SELECT `id` FROM `membership_track` WHERE `id` = {$member_track_id} AND `status` = 0";
      $getMembersQuery = mysqli_query($conn,$sql);
      $count = mysqli_num_rows($getMembersQuery);
      if($count === 0){
        setMessage('Member Track Not found!','danger');
        redirectHeader('list_tracks.php');
      }else{
        // DELETE
        $sql = "DELETE FROM `membership_track` WHERE `id` = {$member_track_id} AND `status` = 0 LIMIT 1";
        $query = mysqli_query($conn,$sql);
        if($query){
          setMessage('Member Track Deleted Successfully!','success');
          redirectHeader('list_tracks.php');
        }else{
          setMessage('Oops, Something Went Wrong Please try again!','danger');
          redirectHeader('list_tracks.php');
        } 
    
      }
    }
  }


  if($_SERVER['REQUEST_METHOD'] === "POST"){
      // search membership
      if(isset($_POST['search_membership'])){
        $search = clean($_POST['membership'],'string');
        $sql = "SELECT  
                      
                      `membership_track`.*,
                      `users`.`full_name` AS `agent_name`,
                      `users`.`agent_code`
                  FROM 
                      `membership_track`
                
                  INNER JOIN 
                      `users`
                  ON  `users`.`id` = `membership_track`.`user_id`
                  WHERE 
                
                  (`membership_track`.`bill` LIKE '%{$search}%' 
                  )
             
                  ORDER BY `membership_track`.`updated_at` DESC";
      }
    
  }else{
        //all get Members 
        $sql = "SELECT  
                       `membership_track`.*,
                      `users`.`full_name` AS `agent_name`,
                      `users`.`agent_code`
                  FROM 
                      `membership_track`                
                  INNER JOIN 
                      `users`
                  ON  `users`.`id` = `membership_track`.`user_id`
                  ORDER BY `membership_track`.`updated_at` DESC";
  }

  $getMembersQuery = mysqli_query($conn,$sql);
  
?>

<style>
.box-container,.box-container .box{display:flex;justify-content:center;align-items:center}.box-container .box{flex-direction:column}.box-container .box:first-child{background-color:#ffadad}.box-container .box:nth-of-type(2){background-color:#ffd6a5}.box-container .box:nth-of-type(3){background-color:#fdffb6}.box-container .box{background:red;padding:1rem;width:14rem;height:12rem;text-align:center;border-radius:2rem;color:#4a4a4a;margin:1rem}.box-container .box .numbers{font-size:3rem}
</style>

 <div class="container mt-5">
  
   <form class="ml-auto col-md-12" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
      <div class="input-group mb-3 col-md-4">
                 <input type="text" class="form-control" name="membership" placeholder="Search by tracks id" >
                <div class="input-group-append">
                <button class="btn btn-outline-info" name="search_membership" type="submit" id="button-addon2">Search</button>
                </div>
      </div>
  </form> 
   <!-- users table -->
   <?php if( isset($_SESSION['message'])){displayMessage();}?>
 <table class="table">
      <thead class="thead-dark">
        <tr>
        <th scope="col">#</th>
          <th scope="col">price	</th>
          <th scope="col">bill </th>
          <th scope="col">start </th>
          <th scope="col">end </th>
          <th scope="col">membership</th>
          <th scope="col">created by </th>
          <th scope="col">Latest Actions </th>
          <th scope="col">status </th>
          <th scope="col">options </th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $greenCircle ="<span style='width: 0.5rem;height: 0.5rem;background-color: green;display: inline-block;border-radius: 50%;box-shadow: 0 0 5px 0.5px green;'></span>";
          $yellowCircle = "<span style='width: 0.5rem;height: 0.5rem;background-color: #d7d72c;display: inline-block;border-radius: 50%;box-shadow: 0 0 5px 0.5px #d7d72c;'></span>";
         
        $i_track = 0;
        while($memberLists = mysqli_fetch_assoc($getMembersQuery)): ?>
            <tr>
                <th scope="row"><?=++$i_track?></th>
              <td><?= number_format($memberLists['price']);?>   </td>
              <td><?=$memberLists['bill']?></td>
              <td><?=date("j M, Y", strtotime($memberLists['start_date']))?></td>
              <td><?=date("j M, Y", strtotime($memberLists['end_date']))?></td>
              <td>
              <?php               
                    $member_id = intval($memberLists['member_id']);
                   $sql = "SELECT `full_name` FROM `membership_info` WHERE `id` = {$member_id}";
                   $memQuery = mysqli_query($conn,$sql);
                   $count     = mysqli_num_rows($memQuery);
                   if($count === 1){
                     $result = mysqli_fetch_assoc($memQuery);
                     echo $result['full_name']; 
                   }else{
                     echo "-";
                   } 
                ?>
              </td>
              <td ><?=$memberLists['agent_name'] .' ['.$memberLists['agent_code']. ']'?></td>
              <td><?=date("j M, Y - g:i a", strtotime($memberLists['updated_at']))?></td>
              <td><?= (intval($memberLists['status']) === 1 ? $greenCircle : $yellowCircle) ?></td>
              <td>
                <?php if(intval($memberLists['status']) === 0): ?>
            <a href="list_tracks.php?delete=<?= base64_encode($memberLists['id'])?>" class="btn btn-danger">X</a>
                 <?php endif;?> 
            </td>
            </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
</div>

<?php
require_once('../inc/footer.php');