<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" style="margin:0;padding:0;" href="#">
      <img src="http://<?=$_SERVER['HTTP_HOST']?>/gym/img/logo.png" alt="rock gym logo" style="object-fit: contain; width:8rem;height:5rem;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php if(isset($_SESSION['id'])): ?>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul dir="rtl" class="navbar-nav ml-auto">
        <li dir="ltr" class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown"
            aria-expanded="false">
            <?=$_SESSION['full_name']."[".$_SESSION['agent_code']."]"?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="http://<?=$_SERVER['HTTP_HOST']?>/gym/edit_user.php?id=<?=base64_encode($_SESSION['id'])?>">Edit</a>
            <?php if($_SESSION['role_id'] === 1 || $_SESSION['role_id'] === 2): ?>
              <a class="dropdown-item" href="http://<?=$_SERVER['HTTP_HOST']?>/gym/dashboard.php">Dashboard</a>
              <?php if($_SESSION['role_id'] === 1): ?>
                <a class="dropdown-item" href="http://<?=$_SERVER['HTTP_HOST']?>/gym/add_user.php">Add agent</a>
                <a class="dropdown-item" href="http://<?=$_SERVER['HTTP_HOST']?>/gym/roles/index.php">Roles</a>

            <?php 

            endif;
              endif;
            ?>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF']?>?logout=true">logout</a>
          </div>
        </li>
        <!-- active -->
        <li class="nav-item ">
          <a class="nav-link" href="http://<?=$_SERVER['HTTP_HOST']?>/gym/add_membership.php">New Membership</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://<?=$_SERVER['HTTP_HOST']?>/gym/index.php">Members</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://<?=$_SERVER['HTTP_HOST']?>/gym/list_tracks.php">all tracks</a>
        </li>
      </ul>
    </div>
    <?php endif; ?>
  </nav>