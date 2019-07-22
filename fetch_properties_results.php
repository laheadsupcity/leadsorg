<?php

require_once('CustomDatabaseSearch.php');
require_once('FavoriteProperties.php');
require_once('LoggedInUser.php');

$search_params = array(
  'num_units_min' => $_GET['num_units_min'],
  'num_units_max' => $_GET['num_units_max'],
  'zip' => isset($_GET['zip_codes']) ? $_GET['zip_codes'] : [],
  'city' => isset($_GET['cities']) ? $_GET['cities'] : [],
  'zoning' => isset($_GET['zoning']) ? $_GET['zoning'] : [],
  'exemption' => isset($_GET['tax_exemption_codes']) ? $_GET['tax_exemption_codes'] : [],
  'casetype' => $_GET['case_types'],
  'num_bedrooms_min' => $_GET['num_bedrooms_min'],
  'num_bedrooms_max' => $_GET['num_bedrooms_max'],
  'num_baths_min' => $_GET['num_baths_min'],
  'num_baths_max' => $_GET['num_baths_max'],
  'num_stories_min' => $_GET['num_stories_min'],
  'num_stories_max' => $_GET['num_stories_max'],
  'cost_per_sq_ft_min' => $_GET['cost_per_sq_ft_min'],
  'cost_per_sq_ft_max' => $_GET['cost_per_sq_ft_max'],
  'lot_area_sq_ft_min' => $_GET['lot_area_sq_ft_min'],
  'lot_area_sq_ft_max' => $_GET['lot_area_sq_ft_max'],
  'sales_price_min' => $_GET['sales_price_min'],
  'sales_price_max' => $_GET['sales_price_max'],
  'is_owner_occupied' => $_GET['is_owner_occupied'],
  'year_built_min' => $_GET['year_built_min'],
  'year_built_max' => $_GET['year_built_max'],
  'sales_date_from' => $_GET['sales_date_from'],
  'sales_date_to' => $_GET['sales_date_to'],
  'is_open_cases_exclusively' => $_GET['is_open_cases_exclusively'],
  'filter_on_notes' => $_GET['filter_on_notes'],
  'notes_content_to_match' => $_GET['notes_content_to_match']
);

$current_page = isset($_GET["page"]) ? $_GET["page"] : 1;
$num_rec_per_page = isset($_REQUEST['num_rec_per_page']) ? $_REQUEST['num_rec_per_page'] : 10;
$user_id = $_GET['user_id'];

$searcher = new CustomDatabaseSearch($user_id, $search_params);
$properties = $searcher->getResults($num_rec_per_page, $current_page);
$matching_cases = $searcher->getMatchingCasesForProperties();
$related_properties_counts = $searcher->getRelatedPropertiesCounts();
$total_records = $searcher->getResultCount();

$show_favorites_flag = false;
$show_matching_cases = true;
$include_related_properties = true;
$properties_only = $_GET['properties_only'] == "true";
$id = 'custom_database_search_results';
$is_admin_user = LoggedInUser::isAdminUser($user_id);
$show_pagination = true;

ob_start();
include('includes/properties_list.php');
$properties_list_markup = ob_get_clean();

echo json_encode([
  'total_records' => $total_records,
  'properties_list_markup' => $properties_list_markup
]);
