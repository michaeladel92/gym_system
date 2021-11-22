<?php
  require("inc/init.php");
  require_once("inc/nav.php");

 
  
  
    $member_id= base64_decode($_GET['id']) ;

    $sql_info = "SELECT * FROM `membership_info` WHERE `id` = $member_id LIMIT 1";
    $getInfoQuery = mysqli_query($conn,$sql_info);
    $member_info = mysqli_fetch_assoc($getInfoQuery); 


 


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
  

  ?>
   <style>

body {
    background: rgb(99, 39, 120)
}

.form-control:focus {
    box-shadow: none;
    border-color: #BA68C8
}

.profile-button {
    background: rgb(99, 39, 120);
    box-shadow: none;
    border: none
}

.profile-button:hover {
    background: #682773
}

.profile-button:focus {
    background: #682773;
    box-shadow: none
}

.profile-button:active {
    background: #682773;
    box-shadow: none
}

.back:hover {
    color: #682773;
    cursor: pointer
}

.labels {
    font-size: 11px
}

.add-experience:hover {
    background: #BA68C8;
    color: #fff;
    cursor: pointer;
    border: solid 1px #BA68C8
}
       </style>
<div class="container rounded bg-white">
    <div class="row">
        <div class="col-md-12 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
              <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
              <span class="font-weight-bold"><?php echo  $member_info['full_name']?></span>
              <span class="text-black-50"><?php echo  $member_info['phone']?></span>
              <span><a  href="edit_user.php"class="btn btn-primary profile-button" >edit member info</a> </span></div>
          
        </div>
   
   
    </div>
   

    <div class="row">
            <div class="col-md-12 border-right">
            <div class="p-3 py-5">
            <div style="  text-align: center; " class="d-flex justify-content-between  mb-3" >
                    <h4 class="text-center"   style="  text-align: center; "> member tracs </h4>
                </div>
                 <!-- member track table -->
  <table class="table">
      <thead class="thead-dark">
        <tr>
       
         
         
          <th scope="col">#</th>
          <th scope="col">price	</th>
          <th scope="col">bill </th>
          <th scope="col">start_date </th>
          <th scope="col">end_date </th>
          <th scope="col">created_by  </th>
          <th scope="col">created_at  </th>
          <th scope="col">updated_at  </th>
          <th scope="col">action_id  </th>
          <th scope="col">status </th>
          <th scope="col">Options</th>
        </tr>
      </thead>
      <tbody>
        <?php while($memberRow = mysqli_fetch_assoc($query_memberTrack)): ?>
        <tr>
          <th scope="row"><?=$memberRow['id']?></th>
          <td><?= number_format($memberRow['price']);?>   </td>
          <td><?=$memberRow['bill']?></td>
          
        
          <td><?=date("j M, Y", strtotime($memberRow['start_date']))?></td>
          <td><?=date("j M, Y", strtotime($memberRow['end_date']))?></td>
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
          <td><?=date("j M, Y - g:i a", strtotime($memberRow['created_at']))?> </td>
          <td><?=date("j M, Y - g:i a", strtotime($memberRow['updated_at']))?> </td>
          <td><?=$memberRow['action_id']?></td>
        
        <td><?= (intval($memberRow['status']) === 1 ? 'Active' : 'De-activate') ?></td>

          <td>
            <a href="<?=$memberRow['id']?>" class="btn btn-dark">Edit</a>
           
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    
    <div style="  text-align: center; " class="d-flex justify-content-between  align-items-center text-center mb-3" >
                    <h4 class="text-center align-items-center text-center"   style="  text-align: center; "> member comments </h4>
                </div>
                 <!-- member track table -->
  <table class="table">
      <thead class="thead-dark">
        <tr>
       
         
         
          <th scope="col">#</th>
          <th scope="col">comment	</th>
       
          <th scope="col">created_at  </th>
          <th scope="col">created_by  </th>
       
          <th scope="col">Options</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        
        $comments=3;
        
        while($commentRow = mysqli_fetch_assoc($getCommQuery)): 
          $comments=4;
        
        ?>
        <tr>
          <th scope="row"><?=$commentRow['id']?></th>
          <td><?=$commentRow['comment']?></td>
          
        
          
          <td><?=date("j M, Y - g:i a", strtotime($commentRow['created_at']))?> </td>
        
          
          <td><?= $commentRow['user_name']."[".$commentRow['agent_code']."]"?></td>
          <td>
            
            <a href="<?=$commentRow['id']?>" class="btn btn-dark">Edit</a>
           
          </td>
        </tr>
        <?php endwhile;
      if (   $comments==3) {
        echo "<div class='alert alert-info alert-dismissible fade show' role='alert'>
            no comments added yet
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
         </div>";
          }
        ?>
      </tbody>
    </table>
 
    <div style="  text-align: center; " class="d-flex justify-content-between  mb-3" >
                    <h4 class="text-center"   style="  text-align: center; "> member info  tracks </h4>
                </div>
                 <!-- member track table -->
  <table class="table">
      <thead class="thead-dark">
        <tr>
       
         
         
          <th scope="col">#</th>
          <th scope="col">updated_by	</th>
       
          <th scope="col">created_at  </th>
       
       
          <th scope="col">Options</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        
        
        $updates=3;
        while($updateRow = mysqli_fetch_assoc($getUpdateQuery)):
          $updates=5; ?>
        
        <tr>
          <th scope="row"><?=$updateRow['id']?></th>
          
        
          
        
          
          <td><?= $updateRow['user_name']."[".$updateRow['agent_code']."]"?></td>
          <td><?=date("j M, Y - g:i a", strtotime($updateRow['created_at']))?> </td>

          <td>
            
            <a href="<?=$updateRow['id']?>" class="btn btn-dark">Edit</a>
           
          </td>
        </tr>
        <?php endwhile;
        
        
          
        if (   $updates==3) {
          echo "<div class='alert alert-info alert-dismissible fade show' role='alert'>
              no data added yet
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
           </div>";
            }?>
      </tbody>
      
    </table>
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
require_once('inc/footer.php');