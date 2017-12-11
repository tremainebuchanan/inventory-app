<?php
  include '../functions.php';
  $query = $_SERVER['QUERY_STRING'];
  parse_str($query, $output);
  $result = deleteUser($output['id']);
  echo json_encode($result);

?>
