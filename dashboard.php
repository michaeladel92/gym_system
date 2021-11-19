<?php
  require("inc/init.php");
  require_once("inc/nav.php");
  

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
 <div class="container mt-5">
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