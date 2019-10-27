<?php
require_once('SortSettings.php');

class SearchParameters {

  private $raw_search_parameters;
  private $raw_case_parameters;

  private $case_type_filters;

  function __construct($raw_search_parameters, $raw_case_parameters)
  {
    $this->raw_search_parameters = $raw_search_parameters;
    $this->raw_case_parameters = $raw_case_parameters;
  }

  public function getSearchParamData() {
    return $this->raw_search_parameters;
  }

  private function getPage() {
    return isset($this->raw_search_parameters['page']) ? (int) $this->raw_search_parameters['page'] : 1;
  }

  private function getOffset() {
    $page = $this->getPage();

    return ($page - 1) * $this->getPageSize();
  }

  private function getPageSize() {
    return isset($this->raw_search_parameters['page_size']) ? $this->raw_search_parameters['page_size'] :  10;
  }

  public function getZips()
  {
    if (is_array($this->raw_search_parameters['zip_codes'])) {
      $zips = array_filter($this->raw_search_parameters['zip_codes']);
    } else {
      $zips = array();
    }

    return $zips;
  }

  public function getCities()
  {
    if (is_array($this->raw_search_parameters['cities'])) {
        $cities = array_filter($this->raw_search_parameters['cities']);
    } else {
        $cities = array();
    }
    return $cities;
  }

  public function getZoning()
  {
    if (is_array($this->raw_search_parameters['zoning'])) {
        $zoning = array_filter($this->raw_search_parameters['zoning']);
    } else {
        $zoning = array();
    }

    return $zoning;
  }

  public function getExemption()
  {
    if (is_array($this->raw_search_parameters['exemption'])) {
        $exemption = array_filter($this->raw_search_parameters['exemption']);
    } else {
        $exemption = array();
    }

    return $exemption;
  }

  public function getNumUnitsMin()
  {
    return $this->raw_search_parameters['num_units_min'];
  }

  public function getNumUnitsMax()
  {
    return $this->raw_search_parameters['num_units_max'];
  }

  public function getNumBedsMin()
  {
    return $this->raw_search_parameters['num_baths_min'];
  }

  public function getNumBedsMax()
  {
    return $this->raw_search_parameters['num_baths_max'];
  }

  public function getNumBathsMin()
  {
    return $this->raw_search_parameters['num_baths_min'];
  }

  public function getNumBathsMax()
  {
    return $this->raw_search_parameters['num_baths_max'];
  }

  public function getNumStoriesMin()
  {
    return $this->raw_search_parameters['num_stories_min'];
  }

  public function getNumStoriesMax()
  {
    return $this->raw_search_parameters['num_stories_max'];
  }

  public function getCostPerSqFeetMin()
  {
    return $this->raw_search_parameters['cost_per_sq_ft_min'];
  }

  public function getCostPerSqFeetMax()
  {
    return $this->raw_search_parameters['cost_per_sq_ft_max'];
  }

  public function getLotAreaSqFeetMin()
  {
    return $this->raw_search_parameters['lot_area_sq_ft_min'];
  }

  public function getLotAreaSqFeetMax()
  {
    return $this->raw_search_parameters['lot_area_sq_ft_max'];
  }

  public function getSalesPriceMin()
  {
    return $this->raw_search_parameters['sales_price_min'];
  }

  public function getSalesPriceMax()
  {
    return $this->raw_search_parameters['sales_price_max'];
  }

  public function isOwnerOccupied()
  {
    return $this->raw_search_parameters['is_owner_occupied'];
  }

  public function isSingleFamilyOnly()
  {
    return $this->raw_search_parameters['sfmlytype'];
  }

  public function getYearBuildMin()
  {
    return $this->raw_search_parameters['year_built_min'];
  }

  public function getYearBuildMax()
  {
    return $this->raw_search_parameters['year_built_max'];
  }

  public function getSalesDateMin()
  {
    return $this->raw_search_parameters['sales_date_from'];
  }

  public function getSalesDateMax()
  {
    return $this->raw_search_parameters['sales_date_to'];
  }

  public function getCaseTypeFilters()
  {
    if (isset($this->case_type_filters)) {
      return $this->case_type_filters;
    }

    if (!isset($this->raw_case_parameters) ) {
      $case_type = array();
    } else if (is_array($this->raw_case_parameters)) {
      $case_type = array_filter($this->raw_case_parameters);
    }

    $this->case_type_filters = new CaseTypeFilters($case_type);

    return $this->case_type_filters;
  }

  public function isPropertiesWithOpenCasesExclusively() {
    return $this->raw_search_parameters['is_open_cases_exclusively'] == "on";
  }

  public function isFilteringOnNotes() {
    return isset($this->raw_search_parameters['filter_on_notes']) &&
           $this->raw_search_parameters['filter_on_notes'] == "on";
  }

  public function getNotesContentToMatch() {
    return $this->raw_search_parameters['notes_content_to_match'];
  }

  public function getSortByClause() {
    if (!isset($this->raw_search_parameters['sortSettings'])) {
      return "";
    }

    parse_str($this->raw_search_parameters['sortSettings'], $raw_sort_settings);
    $sort_settings = new SortSettings($raw_sort_settings);

    return $sort_settings->getSortByClause();
  }

  public function getLimitOffsetClause() {
    return sprintf(
      "LIMIT %s, %s",
      $this->getOffset(),
      $this->getPageSize()
    );
  }

  public function getCasePCIDsToSearch() {
    if (isset($this->raw_search_parameters['case_pcids_to_search'])) {
      return explode(",", $this->raw_search_parameters['case_pcids_to_search']);
    } else {
      return null;
    }
  }

}
