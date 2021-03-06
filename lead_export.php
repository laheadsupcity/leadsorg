<?php
require_once('config.php');
require_once('Property.php');

$selected_property_data = $_POST['selected_property_data'];
$user_id = $_REQUEST['user_id'];

if (!empty($selected_property_data)) {
  $filename = "toy_csv.csv";
  $fp = fopen('php://output', 'w');
  $table = array();
  $objmerged = array();

  foreach ($selected_property_data as $parcel_number) {
    $property = new Property();
    $csv_data = $property->getCSVExportDataForAPN($user_id, $parcel_number);

    $objmerged[] = array_merge((array) $csv_data, (array) $table);
  }

  foreach ($objmerged as $k=>$v) {
    $header[] = array_keys($objmerged[0]);
  }

  header('Content-type: application/csv');
  header('Content-Disposition: attachment; filename='.$filename);
  fputcsv($fp, $header[0]);

  foreach ($objmerged as $k=>$v) {
    fputcsv($fp, $v);
  }
  exit;
}
