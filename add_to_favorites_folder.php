<?php
require_once('config.php');

$user_id = $_POST['user_id'];

$folder_ids = $_POST['folder_ids'];

$parcel_numbers = $_POST['parcel_numbers'];

$should_move_instead_of_add = $_POST['should_move_instead_of_add'] == "true";

if (empty($folder_ids) || empty($parcel_numbers)) {
  return;
}

if ($should_move_instead_of_add) {
  $query = sprintf(
    "
    DELETE FROM `favorite_properties`
    WHERE `folder_id` IN (
      SELECT `folder_id` FROM favorite_properties_folders
      WHERE `user_id` = %s
    ) AND
    `parcel_number` IN (%s)
    ",
    $user_id,
    implode(',', $parcel_numbers)
  );

  $db->query($query);
}

foreach ($folder_ids as $folder_id) {
  $db->select(
    'favorite_properties',
    array(
      'parcel_number' => $parcel_numbers,
      'folder_id' => $folder_id
    )
  );

  $apns_to_exclude = array_map(
    function ($property) {
      return $property->parcel_number;
    },
    $db->result()
  );

  $parcel_numbers_to_search = array_diff($parcel_numbers, $apns_to_exclude);

  foreach ($parcel_numbers_to_search as $parcel_number) {
    $db->insert(
      'favorite_properties',
      array(
        'parcel_number' => $parcel_number,
        'folder_id' => $folder_id
      )
    );
  }
}
