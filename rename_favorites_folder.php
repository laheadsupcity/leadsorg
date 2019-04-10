<?php
require_once('config.php');

$folder_id = $_POST['folder_id'];
$folder_name = $_POST['folder_name'];

$db->update(
  'favorite_properties_folders',
  array(
    'name' => $folder_name
  ),
  array(
    'folder_id' => $folder_id
  )
);
