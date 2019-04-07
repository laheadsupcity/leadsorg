<?php
require_once('config.php');

// TODO: When user logins are properly implemented then this will no longer be hardcoded
$user_id = 1;
$folder_name = $_POST['folder_name'];

$db->insert(
  'favorite_properties_folders',
  array(
    'user_id' => $user_id,
    'name' => $folder_name
  )
);
