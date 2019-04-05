<?php
require_once('config.php');

$parcel_number = $_POST['parcel_number'];
$notes = $_POST['notes'];

$db->update(
  'property',
  array('notes' => $notes),
  array(
    'parcel_number' => $parcel_number
  )
);
