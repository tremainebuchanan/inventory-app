<?php
  include '../functions.php';
  if(isset($_SERVER['QUERY_STRING'])){
    $query = $_SERVER['QUERY_STRING'];
    parse_str($query, $output);
    $result = updateUser('status_id', $output['value'], $output['uid']);
    echo json_encode($result);
  }

?>
