<?php
require_once('config.php');

$folder_id = $_POST['folder_id'];

$db = Database::instance();

$query = sprintf(
  "
    UPDATE `favorite_properties`
    SET `date_last_viewed` = NOW()
    WHERE `folder_id` = %s
  ",
  $folder_id
);

$db->query($query);
