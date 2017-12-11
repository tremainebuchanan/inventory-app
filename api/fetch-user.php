<?php
  include '../functions.php';
  $query = $_SERVER['QUERY_STRING'];
  parse_str($query, $output);
  $user = getUser($output['id']);
  echo json_encode($user);
?>
