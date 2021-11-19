<?php
  require("inc/init.php");
  require_once("inc/nav.php");
?>

  <!-- 
    =================
    table
    =================
   -->

  <div class="container mt-5">
    <h3>Users</h3>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">agent code</th>
          <th scope="col">name</th>
          <th scope="col">email </th>
          <th scope="col">status </th>
          <th scope="col">is_approved </th>
          <th scope="col">options </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">4126</th>
          <td>Mark Otto</td>
          <td>mark@gmail.com</td>
          <td>Active</td>
          <td>Active</td>
          <td>
            <a type="button" class="btn btn-dark">Edit</a>
          </td>
        </tr>
        <tr>
          <th scope="row">4126</th>
          <td>Mark Otto</td>
          <td>mark@gmail.com</td>
          <td>Active</td>
          <td>Active</td>
          <td>
            <a type="button" class="btn btn-dark">Edit</a>
          </td>
        </tr>
        <tr>
          <th scope="row">4126</th>
          <td>Mark Otto</td>
          <td>mark@gmail.com</td>
          <td>Active</td>
          <td>Active</td>
          <td>
            <a type="button" class="btn btn-dark">Edit</a>
          </td>
        </tr>

      </tbody>
    </table>
    <h3>MemberShip</h3>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">name</th>
          <th scope="col">phone </th>
          <th scope="col">start date </th>
          <th scope="col">end date </th>
          <th scope="col">options </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark Otto</td>
          <td>01000000000</td>
          <td>11/16/2021</td>
          <td>11/16/2022</td>
          <td>
            <a type="button" class="btn btn-dark">extend</a>
          </td>
        </tr>
        <tr>
          <th scope="row">1</th>
          <td>Mark Otto</td>
          <td>01000000000</td>
          <td>11/16/2021</td>
          <td>11/16/2022</td>
          <td>
            <a type="button" class="btn btn-dark">extend</a>
          </td>
        </tr>
        <tr>
          <th scope="row">1</th>
          <td>Mark Otto</td>
          <td>01000000000</td>
          <td>11/16/2021</td>
          <td>11/16/2022</td>
          <td>
            <a type="button" class="btn btn-dark">extend</a>
          </td>
        </tr>
      </tbody>
    </table>

    <h3>Bills</h3>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">bill num</th>
          <th scope="col">membership name</th>
          <th scope="col">price</th>
          <th scope="col">created date</th>
          <th scope="col">created by</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>mk9276</td>
          <td>Membership name</td>
          <td>5,000 LE</td>
          <td>11/16/2021</td>
          <td>Agent name</td>
        </tr>
        <tr>
          <th scope="row">1</th>
          <td>mk9276</td>
          <td>Membership name</td>
          <td>5,000 LE</td>
          <td>11/16/2021</td>
          <td>Agent name</td>
        </tr>

      </tbody>
    </table>
    <!-- 
    =================
    FORMS
    =================
   -->
    <h3>login</h3>
    <div class="offset-md-4 col-md-4">
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

    <h3>register</h3>
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
    <h4>Edit User</h4>
    <div class="offset-md-3 col-md-6">
      <form>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Full Name</label>
            <input type="text" class="form-control" id="inputEmail4">
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword4">email</label>
            <input type="email" class="form-control" id="inputPassword4">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputAddress">Password</label>
            <input type="password" class="form-control" id="inputAddress">
          </div>
          <div class="form-group col-md-6">
            <label for="inputAddress2">Confirm password</label>
            <input type="password" class="form-control" id="inputAddress2">
          </div>
          <div class="form-group col-md-6">
            <label for="inputState">Status</label>
            <select id="inputState" class="form-control">
              <option selected>Active</option>
              <option>Deactivate</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="inputState">Role</label>
            <select id="inputState" class="form-control">
              <option selected>Agent</option>
              <option>Manager</option>
            </select>
          </div>
        </div>
        <button type="submit" class="btn btn-info">Update</button>
      </form>
    </div>
    <h4>Add membership</h4>
    <div class="offset-md-3 col-md-6">
      <form>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Full Name</label>
            <input type="text" class="form-control" id="inputEmail4">
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword4">phone</label>
            <input type="number" class="form-control" id="inputPassword4">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputAddress">start date</label>
            <input type="date" class="form-control" id="inputAddress">
          </div>
          <div class="form-group col-md-6">
            <label for="inputAddress2">end date</label>
            <input type="date" class="form-control" id="inputAddress2">
          </div>
          <div class="form-group col-md-6">
            <label for="inputState">Subscription</label>
            <select id="inputState" class="form-control">
              <option selected>+1 day</option>
              <option>+1 Month</option>
              <option>+2 Month</option>
              <option>+3 Month</option>
              <option>+4 Month</option>
              <option>+5 Month</option>
              <option>+6 Month</option>
              <option>+7 Month</option>
              <option>+8 Month</option>
              <option>+9 Month</option>
              <option>+10 Month</option>
              <option>+11 Month</option>
              <option>+1 year</option>
              <option>+2 year</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="inputState">Price</label>
            <input type="number" class="form-control" id="inputPassword4">
          </div>
          <div class="form-group col-md-3">
            <label for="inputState">bill</label>
            <input type="text" class="form-control" id="inputPassword4">
          </div>
        </div>
        <button type="submit" class="btn btn-info">New Member</button>
      </form>
    </div>
  </div>




<?php
require_once('inc/footer.php');