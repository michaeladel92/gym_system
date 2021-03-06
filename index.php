<?php
  require("inc/init.php");
  require_once("inc/nav.php");
  // check if session not set
  isSessionIdNotAvailable('Please login to procceed!','danger','login.php');
  // check if account is active
  isStatusActive();

  if($_SERVER['REQUEST_METHOD'] === "POST"){
      // search membership
      if(isset($_POST['search_membership'])){
        $search = clean($_POST['membership'],'string');
        $sql = "SELECT  
                      `membership_info`.*,
                      `membership_track`.`start_date`,
                      `membership_track`.`end_date`,
                      `membership_track`.`action_id`,
                      `membership_track`.`created_at`,
                      `membership_track`.`updated_at`,
                      `users`.`full_name` AS `agent_name`,
                      `users`.`agent_code`
                  FROM 
                      `membership_info`
                  INNER JOIN 
                      `membership_track`
                  ON `membership_info`.`id` = `membership_track`.`member_id`
                  INNER JOIN 
                      `users`
                  ON  `users`.`id` = `membership_track`.`user_id`
                  WHERE 
                  `membership_track`.`status` = 1 
                  AND
                  (`membership_info`.`full_name` LIKE '%{$search}%' OR 
                   `membership_info`.`phone` LIKE '%{$search}%' 
                  )
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
      }
    
  }else{
        //all get Members 
        $sql = "SELECT  
                      `membership_info`.*,
                      `membership_track`.`start_date`,
                      `membership_track`.`end_date`,
                      `membership_track`.`action_id`,
                      `membership_track`.`created_at`,
                      `membership_track`.`updated_at`,
                      `users`.`full_name` AS `agent_name`,
                      `users`.`agent_code`
                  FROM 
                      `membership_info`
                  INNER JOIN 
                      `membership_track`
                  ON `membership_info`.`id` = `membership_track`.`member_id`
                  INNER JOIN 
                      `users`
                  ON  `users`.`id` = `membership_track`.`user_id`
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
  }


  
  $getMembersQuery = mysqli_query($conn,$sql);
  $countMembers      = mysqli_num_rows($getMembersQuery); 


?>

<style>
.box-container,.box-container .box{
  display: flex;
    justify-content: center;
    align-items: center;
}
.box-container .box{
 flex-direction:column; 
}
.box-container .box:first-child{
  background-color:#ffadad;
}
.box-container .box:nth-of-type(2){
  background-color:#ffd6a5;
}
.box-container .box:nth-of-type(3){
  background-color:#fdffb6;
}
.box-container .box{
  background: red;
    padding: 1rem;
    width: 14rem;
    height: 12rem;
    text-align: center;
    border-radius: 2rem;
    color: #4a4a4a;
    margin: 1rem;
}
.box-container .box .numbers{
  font-size:3rem
}
</style>

 <div class="container mt-5">
   <!-- boxis -->
   <div class="box-container">
      <div class="box">
        <h3>Membership</h3>
        <h2 class="numbers"><?=$countMembers?></h2>
      </div>
      <div class="box">
        <h3>New Members</h3>
        <h2 class="numbers"><?=countDailyNewMember()?></h2>
      </div>
   </div>
   <form class="ml-auto col-md-12" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
      <div class="input-group mb-3 col-md-4">
                 <input type="text" class="form-control" name="membership" placeholder="Search Agent" >
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
          <th scope="col">name</th>
          <th scope="col">phone</th>
          <th scope="col">start date </th>
          <th scope="col">end date </th>
          <th scope="col">created by </th>
          <th scope="col">Latest Action </th>
          <th scope="col">options </th>
        </tr>
      </thead>
      <tbody>
        <?php while($memberLists = mysqli_fetch_assoc($getMembersQuery)): ?>
            <tr>
              <th scope="row"><a href="members/membership_profile.php?id=<?=base64_encode($memberLists['id'])?>"><?=$memberLists['full_name']?></a></th>
              <td><?=implode('-',str_split($memberLists['phone'],3))?></td>
              <td><?=date("j M, Y", strtotime($memberLists['start_date']))?></td>
              <td><?=date("j M, Y", strtotime($memberLists['end_date']))?></td>
              <td ><?=$memberLists['agent_name'] .' ['.$memberLists['agent_code']. ']'?></td>
              <td><?=date("j M, Y - g:i a", strtotime($memberLists['updated_at']))?></td>
              <td>
                <a href="members/extend_membership.php?id=<?=base64_encode($memberLists['id'])?>" type="button" class="btn btn-dark">extend</a>
              </td>
            </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    
</div>

<?php
require_once('inc/footer.php');