<?php
require_once('config.php');

$folder_ids = $_POST['folder_ids'];

$parcel_numbers = explode(',', $_POST['parcel_numbers']);

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
