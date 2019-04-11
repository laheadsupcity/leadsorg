<?php
require_once('config.php');

$folder_id = $_POST['folder_id'];
$parcel_numbers = explode(',', $_POST['parcel_numbers']);

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

$parcel_numbers = array_diff($parcel_numbers, $apns_to_exclude);

foreach ($parcel_numbers as $parcel_number) {
  $db->insert(
    'favorite_properties',
    array(
      'parcel_number' => $parcel_number,
      'folder_id' => $folder_id
    )
  );
}

echo $parcel_numbers;
