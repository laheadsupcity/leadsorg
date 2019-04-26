<?php
require_once('config.php');

$user_id = $_POST['user_id'];
$folder_name = $_POST['folder_name'];

$db->select(
  'favorite_properties_folders',
  array(
    'name' => $folder_name,
    'user_id' => $user_id
  )
);

$existing = $db->result_array();

if (count($existing) > 0) {
  echo json_encode(
    array(
      'error' => "duplicate_folder_name"
    )
  );
} else {
  $db->insert(
    'favorite_properties_folders',
    array(
      'user_id' => $user_id,
      'name' => $folder_name
    )
  );

  echo json_encode(
    array(
      "response" => "success"
    )
  );
}
