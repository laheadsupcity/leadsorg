<?php
require_once('config.php');
include('CaseTypeFilters.php');
include('SearchParameters.php');

class CustomDatabaseSearch {

  private $search_params;
  private $results_count;

  function __construct($search_param_data) {
    $this->db = Database::instance();
    $this->search_params = new SearchParameters($search_param_data);
  }

  private function getOpenAPNsAfterExclusions($case_type_filter_builder) {
    $excluded_ids = null;
    $included_ids = null;

    if (!empty($case_type_filter_builder->getExclusionFilters())) {
      $excluded_ids = $case_type_filter_builder->getExcludedIDsExpression();
    }

    if (!empty($case_type_filter_builder->getInclusionFilters())) {
      $included_ids = $case_type_filter_builder->getIncludedIDsExpression();
    }

    $basic_select = "SELECT APN as open_APN, case_id as open_case_id FROM property_cases
      GROUP BY open_APN, open_case_id
      HAVING SUM(CASE WHEN case_date <> \"\" THEN 1 ELSE 0 END) = 0";

    $conditions = [];
    if ($excluded_ids) {
      $conditions[] = sprintf(
        "SUM(CASE WHEN case_type_id IN (\"%s\") THEN 1 ELSE 0 END) = 0",
        $excluded_ids
      );
    }
    if ($included_ids) {
      $conditions[] = sprintf(
          "SUM(CASE WHEN case_type_id IN (\"%s\") THEN 1 ELSE 0 END) > 0",
          $included_ids
        );
    }

    if (!empty($conditions)) {
      $query = sprintf(
        "SELECT APN, case_id, case_type_id FROM property_cases
        JOIN (
          %s
        ) as open_cases
        ON open_cases.open_APN = property_cases.APN AND open_cases.open_case_id = property_cases.case_id
        GROUP BY APN, case_id, case_type_id
        HAVING %s;",
        $basic_select,
        implode(" AND ", $conditions)
      );
    } else {
      $query = sprintf(
        "SELECT APN, case_id, case_type_id FROM property_cases
        JOIN (
          %s
        ) as open_cases
        ON open_cases.open_APN = property_cases.APN AND open_cases.open_case_id = property_cases.case_id;",
        $basic_select
      );
    }

    $this->db->query($query);

    $results = $this->db->result_array();

    $excluded_apns = [];
    if ($excluded_ids) {
      $excluded_apns_query = sprintf(
        "SELECT APN FROM property_cases
        JOIN (
          SELECT APN as open_APN, case_id as open_case_id FROM property_cases
            GROUP BY open_APN, open_case_id
            HAVING SUM(CASE WHEN case_date <> \"\" THEN 1 ELSE 0 END) = 0
        ) as open_cases
        ON open_cases.open_APN = property_cases.APN AND open_cases.open_case_id = property_cases.case_id
        WHERE property_cases.case_type_id IN (\"%s\");",
        $excluded_ids
      );
      $this->db->query($excluded_apns_query);
      $excluded_apns_results = $this->db->result_array();

      $excluded_apns = array_map(
        create_function('$data', 'return $data["APN"];'),
        $excluded_apns_results
      );
    }

    $matching_apns = $this->filterOnConstraints($results, $excluded_apns, $case_type_filter_builder);

    return $matching_apns;
  }

  private function filterOnConstraints($apn_case_id_data, $excluded_apns, $case_type_filter_builder) {
    $case_type_map = [];
    $all_apns = [];

    foreach ($apn_case_id_data as $data) {
      $apn = $data['APN'];
      $case_id = $data['case_id'];
      $case_type_id = $data['case_type_id'];

      if (in_array($apn, $excluded_apns)) {
        continue;
      }

      $case_type_map[$case_type_id][$apn][] = $case_id;

      $all_apns[] = $apn;
    }

    if (empty($case_type_filter_builder->getInclusionFilters())) {
      return $all_apns;
    }

    $matching_apns = [];
    foreach ($case_type_map as $case_type_id => $case_type_apns) {
      $filter = $case_type_filter_builder->getInclusionFilterForCaseTypeID($case_type_id);

      if (!isset($filter)) {
        continue;
      }

      foreach ($case_type_apns as $apn => $apn_cases) {
        $matches_count = 0;
        foreach ($apn_cases as $case_id) {
          if ($filter->doesMatch($case_id)) {
            $matches_count += 1;
          }
        }
        if ($matches_count > 0) {
          $matching_apns[] = $apn;
        }
      }
    }

    return $matching_apns;
  }

  public function getResults($limit = 100)
  {
      $case_type_filters = $this->search_params->getCaseTypeFilters();
      $case_type_filter_builder = new CaseTypeFilters($case_type_filters);
      $relevant_apns = $this->getOpenAPNsAfterExclusions($case_type_filter_builder);

      $conditions = $this->getConditions($relevant_apns);

      $where = implode(' AND ', $conditions);

      $select = "SELECT * FROM property";


      $query = sprintf(
        "%s
        WHERE (
          %s
        )
        ",
        $select,
        $where
      );

      $this->db->query($query . ";");

      $this->results_count = count($this->db->result_array());

      $this->db->query(
        sprintf(
          "%s LIMIT %s;",
          $query,
          $limit
        )
      );

      $results = $this->db->result_array();

      return $results;
  }

  public function getResultCount() {
    return $this->results_count;
  }

  public function getConditions($relevant_apns) {
    $search_param_data = $this->search_params->getSearchParamData();

    $conditions = array();

    $conditions[] = sprintf(
      "(parcel_number IN ('%s'))",
      implode("','", $relevant_apns)
    );

    $num_units_min = $search_param_data['num_units_min'];
    $num_units_max = $search_param_data['num_units_max'];

    $zips = $this->search_params->getZips();
    $cities = $this->search_params->getCities();
    $zoning = $this->search_params->getZoning();
    $exemption = $this->search_params->getExemption();

    $num_beds_min = $search_param_data['num_bedrooms_min'];
    $num_beds_max = $search_param_data['num_bedrooms_max'];

    $num_baths_min = $search_param_data['num_baths_min'];
    $num_baths_max = $search_param_data['num_baths_max'];

    $num_stories_min = $search_param_data['num_stories_min'];
    $num_stories_max = $search_param_data['num_stories_max'];

    $cost_per_sq_ft_min = $search_param_data['cost_per_sq_ft_min'];
    $cost_per_sq_ft_max = $search_param_data['cost_per_sq_ft_max'];

    $lot_area_sq_ft_min = $search_param_data['lot_area_sq_ft_min'];
    $lot_area_sq_ft_max = $search_param_data['lot_area_sq_ft_max'];

    $sales_price_min = $search_param_data['sales_price_min'];
    $sales_price_max = $search_param_data['sales_price_max'];

    $is_owner_occupied = $search_param_data['is_owner_occupied'];

    $year_built_min = $search_param_data['year_built_min'];
    $year_built_max = $search_param_data['year_built_max'];

    $sales_date_min = $search_param_data['sales_date_from'];
    $sales_date_max = $search_param_data['sales_date_to'];

    if ($num_units_min != '' && $num_units_max == '') {
        $conditions[]= '(number_of_units >='.$num_units_min.')' ;
    } elseif ($num_units_min == '' && $num_units_max != '') {
        $conditions[]= '(number_of_units <='.$num_units_max.')';
    } elseif (!($num_units_min =='' && $num_units_max =='')) {
        $conditions[]=  '(number_of_units >='.$num_units_min.' and number_of_units <='.$num_units_max.')';
    }

    if (!empty($zips)) {
      $conditions[]=  '(site_address_zip IN (' . implode(',', array_map('strval', $zips)) . '))';
    }

    if (!empty($cities)) {
      $conditions[]=  '(site_address_city_state IN ("' . implode('","', array_map('strval', $cities)) . '"))';
    }

    if (!empty($zoning)) {
      $conditions[]=  '(zoning IN ("' . implode('","', array_map('strval', $zoning)) . '"))';
    }

    if (!empty($exemption)) {
      $conditions[]=  '(tax_exemption_code IN ("' . implode(',', array_map('strval', $exemption)) . '"))';
    }

    if ($num_beds_min !='' && $num_beds_max =='') {
      $conditions[]= '(bedrooms >='.$num_beds_min.')' ;
    } elseif ($num_beds_min =='' && $num_beds_max !='') {
      $conditions[]= '(bedrooms <='.$num_beds_max.')';
    } elseif (!($num_beds_min =='' && $num_beds_max =='')) {
      $conditions[]=  '(bedrooms >='.$num_beds_min.' and bedrooms <='.$num_beds_max.')';
    }

    if ($is_owner_occupied != "NA") {
      $conditions[]=  '(owner_occupied ="'.$is_owner_occupied.'")';
    }

    if ($num_baths_min !='' && $num_baths_max =='') {
      $conditions[]= '(bathrooms >='.$num_baths_min.')' ;
    } elseif ($num_baths_min =='' && $num_baths_max !='') {
      $conditions[]= '(bathrooms <='.$num_baths_max.')';
    } elseif (!($num_baths_min =='' && $num_baths_max =='')) {
      $conditions[]=  '(bathrooms >='.$num_baths_min.' and bathrooms <='.$num_baths_max.')';
    }

    if ($num_stories_min !='' && $num_stories_max =='') {
      $conditions[]= '(number_of_stories >='.$num_stories_min.')' ;
    } elseif ($num_stories_min =='' && $num_stories_max !='') {
      $conditions[]= '(number_of_stories <='.$num_stories_max.')';
    } elseif (!($num_stories_min =='' && $num_stories_max =='')) {
      $conditions[]=  '(number_of_stories >='.$num_stories_min.' and number_of_stories <='.$num_stories_max.')';
    }

    if ($cost_per_sq_ft_min !='' && $cost_per_sq_ft_max =='') {
      $conditions[]= '(cost_per_sq_ft >='.$cost_per_sq_ft_min.')' ;
    } elseif ($cost_per_sq_ft_min =='' && $cost_per_sq_ft_max !='') {
      $conditions[]= '(cost_per_sq_ft <='.$cost_per_sq_ft_max.')';
    } elseif (!($cost_per_sq_ft_min =='' && $cost_per_sq_ft_max =='')) {
      $conditions[]=  '(cost_per_sq_ft >='.$cost_per_sq_ft_min.' and cost_per_sq_ft <='.$cost_per_sq_ft_max.')';
    }

    if ($lot_area_sq_ft_min !='' && $lot_area_sq_ft_max =='') {
      $conditions[]= '(lot_area_sqft >='.$lot_area_sq_ft_min.')' ;
    } elseif ($lot_area_sq_ft_min =='' && $lot_area_sq_ft_max!='') {
      $conditions[]= '(lot_area_sqft <='.$lot_area_sq_ft_max.')';
    } elseif (!($lot_area_sq_ft_min =='' && $lot_area_sq_ft_max =='')) {
      $conditions[]=  '(lot_area_sqft >='.$lot_area_sq_ft_min.' and lot_area_sqft <='.$lot_area_sq_ft_max.')';
    }

    if ($sales_price_min !='' && $sales_price_max =='') {
      $conditions[]= '(sales_price >='.$sales_price_min.')' ;
    } elseif ($sales_price_min =='' && $sales_price_max!='') {
      $conditions[]= '(sales_price <='.$sales_price_max.')';
    } elseif (!($sales_price_min =='' && $sales_price_max =='')) {
      $conditions[]=  '(sales_price >='.$sales_price_min.' and sales_price <='.$sales_price_max.')';
    }


    if ($year_built_min !='' && $year_built_max =='') {
      $conditions[]= '(year_built >="'.$year_built_min.'")' ;
    } elseif ($year_built_min =='' && $year_built_max!='') {
      $conditions[]= '(year_built <="'.$year_built_max.'")';
    } elseif (!($year_built_min =='' && $year_built_max =='')) {
      $conditions[]=  '(year_built >="'.$year_built_min.'" and year_built <="'.$year_built_max.'")';
    }

    if ($sales_date_min !='' && $sales_date_max =='') {
      $sdatefirst=date('Y-m-d', strtotime($sales_date_min));
      $conditions[]= '(sales_date >="'.$sdatefirst.'")' ;
    } elseif ($sales_date_min =='' && $sales_date_max!='') {
      $sdatesecond=date('Y-m-d', strtotime($sales_date_max));
      $conditions[]= '(sales_date <="'.$sdatesecond.'")';
    } elseif (!($sales_date_min =='' && $sales_date_max =='')) {
      $sdatefirst=date('Y-m-d', strtotime($sales_date_min));
      $sdatesecond=date('Y-m-d', strtotime($sales_date_max));
      $conditions[]=  '(sales_date >="'.$sdatefirst.'" and sales_date <="'.$sdatesecond.'")';
    }

    return $conditions;
  }

}
