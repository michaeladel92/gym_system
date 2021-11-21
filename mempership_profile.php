<?php
  require("inc/init.php");
  require_once("inc/nav.php");

 
  if ( $_SESSION['id']) {
    $user_id= $_SESSION['id'];

    $sql = "SELECT * FROM `users` WHERE `id` = {$user_id} LIMIT 1";
    $getUsersQuery = mysqli_query($conn,$sql);
    $userRow = mysqli_fetch_assoc($getUsersQuery); 


  }
  else {
    redirectHeader('login.php'); 
  }

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
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold"><?php echo  $userRow['full_name']?></span><span class="text-black-50"><?php echo  $userRow['email']?></span><span> </span></div>
            <!-- <div class="row mt-3">
            <div class="ml-2 text-center"><a  href="edit_user.php"class="btn btn-primary profile-button" >list of track</a></div>
             <div class="ml-2 text-center"><a  href="edit_user.php"class="btn btn-primary profile-button" > list of users</a></div>
                    
                </div> -->
        </div>
        <div class="col-md-9 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                
                <div class="row mt-3">
                <div class="col-md-12"><label class="labels">Name</label><input value="<?php echo  $userRow['full_name']?>" type="text" class="form-control" placeholder="first name" value=""></div>

                    <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" class="form-control" placeholder="enter phone number" value=""></div>
                    <div class="col-md-12"><label class="labels">Email </label><input type="text" class="form-control" placeholder="enter email id" value=""></div>
              
                </div>
            
                <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="button">Save Profile</button></div>
            </div>
        </div>
   
    </div>
    <br>
    <br>

    <div class="row">
            <div class="col-md-12 border-right">
            <div class="p-3 py-5">
            <div style="  text-align: center; " class="d-flex justify-content-between  mb-3" >
                    <h4 class="text-center"   style="  text-align: center; "> list of users</h4>
                </div>
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
              <td><?=$userLists['action_id']?></td>
              <td>
                <a href="edit_user.php?id=<?=base64_encode($userLists['id'])?>" type="button" class="btn btn-dark">Edit</a>
              </td>
            </tr>
        <?php endwhile; ?>
     

      </tbody>
    </table>

 
        </div>
        </div>
   
    </div>
    <br>
    <br>

    <div class="row">
            <div class="col-md-12 border-right">
            <div class="p-3 py-5">
            <div style="  text-align: center; " class="d-flex justify-content-between  mb-3" >
                    <h4 class="text-center"   style="  text-align: center; "> list of users</h4>
                </div>
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
              <td><?=$userLists['action_id']?></td>
              <td>
                <a href="edit_user.php?id=<?=base64_encode($userLists['id'])?>" type="button" class="btn btn-dark">Edit</a>
              </td>
            </tr>
        <?php endwhile; ?>
     

      </tbody>
    </table>

 
        </div>
        </div>
   
    </div>
</div>
</div>
</div>
</div>

<?php
require_once('inc/footer.php');