<?php
require_once('config.php');

$selected_property_data = $_POST['selected_property_data'];

if (!empty($selected_property_data)) {
  $filename = "toy_csv.csv";
  $fp = fopen('php://output', 'w');
  $table = array();
  $objmerged = array();

  foreach ($selected_property_data as $index => $data) {
    $parcel_number = $data['parcel_number'];
    $csv_data = getCSVExportData($parcel_number);
    $csv_data['matching_cases'] = $data['matching_cases'];
    $csv_data['private_note'] = $data['private_note'];
    $csv_data['public_note'] = $data['public_note'];

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

function getCSVExportData($id)
{
  $db = Database::instance();
  $db->getCSVExportDataForProperty($id);
  $result=$db->result_array();
  return $result[0];
}
