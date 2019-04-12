<?php
require_once('config.php');

$parcel_numbers = $_POST['parcel_numbers'];

$query = sprintf(
  "
  SELECT
    `folder_id`,
    COUNT(IF(parcel_number IN ('%s'), 1, NULL)) AS `existing_count`,
    count(`folder_id`) AS `total_count`
  FROM `favorite_properties`
  GROUP BY `folder_id`
  ",
  implode("','", $parcel_numbers)
);

$db->query($query);

echo json_encode($db->result_array());

?>
