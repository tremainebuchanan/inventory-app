<?php
require 'configs/config.php';
/**
 * Determines if a user is logged in.
 * If no user is logged in, the application
 * redirects the user to the login page
 */
function isLoggedIn(){
  if(!isset($_SESSION['username'])) header('Location: /');
}
/**
 * Prevents a user from accessing unauthorized components of the application.
 * If the user type is 'administrator', the user is only allowed to view
 * administrative functions within the application.
 */
function redirectUserType(){
  // $admin_urls = array('admin', 'create-user');
  // $client_urls = array('client', 'create-item', 'profile', 'update-item', 'update-user');
  if(isset($_SESSION['user_type'])){
    $user_type = $_SESSION['user_type'];
    $request_uri = explode('.', $_SERVER['REQUEST_URI']);
    $uri = explode('/', $request_uri[0]);
    if(intval($user_type) === 1){ #administrator
       if(!in_array($uri[1], ADMIN_URLS)){
         header('Location: admin.php');
       }
    }else{
      if(!in_array($uri[1], CLIENT_URLS)){
        header('Location: client.php');
      }
    }
  }
}
/**
 * Establishes a connection to the database.
 * If connected, the connection is returned, else an error is thrown.
 */
function getDBConnection(){
  try {
    $connection = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBUSER, DBPASS);
    return $connection;
  } catch (PDOException $e) {
    echo $e->getMessage();
    die();
  }
}
/**
 * Authenticates a user based on email and password provided.
 * @param  [type] $email    [description]
 * @param  [type] $password [description]
 */
function login($email, $password){
  try {
    $result = 'Email/password invalid';
    $db = getDBConnection();
    $sql = 'SELECT id, email, password, user_type
            FROM users
            WHERE email = :email
            AND status_id = :status_id';
    $values = array( ':email'=> $email, ':status_id' => ACTIVE_STATUS_ID);
    $stmt = $db->prepare($sql);
    $stmt->execute($values);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(password_verify($password, $user['password'])){
      $user['password'] = '';
      return $user;
    }
    return false;
  } catch (Exception $e) {
    return $e->getMessage();
  }
}
/**
 * Adds a new user to the database.
 * @param  [type] $user       The details of the user
 * @param  [type] $created_by User id representing the creator of the record
 */
function createUser( $user, $created_by ){
  try {
    $default_password = DEFAULT_USER_PASSWORD;
    $db = getDBConnection();
    $sql = 'INSERT INTO users (id, name, email, password, user_type, created_on, updated_on, created_by, status_id)
            VALUES(null, :name, :email, :password, :user_type, null, null, :created_by, :status_id)';
    $values = array(
      ':name' => $user['fullname'],
      ':email'=> $user['email'],
      ':password' => password_hash($default_password, PASSWORD_DEFAULT),
      ':created_by' => $created_by,
      ':user_type' => $user['user_type'],
      ':status_id' => ACTIVE_STATUS_ID
    );
    $stmt = $db->prepare($sql);
    $stmt->execute($values);
    return $db->lastInsertId();
  } catch (Exception $e) {
    return $e->getMessage();
  }
}
/**
 * Creates an inventory item.
 * @param  [type] $item The attributes of a item
 */
function createItem( $item ){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("INSERT INTO items (id, item_name, description,
                                             unit_cost, stock_level, category_id,
                                             created_on, updated_on,
                                             deleted, created_by)
                          VALUES (null, :item_name, :description, :unit_cost,
                                  :stock_level, :category_id, null, null, :deleted, :created_by)");
    $values = array(':item_name'=> trim($item['item_name']),
                    ':description' => trim($item['description']),
                    ":unit_cost"=> intval($item['unit_cost']),
                    ":stock_level"=> intval($item['stock_level']),
                    ":category_id" => intval($item['category_id']),
                    ":deleted" => DELETED_FLAG,
                    ":created_by" => getUserID()  );
    $stmt->execute($values);
    return $db->lastInsertId();
  } catch (PDOException $e) {
    return false;
  }
}
/**
 * Retrieves a list of items from the database.
 */
function getItems(){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT items.id, items.item_name, items.description,
                                 items.unit_cost, items.stock_level,
                                 items.category_id, categories.title,
                                 items.created_on
                                 FROM items INNER JOIN categories
                                 ON categories.id = items.category_id");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Retrieves a user by a user id
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function getUser( $id ){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT id, name, email, password FROM users WHERE id = :id");
    $stmt->execute(array(":id" => $id ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Retrieves an item from the database by the item's id
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function getItem( $id ){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT items.id, items.item_name, items.description,
                                 items.unit_cost, items.stock_level,
                                 items.category_id, categories.title,
                                 items.created_on
                                 FROM items INNER JOIN categories
                                 ON categories.id = items.category_id
                                 WHERE items.id =:id");
    $stmt->execute(array(":id"=>$id ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Retrieves a list of users from the database
 * @return [type] [description]
 */
function getUsers(){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT users.id, users.name, users.email,
                          usertypes.title AS `user_type_title`,
                          status.title AS `user_status`, users.created_on
                          FROM users
                          INNER JOIN usertypes ON usertypes.id = users.user_type
                          INNER JOIN status ON users.status_id = status.id
                          ORDER BY `user_type_title` ASC");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Retrieves a list of item categories from the database.
 * @return [type] [description]
 */
function getCategories(){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT id, title FROM categories");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Retrieves a list of user types from the database.
 * @return [type] [description]
 */
function getUserTypes(){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT id, title FROM usertypes");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Updates a user's record.
 * @param  [type] $column The name of the column to be updated
 * @param  [type] $value  The new value
 * @param  [type] $uid    The id of the user
 */
function updateUser($column, $value, $uid){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE users SET $column = :value WHERE id = :id");
    $values = array(
      ":value" => $value,
      ":id" => intval($uid)
    );
    $stmt->execute($values);
    return $stmt->rowCount();
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Updates a user's profile.
 * @param  [type] $user [description]
 */
function updateProfile($user){
  try {
    if(isset($user['new_password'])){
      $password = password_hash($user['new_password'], PASSWORD_DEFAULT);
    }else{
      $password = $user['current_password'];
    }
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE users
                          SET name = :name,
                          email =:email,
                          password =:password WHERE id = :id");
    $values = array(
      ":name" => $user['fullname'],
      ":id" => intval($user['id']),
      ":email" => $user['email'],
      ":password" => $password
    );
    $stmt->execute($values);
    return $stmt->rowCount();
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Updates an item
 * @param  [type] $item [description]
 */
function updateItem($item){
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE items
                         SET item_name = :item_name,
                            description = :description,
                            unit_cost = :unit_cost,
                            category_id = :category_id,
                            stock_level = :stock_level WHERE id = :id");
    $values = array(
      ":id" => $item['id'],
      ":item_name" => $item['item_name'],
      ":description" => $item['description'],
      ":unit_cost" => $item['unit_cost'],
      ":category_id" => $item['category_id'],
      ":stock_level" => $item['stock_level']
    );
    $stmt->execute($values);
    return $stmt->rowCount();
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Removes a user from the database given an id
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function deleteUser($id){
  if(getUserID() === $id) return false;
  try {
    $db = getDBConnection();
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $values = array( ":id" => intval($id) );
    $stmt->execute($values);
    return true;
  } catch (PDOException $e) {
    return array();
  }
}
/**
 * Deletes a session
 */
function destroySession(){
  session_destroy();
  $_SESSION = array();
}
/**
 * Saves user credentials to a PHP session.
 * @param [type] $user [description]
 */
function setSession( $user ){
  $_SESSION['username'] = $user['email'];
  $_SESSION['uid'] = $user['id'];
  $_SESSION['user_type'] = $user['user_type'];
}
/**
 * Retrieves the user id currently in a PHP session
 * @return [type] [description]
 */
function getUserID(){
  if(isset($_SESSION)) return $_SESSION['uid'];
}
/**
 * Valids an inventory item for empty fields.
 * @param  [type]  $item [description]
 * @return boolean       [description]
 */
function isItemValid( $item ){
  if(empty($item['item_name']) ||
     empty($item['category_id']) ||
     empty($item['description']) ||
     empty($item['unit_cost']) ||
     empty($item['stock_level']) ){
       return false;
     }
  return true;
}

?>
