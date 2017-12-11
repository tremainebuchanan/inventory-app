<?php
  require_once 'functions.php';
  if(!isset($_SESSION)) session_start();
  checkSession();
  redirectUserType();
  if(isset($_SERVER['QUERY_STRING'])){
    parse_str($_SERVER['QUERY_STRING'], $query_string);
    $item = getItem(intval($query_string['id']));
  }

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $uri_partial = '?id='.$_POST['id'].'&update_item=success';
    if(!isItemValid($_POST)){
      header('Location: '.$_SERVER['REQUEST_URI'].'?id='.$_POST['id'].'&update_item=failed');
    }else{
      $result = updateItem($_POST);
      if(!$result || $result === 0){
        $uri_partial += 'failed';
      }
      header('Location: '.$_SERVER['REQUEST_URI'].$uri_partial);
    }
  }
?>
<section>
  <div class="box" style="height:auto; min-height:100vh">
    <?php include 'templates/nav.php';
    if(isset($_SERVER['QUERY_STRING'])){
      parse_str($_SERVER['QUERY_STRING'], $success);
      if(isset($success['update_item'])){
        switch ($success['update_item']) {
          case 'success':
          echo '<div class="notification is-success"
                  style="width:600px;margin:0 auto">
                  Item successfully updated.
                  </div>';
            break;
        case 'failed':
        echo '<div class="notification is-danger"
                style="width:600px;margin:0 auto">
                Item was not successfully updated. Check the form for errors or missing fields.
                </div>';
          break;
          default:
            # code...
            break;
        }
      }
    }?>
    <form method="POST"
          action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"
          class="form-create">
      <nav class="level">
        <div class="level-left">
          <div class="level-item">
              <h1 class="title is-4 has-text-centered">Update Item</h1>
          </div>
        </div>

        <input type="hidden" name="id" value="<?php echo $item['id']?>"/>

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
                  <button type="submit"
                         class="button is-primary is-outlined">
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
        <label class="label">Name of Item</label>
        <div class="control has-icons-left">
          <input class="input"
                type="text"
                placeholder="e.g. Bulb"
                name='item_name'
                id="item_name"
                onblur="isEmpty('item_name', 'item_name_error')"
                value="<?php echo $item['item_name']; ?>">
          <span class="icon is-small is-left">
              <i class="fa fa-user"></i>
          </span>
        </div>
        <p class="help is-danger" id="item_name_error" style="display:none"></p>
      </div>

      <div class="field">
         <label class="label">Category</label>
         <div class="control">
           <div class="select">
              <select name="category_id">
                 <?php $categories = getCategories();
                   foreach($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"
                      <?php if($category['id'] === $item['category_id']) echo 'selected'?>>
                        <?php echo $category['title']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
             </div>
         </div>
       </div>

      <div class="field">
        <label class="label">Description</label>
        <div class="control has-icons-left">
          <textarea class="textarea" name="description"><?php echo $item['description'] ?></textarea>
        </div>
      </div>

      <div class="field">
        <label class="label">Unit Cost</label>
        <div class="control">
          <input class="input"
                 type="text"
                 placeholder="e.g. 100"
                 name="unit_cost" value="<?php echo $item['unit_cost'] ?>">
        </div>
      </div>

      <div class="field">
        <label class="label">Stock Level</label>
        <div class="control">
          <input class="input"
                 type="text"
                 placeholder="e.g. 100"
                 name="stock_level"
                 value="<?php echo $item['stock_level'] ?>">
        </div>
      </div>


    </form>

  </div>
</section>
