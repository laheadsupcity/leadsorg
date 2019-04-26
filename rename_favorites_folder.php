<?php
require_once('config.php');

$user_id = $_POST['user_id'];
$folder_id = $_POST['folder_id'];
$folder_name = $_POST['folder_name'];

$existing_folder_query = sprintf(
  "
  SELECT * FROM `favorite_properties_folders`
  WHERE
    `name` = '%s' AND
    `user_id` = %s AND
    `folder_id` <> %s
  ",
  $folder_name,
  $user_id,
  $folder_id
);

$db->query($existing_folder_query);

$existing = $db->result_array();

if (count($existing) > 0) {
  echo json_encode(
    array(
      'error' => "duplicate_folder_name"
    )
  );
} else {
  $db->update(
    'favorite_properties_folders',
    array(
      'name' => $folder_name
    ),
    array(
      'folder_id' => $folder_id
    )
  );

  echo json_encode(
    array(
      "response" => "success"
    )
  );
}
