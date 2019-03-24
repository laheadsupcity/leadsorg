<?php
require_once('config.php');

$parcel_number = $_POST['parcel_number'];
$field = $_POST['field'];
$value = $_POST['value'];

switch ($field) {
  case 'phone1':
  case 'phone2':
  case 'email1':
  case 'email2':
    $db->update(
      'property',
      array($field => $value),
      array(
        'parcel_number' => $parcel_number
      )
    );
    break;
  default:
    // no op
}
