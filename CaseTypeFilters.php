<?php
require_once('config.php');
include('InclusionFilter.php');
include('ExclusionFilter.php');

class CaseTypeFilters {

  private $inclusion_filters = array();
  private $exclusion_filters = array();

  function __construct($case_type_filter_data) {
    $this->db = Database::instance();
    if (!empty($case_type_filter_data)) {
      if (isset($case_type_filter_data['include'])) {
        $this->setInclusionFilters($case_type_filter_data['include']);
      }
      if (isset($case_type_filter_data['exclude'])) {
        $this->setExclusionFilters($case_type_filter_data['exclude']);
      }
    }
  }

  private function setInclusionFilters($data_array) {
    if (isset($data_array)) {
      foreach ($data_array as $data) {
        $this->inclusion_filters[] = new InclusionFilter($data);
      }
    }
  }

  private function setExclusionFilters($data_array) {
    if (isset($data_array)) {
      foreach ($data_array as $data) {
        $this->exclusion_filters[] = new ExclusionFilter($data);
      }
    }
  }

  public function getInclusionFilters() {
    return $this->inclusion_filters;
  }

  public function getExclusionFilters() {
    return $this->exclusion_filters;
  }

  public function getIncludedIDsExpression() {
    if (empty($this->inclusion_filters)) {
      return null;
    }

    $ids = array_map(
      create_function('$filter', 'return $filter->getCaseTypeID();'),
      $this->inclusion_filters
    );

    $expr = implode("','", $ids);

    return $expr;
  }

  public function getExcludedIDsExpression() {
    if (empty($this->exclusion_filters)) {
      return null;
    }

    $ids = array_map(
      create_function('$filter', 'return $filter->getCaseTypeID();'),
      $this->exclusion_filters
    );

    $expr = implode('","', $ids);

    return $expr;
  }

  public function getCaseClosedDateFilters() {
    if (empty($this->getInclusionFilters())) {
      return array();
    }

    $filters = [];
    foreach ($this->getInclusionFilters() as $inclusion_filter) {
      $case_closed_date_filter = $inclusion_filter->getCaseClosedDateFilter();

      if (!empty($case_closed_date_filter)) {
        $filters[] = $inclusion_filter;
      }
    }

    return $filters;
  }

}
