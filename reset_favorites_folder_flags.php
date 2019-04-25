<?php
require_once('config.php');

$folder_id = $_POST['folder_id'];
$parcel_numbers = $_POST['parcel_numbers'];

$db = Database::instance();

$query = sprintf(
  "
    UPDATE `favorite_properties`
    SET `date_last_viewed` = NOW()
    WHERE
      `folder_id` = %s AND
      `parcel_number` IN ('%s')
  ",
  $folder_id,
  implode("','", $parcel_numbers)
);

$db->query($query);
