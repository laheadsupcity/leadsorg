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
      $this->setInclusionFilters($case_type_filter_data['include']);
      $this->setExclusionFilters($case_type_filter_data['exclude']);
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

  private function getExclusionCondition($parcel_number_field = "p.parcel_number") {
    if (empty($this->exclusion_filters)) {
      return null;
    }

    return sprintf(
      "(c.case_id NOT in (
          SELECT case_id
          FROM property_cases
          GROUP BY case_id
          HAVING SUM(CASE WHEN case_date <> \"\" THEN 1 ELSE 0 END) = 0 AND
                 SUM(CASE WHEN case_type_id IN (\"%s\") THEN 1 ELSE 0 END) > 0
        )
      )",
      $this->getExcludedIDsExpression()
    );
  }

  private function getInclusionNoConstraintCondition($parcel_number_field = "p.parcel_number") {
    $filters_with_no_constraints = array_filter(
      $this->inclusion_filters,
      create_function('$filter', 'return !$filter->hasConstraints();')
    );

    if (empty($filters_with_no_constraints)) {
      return null;
    }

    $case_types_to_include = array_map(
      create_function('$filter', 'return $filter->getCaseTypeID();'),
      $filters_with_no_constraints
    );

    $expr = implode('","', $case_types_to_include);

    return sprintf(
      "(c.case_id in (
          SELECT case_id
          FROM property_cases
          GROUP BY case_id
          HAVING SUM(CASE WHEN case_date <> \"\" THEN 1 ELSE 0 END) = 0 AND
                 SUM(CASE WHEN case_type_id IN (\"%s\") THEN 1 ELSE 0 END) > 0
        )
      )",
      $expr
    );
  }

  public function getFiltersWithConstraints() {
    return array_filter(
      $this->inclusion_filters,
      create_function('$filter', 'return $filter->hasConstraints();')
    );
  }

  private function getFiltersWithDateConstraintsOnly() {
    return array_filter(
      $this->inclusion_filters,
      create_function('$filter', 'return $filter->hasDateConstraintsOnly();')
    );
  }

  private function getInclusionCondition() {
    if (empty($this->inclusion_filters)) {
      return null;
    }

    $conditions = [];

    $inclusion_no_constraints = $this->getInclusionNoConstraintCondition();
    if (isset($inclusion_no_constraints)) {
      $conditions[] = $inclusion_no_constraints;
    }

    foreach ($this->getFiltersWithConstraints() as $inclusion_filter) {
      $expression = $inclusion_filter->getConditionExpression();
      if (isset($expression)) {
        $conditions[] = $expression;
      }
    }

    if (empty($conditions)) {
      return null;
    } else {
      return sprintf(
        "%s",
        implode(' OR ', $conditions)
      );
    }
  }

  public function getCaseTypesCondition() {
    $exclusion_condition = $this->getExclusionCondition();
    $inclusion_condition = $this->getInclusionCondition();

    if (!isset($exclusion_condition) && !isset($inclusion_condition)) {
      return null;
    } else if (!isset($exclusion_condition)) {
      return sprintf(
        "
        %s
        ",
        $inclusion_condition
      );
    } else if (!isset($inclusion_condition)) {
      return sprintf(
        "(
          %s
          )",
          $exclusion_condition
      );
    } else {
      return sprintf(
        "%s",
        implode(" AND ", [$exclusion_condition, $inclusion_condition])
      );
    }
  }

}
