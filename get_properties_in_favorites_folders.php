<?php
require_once('config.php');

$user_id = $_POST['user_id'];
$selected_parcel_numbers = $_POST['selected_parcel_numbers'];

$query = sprintf(
  "
  SELECT
    `f`.`folder_id`,
    `f`.`name`,
    COUNT(IF(parcel_number IN ('%s'), 1, NULL)) AS `existing_count`,
    count(`p`.`folder_id`) AS `total_count`
  FROM `favorite_properties_folders` AS `f`
  LEFT JOIN `favorite_properties` AS `p` ON (
    `p`.`folder_id` = `f`.`folder_id`
  )
  WHERE `user_id` = %s
  GROUP BY `f`.`folder_id`;
  ",
  implode("','", $selected_parcel_numbers),
  $user_id
);

$db->query($query);

echo json_encode($db->result_array());

?>
