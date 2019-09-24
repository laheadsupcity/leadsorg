<?php

require_once('CustomDatabaseSearch.php');
require_once('FavoriteProperties.php');
require_once('LoggedInUser.php');

$search_params = array(
  'sortSettings' => $_REQUEST['sortSettings'],
  'page_size' => isset($_REQUEST['page_size']) ? $_REQUEST['page_size'] : 10,
  'page' => isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1
);

if (isset($_REQUEST['case_pcids_to_search'])) {
  $search_params['case_pcids_to_search'] = $_REQUEST['case_pcids_to_search'];
} else {
  $search_params = array_merge($search_params, array(
    'is_open_cases_exclusively' => $_REQUEST['is_open_cases_exclusively'],
    'filter_on_notes' => $_REQUEST['filter_on_notes'],
    'notes_content_to_match' => $_REQUEST['notes_content_to_match'],
    'sortSettings' => $_REQUEST['sortSettings'],
    'num_units_min' => $_REQUEST['num_units_min'],
    'num_units_max' => $_REQUEST['num_units_max'],
    'zip' => isset($_REQUEST['zip_codes']) ? $_REQUEST['zip_codes'] : [],
    'city' => isset($_REQUEST['cities']) ? $_REQUEST['cities'] : [],
    'zoning' => isset($_REQUEST['zoning']) ? $_REQUEST['zoning'] : [],
    'exemption' => isset($_REQUEST['tax_exemption_codes']) ? $_REQUEST['tax_exemption_codes'] : [],
    'casetype' => $_REQUEST['case_types'],
    'num_bedrooms_min' => $_REQUEST['num_bedrooms_min'],
    'num_bedrooms_max' => $_REQUEST['num_bedrooms_max'],
    'num_baths_min' => $_REQUEST['num_baths_min'],
    'num_baths_max' => $_REQUEST['num_baths_max'],
    'num_stories_min' => $_REQUEST['num_stories_min'],
    'num_stories_max' => $_REQUEST['num_stories_max'],
    'cost_per_sq_ft_min' => $_REQUEST['cost_per_sq_ft_min'],
    'cost_per_sq_ft_max' => $_REQUEST['cost_per_sq_ft_max'],
    'lot_area_sq_ft_min' => $_REQUEST['lot_area_sq_ft_min'],
    'lot_area_sq_ft_max' => $_REQUEST['lot_area_sq_ft_max'],
    'sales_price_min' => $_REQUEST['sales_price_min'],
    'sales_price_max' => $_REQUEST['sales_price_max'],
    'is_owner_occupied' => $_REQUEST['is_owner_occupied'],
    'year_built_min' => $_REQUEST['year_built_min'],
    'year_built_max' => $_REQUEST['year_built_max'],
    'sales_date_from' => $_REQUEST['sales_date_from'],
    'sales_date_to' => $_REQUEST['sales_date_to']
  ));
}

$user_id = $_REQUEST['user_id'];

$searcher = new CustomDatabaseSearch($user_id, $search_params);
$properties = $searcher->getResults();
$matching_cases = $searcher->getCasesResults();
$related_properties_counts = $searcher->getRelatedPropertiesCounts();
$all_result_apns = $searcher->getAllResultApns();
$total_records = $searcher->getResultCount();

$show_favorites_flag = false;
$show_matching_cases = true;
$include_related_properties = true;
$properties_only = $_REQUEST['properties_only'] == "true";
$id = 'custom_database_search_results';
$is_admin_user = LoggedInUser::isAdminUser($user_id);
$show_pagination = true;
$select_all = false;
$read_only_fields = false;

ob_start();
include('includes/properties_list.php');
$properties_list_markup = ob_get_clean();

echo json_encode([
  // 'cases_query' => $searcher->cases_query,
  'total_records' => $total_records,
  'all_result_apns' => $all_result_apns,
  'properties_list_markup' => $properties_list_markup,
  'cases_results' => $searcher->getCasesResultsIDs()
]);
