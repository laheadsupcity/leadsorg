<?php
require_once('config.php');

$folder_id = $_POST['folder_id'];
$parcel_numbers = explode(',', $_POST['parcel_numbers']);

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
