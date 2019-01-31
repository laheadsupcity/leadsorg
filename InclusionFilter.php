<?php
include('CaseTypeStatusFilter.php');

class InclusionFilter {

  private $case_type_id;
  private $from_date;
  private $to_date;
  private $status_filters;

  function __construct($data_array) {
    $this->case_type_id = $data_array['case_type_id'];
    $this->from_date = $data_array['from_date'];
    $this->to_date = $data_array['to_date'];
    $this->status_filters = $this->getStatusFilters($data_array['status_filters']);
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

  private function getCurrentDateAsDateObject() {
    return sprintf(
      "STR_TO_DATE(\"%s\", '%s')",
      date('m/d/Y', time()),
      "%m/%d/%Y"
    );
  }

  private function hasStatusFilters() {
    return !empty($this->status_filters);
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

  public function hasDateConstraintsOnly() {
    return ($this->hasFromDate() || $this->hasToDate()) &&
           !$this->hasStatusFilters();
  }

  public function hasConstraints() {
    return $this->hasFromDate() ||
           $this->hasToDate() ||
           !empty($this->status_filters);
  }

  private function getStatusExclusions() {
    return array_filter(
      $this->status_filters,
      create_function('$filter', 'return $filter->isExclude();')
    );
  }

  private function getStatusInclusions() {
    return array_filter(
      $this->status_filters,
      create_function('$filter', 'return !$filter->isExclude();')
    );
  }

  public function getConditionExpression() {
    $inclusion_clauses = [];
    $exclusion_clauses = [];

    if (($this->hasFromDate() || $this->hasToDate()) && !$this->hasStatusFilters()) {
      if ($this->hasFromDate() && $this->hasToDate()) {
        $date_clause = sprintf(
          "SUM(CASE WHEN STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s THEN 1 ELSE 0 END) > 0",
          $this->getFromDateAsExpression(),
          $this->getToDateAsExpression()
        );
      } else if ($this->hasFromDate()) {
        $date_clause = sprintf(
          "SUM(CASE WHEN STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s THEN 1 ELSE 0 END) > 0",
          $this->getFromDateAsExpression(),
          $this->getCurrentDateAsDateObject()
        );
      } else if ($this->hasToDate()) {
        $date_clause = sprintf(
          "SUM(CASE WHEN STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s THEN 1 ELSE 0 END) > 0",
          $this->getToDateAsExpression()
        );
      }

      return sprintf(
        "(
          c.case_id in (
            SELECT case_id
            FROM property_cases AS c
            JOIN property_inspection AS pi
            ON c.case_id = pi.lblCaseNo
            WHERE c.case_type_id = %s
            GROUP BY c.case_id
            HAVING %s
          )
        )",
        $this->getCaseTypeID(),
        $date_clause
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
            "SUM(CASE WHEN STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s THEN 1 ELSE 0 END) > 0",
            $this->getFromDateAsExpression(),
            $this->getToDateAsExpression()
          );
        } else if ($this->hasFromDate()) {
          $date_clause = sprintf(
            "SUM(CASE WHEN STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s THEN 1 ELSE 0 END) > 0",
            $this->getFromDateAsExpression(),
            $this->getCurrentDateAsDateObject()
          );
        } else if ($this->hasToDate()) {
          $date_clause = sprintf(
            "SUM(CASE WHEN STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s THEN 1 ELSE 0 END) > 0",
            $this->getToDateAsExpression()
          );
        }
      }

      if (isset($date_clause)) {
        $exclusion_clauses[] = $date_clause;
      }

      $exclusion_clauses[] = sprintf(
        "SUM(CASE WHEN pi.staus IN (%s) THEN 1 ELSE 0 END) = 0",
        $expr
      );
    }

    foreach ($this->getStatusInclusions() as $status_filter) {
      if (!$status_filter->hasFromDate() && !$status_filter->hasToDate()) {
        $having_clause = sprintf(
          "SUM(CASE WHEN pi.staus = \"%s\" THEN 1 ELSE 0 END) > 0",
          $status_filter->getStatus()
        );
      } else if ($status_filter->hasFromDate() && $status_filter->hasToDate()) {
        $date_clause = sprintf(
          "STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s",
          $status_filter->getFromDateAsExpression(),
          $status_filter->getToDateAsExpression()
        );
        $having_clause = sprintf(
          "SUM(CASE WHEN pi.staus = \"%s\" AND %s THEN 1 ELSE 0 END) > 0",
          $status_filter->getStatus(),
          $date_clause
        );
      } else if ($status_filter->hasFromDate() && !$status_filter->hasToDate()) {
        $date_clause = sprintf(
          "STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") >= %s AND STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s)",
          $status_filter->getFromDateAsExpression(),
          $status_filter->getCurrentDateAsDateObject()
        );
        $having_clause = sprintf(
          "SUM(CASE WHEN pi.staus = \"%s\" AND %s THEN 1 ELSE 0 END) > 0",
          $status_filter->getStatus(),
          $date_clause
        );
      } else if (!$status_filter->hasFromDate() && $status_filter->hasToDate()) {
        $date_clause = sprintf(
          "STR_TO_DATE(pi.date, \"%%m/%%d/%%Y\") <= %s)",
          $status_filter->getToDateAsExpression()
        );
        $having_clause = sprintf(
          "SUM(CASE WHEN pi.staus = \"%s\" AND %s THEN 1 ELSE 0 END) > 0",
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
        "(
          c.case_id in (
            SELECT case_id
            FROM property_cases AS c
            JOIN property_inspection AS pi
            ON c.case_id = pi.lblCaseNo
            WHERE c.case_type_id = %s
            GROUP BY c.case_id
            HAVING %s
          )
        )",
        $this->getCaseTypeID(),
        $exclusion_clause
      );
    } else if (isset($exclusion_clause) && !empty($inclusion_clauses)) {
      return sprintf(
        "(
          c.case_id in (
            SELECT case_id
            FROM property_cases AS c
            JOIN property_inspection AS pi
            ON c.case_id = pi.lblCaseNo
            WHERE c.case_type_id = %s
            GROUP BY c.case_id
            HAVING %s AND %s
          )
        )",
        $this->getCaseTypeID(),
        $exclusion_clause,
        implode(" AND ", $inclusion_clauses)
      );
    } else if (!isset($exclusion_clause) && !empty($inclusion_clauses)) {
        return sprintf(
          "(
            c.case_id in (
              SELECT case_id
              FROM property_cases AS c
              JOIN property_inspection AS pi
              ON c.case_id = pi.lblCaseNo
              WHERE c.case_type_id = %s
              GROUP BY c.case_id
              HAVING %s
            )
          )",
          $this->getCaseTypeID(),
          implode(" AND ", $inclusion_clauses)
        );
    }

    // //
    // // HAVING SUM(CASE WHEN STR_TO_DATE(pi.date, "%m/%d/%Y") >= STR_TO_DATE("8/1/2018", "%m/%d/%Y") AND STR_TO_DATE(pi.date, "%m/%d/%Y") <= STR_TO_DATE("8/31/2018", "%m/%d/%Y") THEN 1 ELSE 0 END) > 0 AND
    // //        SUM(CASE WHEN c.case_date <> "" THEN 1 ELSE 0 END) = 0
    //
    //
    //
    // $status_inclusions = [];
    // foreach ($this->getStatusInclusions() as $filter) {
    //   $status_inclusions[] = $filter->getCondition();
    // }
    //
    // if (!empty($status_inclusions)) {
    //   $status_conditions[] = sprintf("(%s)", implode(" OR ", $status_inclusions));
    // }
    //
    // if (count($status_conditions) == 2) {
    //   $status_conditions_expression = implode(" AND ", $status_conditions);
    // } else {
    //   $status_conditions_expression = $status_conditions[0];
    // }
    //
    // if ($this->hasDateConstraintsOnly()) {
    //   if ($this->hasFromDate() && $this->hasToDate()) {
    //     $date_range_condition = sprintf(
    //       "STR_TO_DATE(pi.date, '%s') >= %s AND STR_TO_DATE(pi.date, '%s') <= %s",
    //       '%m/%d/%Y',
    //       $this->getFromDateAsExpression(),
    //       '%m/%d/%Y',
    //       $this->getToDateAsExpression()
    //     );
    //   } else if ($this->hasFromDate() && !$this->hasToDate()) {
    //     $date_range_condition = sprintf(
    //       "STR_TO_DATE(pi.date, '%s') >= %s AND STR_TO_DATE(pi.date, '%s') <= %s",
    //       '%m/%d/%Y',
    //       $this->getFromDateAsExpression(),
    //       '%m/%d/%Y',
    //       $this->getCurrentDateAsDateObject()
    //     );
    //   } else if (!$this->hasFromDate() && $this->hasToDate()) {
    //     $date_range_condition = sprintf(
    //       "STR_TO_DATE(pi.date, '%s') <= %s",
    //       '%m/%d/%Y',
    //       $this->getToDateAsExpression()
    //     );
    //   }
    //
    //   $status_conditions_expression = $date_range_condition;
    // }
    //
    // return sprintf(
    //   "(
    //     %s AND %s
    //   )",
    //   $case_type_condition,
    //   $status_conditions_expression
    // );
  }


  private function prettyPrint($object) {
    echo("<pre>");
    var_dump($object);
    echo("</pre>");
  }


}
