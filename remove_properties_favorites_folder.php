<?php
require_once('config.php');

$folder_id = $_POST['folder_id'];
$parcel_numbers = $_POST['parcel_numbers'];

foreach ($parcel_numbers as $parcel_number) {
  $db->delete(
    'favorite_properties',
    sprintf('parcel_number IN (%s) AND folder_id = %s', implode(',', $parcel_numbers), $folder_id)
  );
}

echo $parcel_numbers;
