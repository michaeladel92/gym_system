<?php
  require("inc/init.php");
  require_once("inc/nav.php");
  // Session not available 
  isSessionIdNotAvailable('Please Login to procceed!','danger','login.php');
  // check if role is admin or manager
  isAdminOrManager('Access Denied!','danger','index.php');
  // check if account is active
  isStatusActive();


  //all get users 
  $sql = "SELECT  
                `users`.*,
                `roles`.`role`
                          FROM 
                              `users`
                          INNER JOIN 
                               `roles`
                          ON `users`.`role_id` = `roles`.`id`
                          ORDER BY `id` DESC
                          ";
  $getUsersQuery = mysqli_query($conn,$sql);
  $countUsers    = mysqli_num_rows($getUsersQuery);
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
        <h3>users</h3>
        <h2 class="numbers"><?=$countUsers?></h2>
      </div>
      <div class="box">
        <h3>Membership</h3>
        <h2 class="numbers">8</h2>
      </div>
      <div class="box">
        <h3>New Members</h3>
        <h2 class="numbers">8</h2>
      </div>
   </div>
   
   <!-- users table -->
   <?php if( isset($_SESSION['message'])){displayMessage();}?>
 <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">agent code</th>
          <th scope="col">name</th>
          <th scope="col">email </th>
          <th scope="col">status </th>
          <th scope="col">password </th>
          <th scope="col">role </th>
          <th scope="col">Latest Action </th>
          <th scope="col">options </th>
        </tr>
      </thead>
      <tbody>
        <?php while($userLists = mysqli_fetch_assoc($getUsersQuery)): ?>
            <tr>
              <th scope="row"><?=$userLists['agent_code']?></th>
              <td><?=$userLists['full_name']?></td>
              <td><?=$userLists['email']?></td>
              <td><?= (intval($userLists['status']) === 1 ? 'Active' : 'De-activate') ?></td>
              <td><?= (intval($userLists['is_approved']) === 1 ? 'changed' : 'pending') ?></td>
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
                    echo $result['full_name'] ."[".$userLists['agent_code']."]"; 
                  }else{
                    echo "-";
                  }                              
              }else{
                echo $userLists['full_name'] ."[".$result['agent_code']."]";
              }
              
              
              ?></td>
              <td>
                <a href="edit_user.php?id=<?=base64_encode($userLists['id'])?>" type="button" class="btn btn-dark">Edit</a>
              </td>
            </tr>
        <?php endwhile; ?>
     

      </tbody>
    </table>

    
</div>

<?php
require_once('inc/footer.php');