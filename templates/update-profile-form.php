<?php
  require_once 'functions.php';
  if(!isset($_SESSION)) session_start();
  checkSession();
  redirectUserType();
  if(isset($_SERVER['QUERY_STRING'])){
    parse_str($_SERVER['QUERY_STRING'], $query_string);
    $user = getUser($query_string['uid']);
  }

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $success = 'true';
    $result = updateProfile($_POST);
    if(!$result || $result === 0) $success = 'false';
    header('Location: '.$_SERVER['REQUEST_URI'].'?uid='.$_POST['id'].'&profile_updated='.$success);
  }
?>
<section>
  <div class="box" style="height:auto; min-height:100vh">
    <?php include 'templates/nav.php'; ?>
    <?php
      parse_str($_SERVER['QUERY_STRING'], $query_string);
      if(isset($query_string['profile_updated'])){
        if($query_string['profile_updated'] === 'true' ){
          echo '<div class="notification is-success"
                      style="width:600px;margin:0 auto">Profile Update successful.
                </div>';
        }else{
          echo '<div class="notification is-danger"
                      style="width:600px;margin:0 auto">An error occurred while updating your profile.
                </div>';
        }
      }

    ?>

    <form method="POST"
          class="form-create"
          action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <nav class="level">
        <!-- Left side -->
        <div class="level-left">
          <div class="level-item">
              <h1 class="title is-4 has-text-centered">My Profile</h1>
          </div>

        </div>

        <!-- Right side -->
        <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped is-grouped-right">
                <p class="control">
                  <a class="button is-outlined" href="client.php">
                    <span class="icon">
                        <i class="fa fa-times-circle"></i>
                      </span>
                      <span>Cancel</span>
                  </a>
                </p>

                <p class="control">
                  <button type="submit" class="button is-outlined is-primary">
                    <span class="icon">
                        <i class="fa fa-check"></i>
                      </span>
                  <span>Update</span>
                  </button>
                </p>
              </div>
            </div>
        </div>
      </nav>

      <hr />
      <div class="field">
        <label class="label">Full Name</label>
        <div class="control has-icons-left">
          <input class="input" type="text" name='fullname' value="<?php echo $user['name']?>">
          <span class="icon is-small is-left">
              <i class="fa fa-user"></i>
          </span>
        </div>
      </div>

      <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left">
          <input class="input" type="text" name='email' value="<?php echo $user['email'] ?>">
          <span class="icon is-small is-left">
              <i class="fa fa-envelope"></i>
          </span>
        </div>
      </div>

      <div class="field">
        <label class="label">New Password</label>
        <div class="control has-icons-left">
          <input class="input" type="password" name='new_password'>
          <span class="icon is-small is-left">
              <i class="fa fa-lock"></i>
          </span>
        </div>
      </div>
      <input type="hidden" value="<?php echo $user['password'] ?> " name="current_password"/>
      <input type="hidden" value="<?php echo $user['id'] ?> " name="id"/>
    </form>

  </div>
</section>
