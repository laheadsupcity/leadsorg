<?php
require_once('config.php');
include('CaseTypeStatusFilter.php');

class InclusionFilter {

  private $db;
  private $case_type_id;
  private $from_date;
  private $to_date;
  private $status_filters;

  function __construct($data_array) {
    $this->db = Database::instance();
    $this->case_type_id = $data_array['case_type_id'];
    $this->from_date = $data_array['from_date'];
    $this->to_date = $data_array['to_date'];
    $this->status_filters = $this->getStatusFilters(
      isset($data_array['status_filters']) ? $data_array['status_filters'] : null
    );
  }

  public function getCaseTypeID() {
    return $this->case_type_id;
  }

  private function getFromDateAsExpression() {
    return sprintf(
      "STR_TO_DATE(\"%s\", '%s')",
      $this->from_date,
      "%m/%d/%Y"
    );
  }

  private function getToDateAsExpression() {
    return sprintf(
      "STR_TO_DATE(\"%s\", '%s')",
      $this->to_date,
      "%m/%d/%Y"
    );
  }

  private function hasStatusFilters() {
    return !empty(
      array_filter(
        $this->status_filters,
        function($filter) {
          return
            !$filter->isCaseClosedDateFilter() &&
            !$filter->isCaseOpenedDateFilter();
        }
      )
    );
  }

  private function getStatusFilters($data_array) {
    if (!isset($data_array)) {
      return [];
    }

    $includes = isset($data_array['include']) ? $data_array['include'] : [];
    $excludes = isset($data_array['exclude']) ? $data_array['exclude'] : [];

    $status_filters = [];
    foreach ($includes as $data) {
      $status_filters[] = new CaseTypeStatusFilter($data, true);
    }
    foreach ($excludes as $data) {
      $status_filters[] = new CaseTypeStatusFilter($data, false);
    }

    return $status_filters;
  }

  private function hasFromDate() {
    return !empty($this->from_date);
  }

  private function hasToDate() {
    return !empty($this->to_date);
  }

  public function hasConstraints() {
    return $this->hasFromDate() ||
           $this->hasToDate() ||
           !empty($this->status_filters);
  }

  private function getStatusExclusions() {
    return array_filter(
      $this->status_filters,
      function($filter) {
        return
          $filter->isExclude() &&
          !$filter->isCaseClosedDateFilter() &&
          !$filter->isCaseOpenedDateFilter();;
      }
    );
  }

  private function getStatusInclusions() {
    return array_filter(
      $this->status_filters,
      function($filter) {
        return
          !$filter->isExclude() &&
          !$filter->isCaseClosedDateFilter() &&
          !$filter->isCaseOpenedDateFilter();
      }
    );
  }

  public function getCaseClosedDateFilter() {
    // There is only one case closed date status filter
    // Grab the first and only one
    foreach ($this->status_filters as $filter) {
      if ($filter->isCaseClosedDateFilter()) {
        return $filter;
      }
    }
  }

  public function getCaseOpenedDateFilter() {
    // There is only one case opened date status filter
    // Grab the first and only one
    foreach ($this->status_filters as $filter) {
      if ($filter->isCaseOpenedDateFilter()) {
        return $filter;
      }
    }
  }

  private function getCaseTypeDateRangeClause() {
    $date_clause = null;
    if ($this->hasFromDate() && $this->hasToDate()) {
      $date_clause = sprintf(
        "COUNT(IF(pi.case_type_id = %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s, 1, NULL)) > 0",
        $this->getCaseTypeID(),
        $this->getFromDateAsExpression(),
        $this->getToDateAsExpression()
      );
    } else if ($this->hasFromDate()) {
      $date_clause = sprintf(
        "COUNT(IF(pi.case_type_id = %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s, 1, NULL)) > 0",
        $this->getCaseTypeID(),
        $this->getFromDateAsExpression()
      );
    } else if ($this->hasToDate()) {
      $date_clause = sprintf(
        "COUNT(IF(pi.case_type_id = %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s, 1, NULL)) > 0",
        $this->getCaseTypeID(),
        $this->getToDateAsExpression()
      );
    }

    return $date_clause;
  }

  public function getConditionExpression() {
    $inclusion_clauses = [];
    $exclusion_clauses = [];

    if (($this->hasFromDate() || $this->hasToDate()) && !$this->hasStatusFilters()) {
      return $this->getCaseTypeDateRangeClause();
    }

    $case_closed_date_filter = $this->getCaseClosedDateFilter();
    if (isset($case_closed_date_filter) && count($this->status_filters) == 1) {
      $inclusion_clauses[] = sprintf(
        "COUNT(IF(case_type_id=%s, 1, NULL)) > 0",
        $this->getCaseTypeID()
      );
    }

    $status_exclusions = $this->getStatusExclusions();
    if (!empty($status_exclusions)) {
      $expr = implode(',', array_map(
        create_function('$filter', 'return sprintf("\"%s\"", $filter->getStatus());'),
        $status_exclusions
      ));

      // if status filters are all exclusions then add a date range clause here
      if (count($status_exclusions) == count($this->status_filters)) {
        if ($this->hasFromDate() && $this->hasToDate()) {
          $date_clause = sprintf(
            "COUNT(
              IF(
                case_type_id=%s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s, 1, NULL
              )) > 0",
            $this->getCaseTypeID(),
            $this->getFromDateAsExpression(),
            $this->getToDateAsExpression()
          );
        } else if ($this->hasFromDate()) {
          $date_clause = sprintf(
            "COUNT(IF(case_type_id=%s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s, 1, NULL)) > 0",
            $this->getCaseTypeID(),
            $this->getFromDateAsExpression()
          );
        } else if ($this->hasToDate()) {
          $date_clause = sprintf(
            "COUNT(IF(case_type_id=%s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s, 1, NULL)) > 0",
            $this->getCaseTypeID(),
            $this->getToDateAsExpression()
          );
        }
      }

      if (isset($date_clause)) {
        $exclusion_clauses[] = $date_clause;
      }

      $exclusion_clauses[] = sprintf(
        "COUNT(IF(case_type_id=%s AND pi.staus IN (%s), 1, NULL)) = 0",
        $this->getCaseTypeID(),
        $expr
      );
    }

    foreach ($this->getStatusInclusions() as $status_filter) {
      if (!$status_filter->hasFromDate() && !$status_filter->hasToDate()) {
        $having_clause = sprintf(
          "COUNT(IF(case_type_id=%s AND pi.staus = \"%s\", 1, NULL)) > 0",
          $this->getCaseTypeID(),
          $status_filter->getStatus()
        );
      } else if ($status_filter->hasFromDate() && $status_filter->hasToDate()) {
        $date_clause = sprintf(
          "STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s",
          $status_filter->getFromDateAsExpression(),
          $status_filter->getToDateAsExpression()
        );
        $having_clause = sprintf(
          "COUNT(IF(case_type_id=%s AND pi.staus = \"%s\" AND %s, 1, NULL)) > 0",
          $this->getCaseTypeID(),
          $status_filter->getStatus(),
          $date_clause
        );
      } else if ($status_filter->hasFromDate() && !$status_filter->hasToDate()) {
        $date_clause = sprintf(
          "STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s",
          $status_filter->getFromDateAsExpression()
        );
        $having_clause = sprintf(
          "COUNT(IF(case_type_id=%s AND pi.staus = \"%s\" AND %s, 1, NULL)) > 0",
          $this->getCaseTypeID(),
          $status_filter->getStatus(),
          $date_clause
        );
      } else if (!$status_filter->hasFromDate() && $status_filter->hasToDate()) {
        $date_clause = sprintf(
          "STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s",
          $status_filter->getToDateAsExpression()
        );
        $having_clause = sprintf(
          "COUNT(IF(case_type_id=%s AND pi.staus = \"%s\" AND %s, 1, NULL)) > 0",
          $this->getCaseTypeID(),
          $status_filter->getStatus(),
          $date_clause
        );
      }

      $inclusion_clauses[] = $having_clause;
    };

    if (!empty($exclusion_clauses)) {
      $exclusion_clause = implode(
        "
        AND
        ", $exclusion_clauses
      );
    }

    if (isset($exclusion_clause) && empty($inclusion_clauses)) {
      return sprintf(
        '(%s)',
        $exclusion_clause
      );
    } else if (isset($exclusion_clause) && !empty($inclusion_clauses)) {
      return sprintf(
        "%s AND (%s)",
        $exclusion_clause,
        implode(" AND ", $inclusion_clauses)
      );
    } else if (!isset($exclusion_clause) && !empty($inclusion_clauses)) {
      return sprintf(
        "(%s)",
        implode(" AND ", $inclusion_clauses)
      );
    }
  }

}
