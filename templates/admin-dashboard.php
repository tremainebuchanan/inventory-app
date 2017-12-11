<section class="box">
<?php
  require 'functions.php';
  if(!isset($_SESSION)) session_start();
  isLoggedIn();
  redirectUserType();
  include 'templates/nav.php';
?>
<hr />
<?php

if(isset($_SERVER['QUERY_STRING'])){
  $query_string = $_SERVER['QUERY_STRING'];
  switch ($query_string) {
    case 'user_suspended=true':
          echo '<div class="notification is-success" style="width:600px;margin:0 auto">
                User was successfully suspended.
            </div>';
    break;
    case 'user_activated=true':
          echo '<div class="notification is-success" style="width:600px;margin:0 auto">
                User was successfully reactivated.
            </div>';
    break;
    case 'user_deleted=true':
    echo '<div class="notification is-success" style="width:600px;margin:0 auto">
          User was successfully deleted.
      </div>';
    break;
    default:break;
  }
}
?>
<nav class="level">
  <!-- Left side -->
  <div class="level-left"></div>
  <div class="level-right">
    <div class="level-item">
      <div class="field is-grouped is-grouped-right">
        <p class="control">
          <a class="button is-info" href="/create-user.php">
            <span class="icon"><i class="fa fa-user"></i></span>
            <span>Create User</span>
          </a>
        </p>
      </div>
    </div>
  </div>
</nav>
<?php
  $users = getUsers();
  if(sizeof($users) < 1){
    echo '<div class="notification">
          There are <strong>'. sizeof($users) .
          '</strong> users. To add a user, click
          <button class="button is-info is-small">
          <span class="icon"><i class="fa fa-user"></i></span>
          <span>Create user</span></button> button above.
          </div>';
  }else{
?>
  <table class="table is-hoverable is-fullwidth">
    <thead>
      <tr>
        <th>
          #
        </th>
        <th>Username/Email</th>
        <th>Name</th>
        <th>Created on</th>
        <th>User Type</th>
        <th>Status</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($users as $user): ?>
            <tr>
            <td><?php echo $user['id'] ?></td>
            <td><?php echo $user['email'] ?></td>
            <td><?php echo $user['name'] ?></td>
            <td><?php echo date_format(date_create($user['created_on']), "D M d, Y") ?></td>
            <td><?php echo $user['user_type_title'] ?></td>
            <td><?php echo $user['user_status'] ?></td>
            <td><button class="button is-warning is-outlined is-small"
                        <?php if($user['user_status'] === 'Suspended') echo 'disabled'; ?>
                        onclick="getUser(<?php echo $user['id'].",'suspend'"?>)">
                        <span class="icon"><i class="fa fa-ban"></i></span>
                        <span>Suspend</span>
                </button>
            </td>
            <td><button class="button is-success is-outlined is-small"
                        <?php if($user['user_status'] === 'Active') echo 'disabled'; ?>
                        onclick="getUser(<?php echo $user['id'].",'activate'"?>)">
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <span>Activate</span>
                </button>
            </td>
            <td>
              <button class="button is-danger is-outlined is-small"
                      onclick="getUser(<?php echo $user['id'].",'delete'"?>)">
               <span class="icon"><i class="fa fa-trash"></i></span>
               <span>Delete</span>
             </button>
            </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
  <?php } ?>
</section>

<?php include 'templates/modals/admin_modal.php'; ?>
