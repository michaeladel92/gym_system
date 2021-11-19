<?php
  require("inc/init.php");
  require_once("inc/nav.php");
?>
 <div class="container mt-5">
<div  class="offset-md-4 col-md-4">
      <form>
        <div class="form-group">
          <label for="exampleFormControlInput1">Email address</label>
          <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Password</label>
          <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="password">
        </div>
        <button type="button" class="btn btn-info">Sign In</button>
      </form>
    </div>
</div>

<?php
require_once('inc/footer.php');