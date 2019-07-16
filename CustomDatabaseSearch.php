<?php
require_once('config.php');
include('CaseTypeFilters.php');
include('SearchParameters.php');

class CustomDatabaseSearch {

  private $search_params;
  private $user_id;
  private $results_count;
  private $apns_to_cases_map;
  private $result_apns;
  private $case_type_filter_builder;

  function __construct($user_id, $search_param_data)
  {
    $this->db = Database::instance();
    $this->user_id = $user_id;
    $this->search_params = new SearchParameters($search_param_data);

    $case_type_filters = $this->search_params->getCaseTypeFilters();

    $this->case_type_filter_builder = new CaseTypeFilters($case_type_filters);
  }

  private function getStatusInclusionClauses()
  {
    $inclusion_filters = $this->case_type_filter_builder->getInclusionFilters();

    if (empty($inclusion_filters)) {
      return "";
    }

    $clauses = [];
    foreach ($inclusion_filters as $filter) {
      $condition_expr = $filter->getConditionExpression();
      if (!empty($condition_expr)) {
        $clauses[] = $condition_expr;
      }
    }

    return implode(" OR ", $clauses);
  }

  private function getExclusionSubquery()
  {
    $expr = $this->case_type_filter_builder->getExcludedIDsExpression();

    if (empty($expr)) {
      return "";
    }

    $clauses = [];

    if ($this->search_params->isPropertiesWithOpenCasesExclusively()) {
      $clauses[] = "COUNT(IF(`staus`='All Violations Resolved Date', 1, NULL)) = 0";
    }

    $having_clauses = "HAVING " . implode($clauses, " AND ");

    $query = sprintf(
      "
        LEFT JOIN (
          SELECT
            `APN`
          FROM
            `property_inspection`
          WHERE
            `case_type_id` IN ('%s')
          GROUP BY
            `APN`,
            `property_case_detail_id`
          %s
        ) AS `open_excluded_cases`
        ON `p`.`parcel_number` = `open_excluded_cases`.`APN`
      ",
      $expr,
      empty($clauses) ? "" : $having_clauses
    );

    return $query;
  }

  private function getHavingClauseForMatchingCases() {
    $clauses = [];

    $inclusion_clauses = $this->getStatusInclusionClauses();

    if (!empty($inclusion_clauses)) {
      $clauses[] = $inclusion_clauses;
    }

    if ($this->search_params->isPropertiesWithOpenCasesExclusively()) {
      $clauses[] = "COUNT(IF(staus='All Violations Resolved Date', 1, NULL)) = 0";
    }

    if (empty($clauses)) {
      return "";
    } else {
      return sprintf(
        "
          HAVING
            %s
        ",
        implode($clauses, " AND ")
      );
    }
  }

  private function filterPropertiesWithMatchingCases($apns_with_matching_notes) {
    if (isset($apns_with_matching_notes) && count($apns_with_matching_notes) == 0) {
      // notes filter did not match any properties
      // shortcut and return empty results
      return [];
    }

    $included_case_types_expr = $this->case_type_filter_builder->getIncludedIDsExpression();

    $conditions = $this->getConditions();

    if (isset($apns_with_matching_notes)) {
      $conditions[] = sprintf(
        "`p`.`parcel_number` IN ('%s')",
        implode($apns_with_matching_notes, "','")
      );
    }

    if ($this->case_type_filter_builder->hasExclusionFilters()) {
      $conditions[] = "open_excluded_cases.APN IS NULL";
    }

    $case_closed_date_clause = $this->getCaseClosedDateClause();
    if(isset($case_closed_date_clause)) {
      $conditions[] = $case_closed_date_clause;
    }

    $case_open_date_clause = $this->getCaseOpenedDateClause();
    if(isset($case_open_date_clause)) {
      $conditions[] = $case_open_date_clause;
    }

    if (!empty($conditions)) {
      $where = implode(' AND ', $conditions);
    }

    $query = sprintf(
      "SELECT
        `p`.`parcel_number`,
        `cases`.`pcid`,
        `cases`.`case_type`,
        `cases`.`case_id`
      FROM (
        SELECT
          `APN`,
          `property_case_detail_id`
        FROM
          `property_inspection` AS `pi`
          %s
        GROUP BY
          `APN`,
          `property_case_detail_id`
          %s
      ) as `matching_cases`
      JOIN `property` AS `p` ON (
        `p`.`parcel_number` = `matching_cases`.`APN`
      )
      JOIN `property_cases_detail` AS `pcd` ON (
        `pcd`.`id` = `matching_cases`.`property_case_detail_id` AND
        `pcd`.`apn` = `matching_cases`.`APN`
        %s
      )
      JOIN `property_cases` AS `cases` ON (
        `cases`.`pcid` = `pcd`.`property_case_id` AND
        `cases`.`APN` = `pcd`.`apn`
        %s
      )
      %s
      %s
      ORDER BY `p`.`parcel_number`;",
      isset($included_case_types_expr) ? sprintf(" WHERE pi.case_type_id IN ('%s')", $included_case_types_expr) : "",
      $this->getHavingClauseForMatchingCases(),
      isset($included_case_types_expr) ? sprintf("AND `pcd`.`case_type_id` IN ('%s')", $included_case_types_expr) : "",
      isset($included_case_types_expr) ? sprintf("AND `cases`.`case_type_id` IN ('%s')", $included_case_types_expr) : "",
      $this->getExclusionSubquery(),
      isset($where) ? " WHERE $where" : ""
    );

    $this->db->query($query);

    return $this->db->result_array();
  }

  public function getResults($limit = 100, $page = 1)
  {
    $limit = (int) $limit;
    $offset = ((int) $page - 1) * $limit;

    $apns_with_notes = null;
    if ($this->search_params->isFilteringOnNotes()) {
      $apns_with_notes = $this->filterPropertiesWithMatchingNotes();
    }

    $cases_results = $this->filterPropertiesWithMatchingCases($apns_with_notes);

    $matching_apns = array_unique(array_map(
      function($result) {
        return $result['parcel_number'];
      },
      $cases_results
    ));

    $apns_to_search = array_slice($matching_apns, $offset, $limit);

    $property_query = sprintf(
      "SELECT
      p.parcel_number,
      p.street_number,
      p.street_name,
      p.site_address_city_state,
      p.site_address_zip,
      p.owner_name2,
      p.full_mail_address,
      p.mail_address_zip,
      p.number_of_units,
      p.number_of_stories,
      p.bedrooms,
      p.bathrooms,
      p.lot_area_sqft,
      p.building_area,
      p.cost_per_sq_ft,
      p.year_built,
      p.sales_date,
      p.sales_price,
      p.phone1,
      p.phone2,
      p.email1,
      p.email2,
      p.owner_address_and_zip,
      p.id
      FROM `property` AS p WHERE p.parcel_number IN (
        \"%s\"
      );",
      implode('","', $apns_to_search)
    );

    $this->db->query($property_query);

    $results = $this->db->result_array();

    $this->result_apns = $apns_to_search;
    $this->results_count = count($matching_apns);
    $this->matching_cases_data = $cases_results;

    return $results;
  }

  public function filterPropertiesWithMatchingNotes() {
    if (!$this->search_params->isFilteringOnNotes()) {
      return null;
    }

    $notes_content_to_match_clause = "";
    if (!empty($this->search_params->getNotesContentToMatch())) {
      $notes_content_to_match_clause = sprintf(
        "
          AND
          `content` LIKE '%%%s%%'
        ",
        $this->search_params->getNotesContentToMatch()
      );
    }

    $query = sprintf(
      "
        SELECT
          DISTINCT `parcel_number`
        FROM
          `property_notes`
        WHERE
          `content` <> '' AND
          (
            `is_private` = 0 OR
            `user_id` = %s
          )
          %s
      ",
      $this->user_id,
      $notes_content_to_match_clause
    );

    $this->db->query($query);

    $results = $this->db->result_array();

    return array_map(
      function($result) {
        return $result['parcel_number'];
      },
      $results
    );
  }

  public function getRelatedPropertiesCounts() {
    if (empty($this->result_apns)) {
      return [];
    }

    $addresses_query = sprintf(
      "
      SELECT
        count(`owner_address_and_zip`) - 1 AS `related_properties_count`,
        `owner_address_and_zip`
      FROM `property`
      WHERE
        `full_mail_address` <> \"\" AND
        `owner_address_and_zip` IN (
          SELECT
            `owner_address_and_zip`
          FROM `property`
          WHERE parcel_number IN (
            %s
          )
        )
      GROUP BY owner_address_and_zip
      HAVING `related_properties_count` > 0;
      ",
      implode(',', $this->result_apns)
    );

    $this->db->query($addresses_query);

    $results = $this->db->result_array();

    $related_count_map = [];
    foreach ($results as $result) {
      $address =  $result['owner_address_and_zip'];
      $related_properties_count =  $result['related_properties_count'];

      $related_count_map[$address] = $related_properties_count;
    }

    return $related_count_map;
  }

  public function getResultCount() {
    return $this->results_count;
  }

  public function getMatchingCasesForProperties()
  {
    return $this->matching_cases_data;
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

  private function getCaseClosedDateClause() {
    $case_closed_date_filters = $this->case_type_filter_builder->getCaseClosedDateFilters();

    if (empty($case_closed_date_filters)) {
      return null;
    }

    $clauses = array();
    foreach ($case_closed_date_filters as $inclusion_filter) {
      $filter = $inclusion_filter->getCaseClosedDateFilter();
      $clause = null;
      if ($filter->isExclude()) {
        $clause = "cases.case_date = \"\"";
      } else if (!$filter->hasFromDate() && !$filter->hasToDate()) {
        $clause = "cases.case_date <> \"\"";
      } else {
        $from_date_expr = $filter->getFromDateAsExpression();
        $to_date_expr = $filter->getToDateAsExpression();

        if ($filter->hasFromDate() && $filter->hasToDate()) {
          $clause = sprintf(
            "STR_TO_DATE(cases.case_date, '%s') >= %s AND STR_TO_DATE(cases.case_date, '%s') <= %s",
            '%m/%d/%Y',
            $from_date_expr,
            '%m/%d/%Y',
            $to_date_expr
          );
        } else if ($filter->hasFromDate()) {
          $clause = sprintf(
            "STR_TO_DATE(cases.case_date, '%s') >= %s",
            '%m/%d/%Y',
            $from_date_expr
          );
        } else if ($filter->hasToDate()) {
          $clause = sprintf(
            "STR_TO_DATE(cases.case_date, '%s') <= %s",
            '%m/%d/%Y',
            $to_date_expr
          );
        }
      }

      $clause = sprintf(
        "WHEN cases.case_type_id = %s THEN %s",
        $inclusion_filter->getCaseTypeID(),
        $clause
      );

      $clauses[] = $clause;
    }

    return sprintf(
      "(
          CASE
            %s
            ELSE 1=1
          END

      )",
      implode(' ', $clauses)
    );
  }

  private function getCaseOpenedDateClause() {
    $case_open_date_filters = $this->case_type_filter_builder->getCaseOpenedDateFilters();

    if (empty($case_open_date_filters)) {
      return null;
    }

    $clauses = array();
    foreach ($case_open_date_filters as $inclusion_filter) {
      $filter = $inclusion_filter->getCaseOpenedDateFilter();
      $clause = null;
      if ($filter->isExclude()) {
        $clause = "cases.created_date IS NULL \"\"";
      } else if (!$filter->hasFromDate() && !$filter->hasToDate()) {
        $clause = "cases.created_date IS NOT NULL";
      } else {
        $from_date_expr = $filter->getFromDateAsDateTimeExpression();
        $to_date_expr = $filter->getToDateAsDateTimeExpression();

        if ($filter->hasFromDate() && $filter->hasToDate()) {
          $clause = sprintf(
            "cases.created_date >= '%s' AND cases.created_date <= '%s'",
            $from_date_expr,
            $to_date_expr
          );
        } else if ($filter->hasFromDate()) {
          $clause = sprintf(
            "cases.created_date >= '%s'",
            $from_date_expr
          );
        } else if ($filter->hasToDate()) {
          $clause = sprintf(
            "cases.created_date <= '%s'",
            $to_date_expr
          );
        }
      }

      $clause = sprintf(
        "WHEN cases.case_type_id = %s THEN %s",
        $inclusion_filter->getCaseTypeID(),
        $clause
      );

      $clauses[] = $clause;
    }

    return sprintf(
      "(
          CASE
            %s
            ELSE 1=1
          END

      )",
      implode(' ', $clauses)
    );
  }

}
