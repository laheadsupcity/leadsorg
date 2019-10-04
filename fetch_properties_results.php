<?php
require_once('CustomDatabaseSearch.php');
require_once('LoggedInUser.php');

$search_params = $_REQUEST;

// handle this separately because can't figure out how to properly parse an assoc array
// without doing it upfront
// SHRUG... I HATE PHP
$case_types = isset($_REQUEST['case_types']) ? $_REQUEST['case_types'] : null;

$user_id = $_REQUEST['user_id'];

$searcher = new CustomDatabaseSearch($user_id, $search_params, $case_types);

$properties = $searcher->getResults();
$matching_cases = $searcher->getCasesResults();
$all_result_apns = $searcher->getAllResultApns();
$total_records = $searcher->getResultCount();

//// variables for template files
$show_favorites_flag = isset($_REQUEST['show_favorites_flag']) && $_REQUEST['show_favorites_flag'] == "true";
$show_matching_cases = isset($_REQUEST['show_matching_cases']) && $_REQUEST['show_matching_cases'] == "true";
$include_related_properties = isset($_REQUEST['include_related_properties']) && $_REQUEST['include_related_properties'] == "true";
$select_all = isset($_REQUEST['select_all']) && $_REQUEST['select_all'] == "true";
$read_only_fields = isset($_REQUEST['read_only_fields']) && $_REQUEST['read_only_fields'] == "true";
////

ob_start();
include('includes/properties_list.php');
$properties_list_markup = ob_get_clean();

ob_start();
include('includes/search_results/pagination.php');
$pagination_markup = ob_get_clean();

echo json_encode([
  'cases_query' => $searcher->cases_query,
  'total_records' => $total_records,
  'all_result_apns' => $all_result_apns,
  'properties_list_markup' => $properties_list_markup,
  'pagination_markup' => $pagination_markup,
  'cases_results' => $searcher->getCasesResultsIDs()
]);
