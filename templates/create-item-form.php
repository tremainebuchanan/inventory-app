<?php
    require_once 'functions.php';
    if(!isset($_SESSION)) session_start();
    checkSession();
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      $uri_partial = '?item_created=success';
      if(!isItemValid($_POST)){
        $uri_partial = '?item_created=failed';
        header('Location: '.$_SERVER['REQUEST_URI'].$uri_partial);
      }else{
        $result = createItem($_POST);
        if(!$result || $result === 0){
          $uri_partial = '?item_created=failed';
        }
        header('Location: '.$_SERVER['REQUEST_URI'].$uri_partial);
      }
    }
 ?>
<section class="box">
  <?php include 'templates/nav.php';
      if(isset($_SERVER['QUERY_STRING'])){
        $query_string = $_SERVER['QUERY_STRING'];
        switch ($query_string) {
          case 'item_created=success':
                echo '<div class="notification is-success" style="width:600px;margin:0 auto">
                      Item succesfully created
                  </div>';
          break;
          case 'item_created=failed':
          echo '<div class="notification is-danger" style="width:600px;margin:0 auto">
                Please check your form for errors or missing fields!
            </div>';
          break;
          default:break;
        }
      }
  ?>
  <form method="POST"
        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
        class="form-create" id="item-form"
        novalidate>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
            <h1 class="title is-4 has-text-centered">New Inventory</h1>
        </div>
      </div>
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
                <button type="submit" class="button is-outlined is-success">
                  <span class="icon">
                      <i class="fa fa-check"></i>
                    </span>
                    <span>Save</span>
                </button>
              </p>
            </div>
          </div>
      </div>
    </nav>

    <hr />
    <div class="field">
      <label class="label">Name of Item</label>
      <div class="control">
        <input class="input"
               type="text"
               placeholder="e.g. Bulb"
               name='item_name'
               id="item_name"
               onblur="isEmpty('item_name', 'item_name_error')"
               required>
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
                  <option value="<?php echo $category['id'] ?>">
                      <?php echo $category['title'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
           </div>
       </div>
     </div>

    <div class="field">
      <label class="label">Description</label>
      <div class="control has-icons-left">
        <textarea class="textarea" name="description"></textarea>
      </div>
    </div>

    <div class="field">
      <label class="label">Unit Cost</label>
      <div class="control">
        <input class="input"
               type="text"
               placeholder="e.g. 100"
               name="unit_cost"
               id="unit_cost"
               required
               onblur="isValidNumber('unit_cost', 'unit_cost_error')">
      </div>
      <p class="help is-danger" id="unit_cost_error"></p>
    </div>

    <div class="field">
      <label class="label">Stock Level</label>
      <div class="control">
        <input class="input"
               type="text"
               placeholder="e.g. 100"
               name="stock_level"
               id="stock_level"
               required
               onblur="isValidNumber('stock_level', 'stock_level_error')">
        <p class="help is-danger" id="stock_level_error"></p>
      </div>
    </div>
  </form> <!-- end of form -->
</section>
