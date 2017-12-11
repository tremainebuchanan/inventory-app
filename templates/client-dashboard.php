<?php
    if(!isset($_SESSION)) session_start();
    require 'functions.php';
    isLoggedIn();
    redirectUserType();
?>
<section class="box">
<?php include 'templates/nav.php'; ?>
<hr />
<nav class="level">
  <div class="level-left"></div>
  <div class="level-right">
    <div class="level-item">
      <div class="field is-grouped is-grouped-right">
        <p class="control">
          <a class="button is-info" href="/create-item.php">
            <span class="icon"><i class="fa fa-plus-circle"></i></span>
            <span>New Item</span>
          </a>
        </p>
      </div>
    </div>
  </div>
</nav>
<?php $items = getItems();
    if(sizeof($items) < 1){
      echo '<div class="notification">
            There are <strong>'. sizeof($items) .
            '</strong> items. To add an item, click
            <button class="button is-info is-small">
            <span class="icon"><i class="fa fa-plus-circle"></i></span>
            <span>New Item</span></button> button above.
            </div>';
    }else{
?>
  <table class="table is-hoverable is-fullwidth">
    <thead>
      <tr>
        <th>Item Name</th>
        <th>Description</th>
        <th>Category</th>
        <th>Unit Cost</th>
        <th>Qty</th>
        <th>Entered on</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($items as $item): ?>
            <tr>
            <td><?php echo $item['item_name'] ?></td>
            <td><?php echo $item['description'] ?></td>
            <td><?php echo $item['title'] ?></td>
            <td><?php echo "$".$item['unit_cost'] ?></td>
            <td><?php echo $item['stock_level'] ?></td>
            <td><?php echo date_format(date_create($item['created_on']), "D M d, Y") ?></td>
            <td>

              <a class="button is-small is-info is-outlined is-rounded"
                  href="update-item.php?id=<?php echo $item['id'] ?>">
                  <span class="icon">
                     <i class="fa fa-pencil-square"></i>
                   </span>
                   <span>Update</span>
                </a>
            </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
  <?php } ?>
</section>
