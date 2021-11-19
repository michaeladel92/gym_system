<?php
  require("inc/init.php");
  require_once("inc/nav.php");
?>
 <div class="container mt-5">
 <div class="offset-md-3 col-md-6">
      <form>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Full Name</label>
            <input type="text" class="form-control" id="inputEmail4">
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword4">Email</label>
            <input type="email" class="form-control" id="inputPassword4">
          </div>
        </div>

        <div class="form-group col-md-6">
          <label for="inputState">Role</label>
          <select id="inputState" class="form-control">
            <option selected>Agent</option>
            <option>manager</option>
          </select>
        </div>
        <button type="submit" class="btn btn-info">Register</button>
      </form>
    </div>
</div>

<?php
require_once('inc/footer.php');