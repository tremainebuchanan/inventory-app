<section class="login">
<?php
if(!isset($_SESSION)) session_start();
require_once 'functions.php';
if(isset($_SERVER['QUERY_STRING'])){
  if($_SERVER['QUERY_STRING'] === "logout=true") destroySession();
}

if(isset($_SESSION['username'])) {
  if(intval($_SESSION['user_type']) === 2)
    header('Location: client.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $user = login($_POST['email'], $_POST['password']);
    if($user === false || intval($user['user_type']) === 1 ) //admin user type
        return header("Location: ".$_SERVER['REQUEST_URI']."?user_authenticated=false");

    setSession($user);
    header("Location: client.php");
}else{
?>

<div class="box">
  <?php
    if(isset($_SERVER['QUERY_STRING'])){
      $query_string = $_SERVER['QUERY_STRING'];
      if($query_string === 'user_authenticated=false')
        echo '<div class="notification is-danger" style="text-align:center">
                Invalid username/password.
              </div>';
    }
  ?>
  <h1 class="title is-4 has-text-centered">Client Login</h1>
    <hr />
    <form method="POST" action='<?php echo $_SERVER['PHP_SELF']; ?>'>
      <div class="field">
        <label class="label">Username</label>
        <div class="control has-icons-left">
          <input class="input" type="text" placeholder="e.g. romoyne.watson@gmail.com" name="email">
          <span class="icon is-small is-left">
              <i class="fa fa-user"></i>
          </span>
        </div>
      </div>

      <div class="field">
        <label class="label">Password</label>
        <div class="control has-icons-left">
          <input class="input" type="password" name="password">
          <span class="icon is-small is-left">
              <i class="fa fa-lock"></i>
          </span>
        </div>
      </div>

      <div class="field is-grouped is-grouped-right">
        <p class="control">
          <input class="button is-primary" type="submit" value="Log in">

        </p>
      </div>
    </form>
  <p><a href="admin-login.php">Go To Admin Login</a></p>
  </div>
  <?php
    }
  ?>
</section>
