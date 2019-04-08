<?php
require_once('config.php');

$parcel_number = $_POST['parcel_number'];
$field = $_POST['field'];
$value = $_POST['value'];
$edit_related = $_POST['edit_related'] == "true";

$owner_address = $db->select(
  'property',
  array('parcel_number' => $parcel_number),
  false,
  false,
  "AND",
  array('full_mail_address', 'mail_address_zip')
);

$owner_address = $db->result_array()[0]['full_mail_address'];

$where_clause = $edit_related && !empty($owner_address)?
  array(
    'full_mail_address' => $owner_address
  ) :
  array('parcel_number' => $parcel_number);

$db->select(
  'property',
  $where_clause,
  false,
  false,
  "AND",
  array('parcel_number')
);

$parcel_numbers = implode(
  ',',
  array_map(
    create_function('$entry', 'return $entry["parcel_number"];'),
    $db->result_array()
  )
);

switch ($field) {
  case 'phone1':
  case 'phone2':
  case 'email1':
  case 'email2':
    $db->update(
      'property',
      array($field => $value),
      sprintf("parcel_number IN (%s)", $parcel_numbers)
    );
    break;
  default:
    // no op
}

echo $parcel_numbers;
