<section>
<?php
  require_once 'functions.php';
  if(!isset($_SESSION)) session_start();
  isLoggedIn();
  redirectUserType();
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $user = $_POST;
    $result = createUser( $user, getUserID());
    if($result > 0){
      header("Location: ". $_SERVER['REQUEST_URI']."?user_created=success");
    }
  }else{ ?>

  <div class="box" style="height:auto; min-height:100vh">
    <?php include 'templates/nav.php';
      if(isset($_SERVER['QUERY_STRING'])){
        $query_string = $_SERVER['QUERY_STRING'];
        if($query_string === 'user_created=success')
          echo '<div class="notification is-success" style="width:600px;margin:0 auto">
                User succesfully created.
            </div>';
      }
    ?>
    <form method="POST" action='<?php echo $_SERVER['PHP_SELF']; ?>' class="form-create">
      <nav class="level">
        <!-- Left side -->
        <div class="level-left">
          <div class="level-item">
              <h1 class="title is-4 has-text-centered">Create User</h1>
          </div>

        </div>

        <!-- Right side -->
        <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped is-grouped-right">
                <p class="control">
                  <a class="button is-outlined" href="admin.php">
                    <span class="icon">
                    <i class="fa fa-ban"></i></span>
                    <span>Cancel</span>
                  </a>
                </p>

                <p class="control">
                  <button type="submit" class="button is-outlined is-success">
                    <span class="icon">
                    <i class="fa fa-check"></i></span>
                    <span>Save</span>
                  </a>
                </p>
              </div>
            </div>
        </div>
      </nav>
      <hr />
      <div class="field">
        <label class="label">Full Name</label>
        <div class="control has-icons-left">
          <input class="input" type="text" placeholder="e.g. romoyne watson" name="fullname">
          <span class="icon is-small is-left">
              <i class="fa fa-user"></i>
          </span>
        </div>
      </div>

      <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left">
          <input class="input" type="text" placeholder="e.g. romoyne.watson@gmail.com" name="email">
          <span class="icon is-small is-left">
              <i class="fa fa-envelope"></i>
          </span>
        </div>
      </div>

      <div class="field">
        <label class="label">User Default Password</label>
        <div class="control has-icons-left">
          <input class="input" type="text" value="Password01" name="fullname" disabled>
          <span class="icon is-small is-left">
              <i class="fa fa-lock"></i>
          </span>
        </div>
      </div>

    <div class="field">
       <label class="label">User Type</label>
       <div class="control">
         <div class="select">
            <select name="user_type">
               <?php $usertypes = getUserTypes();
                 foreach($usertypes as $usertype): ?>
                  <option value="<?php echo $usertype['id'] ?>"
                    <?php if($usertype['title'] === 'Regular') echo 'selected'; ?>>
                      <?php echo $usertype['title'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
           </div>
       </div>
     </div>
    </form>

  </div>
  <?php } ?>
</section>
