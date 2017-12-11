<?php if(!isset($_SESSION)) session_start(); ?>
<nav class="level">
  <div class="level-left">
    <div class="level-item"></div>
  </div>
  <div class="level-right">
    <p class="level-item">
      <a href="<?php if(intval($_SESSION['user_type']) === 1){ echo 'admin.php'; } else { echo 'client.php'; } ?>">
        Dashboard
      </a>
    </p>
    <p class="level-item"> <a href="client.php">View Reports</a> </p>
    <p class="level-item"> <a href="client.php">New Query</a> </p>
    <p class="level-item">
      Logged in as &nbsp<strong><?php echo $_SESSION['username']; ?></strong>
    </p>
    <?php
      if(intval($_SESSION['user_type']) === 2){
        $ele = intval($_SESSION['uid']);
        echo '<p class="level-item"><a href="profile.php?uid=' . $ele . '">View Profile</a></p>';
      }
    ?>

    <p class="level-item">
      <?php if(intval($_SESSION['user_type']) === 2){
                echo '<a class="button is-outlined is-info is-small" href="/?logout=true">
                <span class="icon"><i class="fa fa-sign-out"></i></span>
                <span>Logout</span></a>';
            }else{
                echo '<a class="button is-outlined is-info is-small" href="admin-login.php?logout=true">
                <span class="icon"><i class="fa fa-sign-out"></i></span>
                <span>Logout</span></a>';
            }
      ?>
    </p>
  </div>
</nav>
