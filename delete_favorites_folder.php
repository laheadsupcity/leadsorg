<?php
require_once('config.php');

$folder_id = $_POST['folder_id'];

$db->delete(
  'favorite_properties',
  array(
    'folder_id' => $folder_id
  )
);

$db->delete(
  'favorite_properties_folders',
  array(
    'folder_id' => $folder_id
  )
);
