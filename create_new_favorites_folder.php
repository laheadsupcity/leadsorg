<?php
require_once('config.php');

$user_id = $_POST['user_id'];
$folder_name = $_POST['folder_name'];

$db->insert(
  'favorite_properties_folders',
  array(
    'user_id' => $user_id,
    'name' => $folder_name
  )
);
