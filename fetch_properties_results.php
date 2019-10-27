<?php
require_once('CustomDatabaseSearch.php');
require_once('LoggedInUser.php');
require_once('FavoriteProperties.php');

$search_params = $_REQUEST;

// handle this separately because can't figure out how to properly parse an assoc array
// without doing it upfront
// SHRUG... I HATE PHP
$case_types = isset($_REQUEST['case_types']) ? $_REQUEST['case_types'] : null;

$user_id = $_REQUEST['user_id'];

//// variables for template files
$show_favorites_flag = isset($_REQUEST['show_favorites_flag']) && $_REQUEST['show_favorites_flag'] == "true";
$show_matching_cases = isset($_REQUEST['show_matching_cases']) && $_REQUEST['show_matching_cases'] == "true";
$include_related_properties = isset($_REQUEST['include_related_properties']) && $_REQUEST['include_related_properties'] == "true";
$select_all = isset($_REQUEST['select_all']) && $_REQUEST['select_all'] == "true";
$read_only_fields = isset($_REQUEST['read_only_fields']) && $_REQUEST['read_only_fields'] == "true";
////

if ($_REQUEST['related_apns_for_parcel_number']) {
  $properties = Property::getRelatedPropertiesForAPN($_REQUEST['related_apns_for_parcel_number']);

  $all_result_apns = array_map(
    function($result) {
      return $result['parcel_number'];
    },
    $properties
  );

  $total_records = count($properties);

  $result_data = [
    'total_records' => $total_records,
    'all_result_apns' => $all_result_apns
  ];
} else if ($_REQUEST['apns_for_favorites_folder']) {
  $folder_id = $_REQUEST['apns_for_favorites_folder'];
  $favorites = new FavoriteProperties();
  $folder = $favorites->getFolderFromID($folder_id);
  $properties = $favorites->getPropertiesForFolder($user_id, $folder_id);

  $all_result_apns = array_map(
    function($result) {
      return $result['parcel_number'];
    },
    $properties
  );

  $result_data = [
    'total_records' => count($properties),
    'folder_name' => $folder['name'],
    'all_result_apns' => $all_result_apns
  ];
} else {
  $searcher = new CustomDatabaseSearch($user_id, $search_params, $case_types);

  $properties = $searcher->getResults();
  $matching_cases = $searcher->getCasesResults();
  $all_result_apns = $searcher->getAllResultApns();
  $total_records = $searcher->getResultCount();

  $result_data = [
    'cases_query' => $searcher->cases_query,
    'total_records' => $total_records,
    'all_result_apns' => $all_result_apns,
    'cases_results' => $searcher->getCasesResultsIDs()
  ];
}

ob_start();
include('includes/properties_list.php');
$properties_list_markup = ob_get_clean();

ob_start();
include('includes/search_results/pagination.php');
$pagination_markup = ob_get_clean();

$result_data = array_merge(
  $result_data,
  [
    'properties_list_markup' => $properties_list_markup,
    'pagination_markup' => $pagination_markup
  ]
);

echo json_encode($result_data);
