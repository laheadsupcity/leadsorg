<?php
require_once('config.php');
include('CaseTypeFilters.php');
include('SearchParameters.php');

class CustomDatabaseSearch {

  private $search_params;
  private $results_count;
  private $case_type_filter_builder;

  function __construct($search_param_data) {
    $this->db = Database::instance();
    $this->search_params = new SearchParameters($search_param_data);

    $case_type_filters = $this->search_params->getCaseTypeFilters();
    $this->case_type_filter_builder = new CaseTypeFilters($case_type_filters);
  }

  private function getStatusInclusionClauses() {

    $inclusion_filters = $this->case_type_filter_builder->getInclusionFilters();
    if (empty($inclusion_filters)) {
      return "";
    }

    $clauses = [];
    foreach ($inclusion_filters as $filter) {
      $clauses[] = $filter->getConditionExpression();
    }

    $expression = implode(" OR ", $clauses);

    if (empty($expression)) {
      return "";
    } else {
      return sprintf(
        "%s AND",
        $expression
      );
    }
  }

  private function getExclusionSubquery() {
    $expr = $this->case_type_filter_builder->getExcludedIDsExpression();

    if (empty($expr)) {
      return null;
    }

    $query = sprintf(
      'LEFT JOIN (
        SELECT APN FROM property_inspection
        WHERE case_type_id IN ("%s")
        GROUP BY APN, property_case_detail_id
        HAVING COUNT(IF(staus="All Violations Resolved Date", 1, NULL)) = 0
      ) AS open_excluded_cases
      ON p.parcel_number = open_excluded_cases.APN',
      $expr
    );

    return $query;
  }

  public function getResults($limit = 100, $page = 1)
  {
      $conditions = $this->getConditions();

      $included_case_types_expr = $this->case_type_filter_builder->getIncludedIDsExpression();
      $inclusion_clauses = $this->getStatusInclusionClauses();


      $exclusion_subquery = $this->getExclusionSubquery();

      if (isset($exclusion_subquery)) {
        $conditions[] = "open_excluded_cases.APN IS NULL";
      }

      if (!empty($conditions)) {
        $where = implode(' AND ', $conditions);
      }

      $limit = sprintf(
        "LIMIT %s, %s",
        $limit * ($page - 1),
        $limit
      );

      $query = sprintf(
        "SELECT
          -- SQL_CALC_FOUND_ROWS
          DISTINCT p.parcel_number,
          p.street_number,
          p.street_name,
          p.site_address_city_state,
          p.owner_name2,
          p.number_of_units,
          p.number_of_stories,
          p.bedrooms,
          p.bathrooms,
          p.lot_area_sqft,
          p.cost_per_sq_ft,
          p.year_built,
          p.sales_date,
          p.sales_price,
          p.id
        FROM (
          SELECT APN, property_case_detail_id FROM property_inspection AS pi
          %s
          GROUP BY APN, property_case_detail_id
          HAVING %s
          COUNT(IF(staus='All Violations Resolved Date', 1, NULL)) = 0
        ) as open_cases
        JOIN property AS p
        ON p.parcel_number = open_cases.APN
        %s
        %s
        %s;",
        isset($included_case_types_expr) ? sprintf(" WHERE pi.case_type_id IN ('%s')", $included_case_types_expr) : "",
        !empty($inclusion_clauses) ? $inclusion_clauses : "",
        isset($exclusion_subquery) ? $exclusion_subquery : "",
        isset($where) ? " WHERE $where" : "",
        $limit
      );

      //Debug::dumpR($query);

      $this->db->query($query);

      $results = $this->db->result_array();

      // $this->db->query("SELECT FOUND_ROWS();");
      //
      $this->results_count = count($results);// $this->db->result_array()[0]["FOUND_ROWS()"];

      return $results;
  }

  public function getResultCount() {
    return $this->results_count;
  }

  public function getConditions() {
    $search_param_data = $this->search_params->getSearchParamData();

    $conditions = array();

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
