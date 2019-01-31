<?php

class CaseTypeStatusFilter {

  private $is_include;
  private $case_status_type;
  private $from_date;
  private $to_date;

  function __construct($data_array, $is_include) {
    $this->is_include = $is_include;
    $this->case_status_type = $data_array['case_status_type'];
    $this->from_date = $data_array['from_date'];
    $this->to_date = $data_array['to_date'];
  }


  public function hasFromDate() {
    return !empty($this->from_date);
  }

  public function hasToDate() {
    return !empty($this->to_date);
  }


  public function isExclude() {
    return !$this->is_include;
  }

  public function getFromDateAsExpression() {
    return sprintf(
      "STR_TO_DATE(\"%s\", '%s')",
      $this->from_date,
      "%m/%d/%Y"
    );
  }

  public function getToDateAsExpression() {
    return sprintf(
      "STR_TO_DATE(\"%s\", '%s')",
      $this->to_date,
      "%m/%d/%Y"
    );
  }

  public function getCurrentDateAsDateObject() {
    return sprintf(
      "STR_TO_DATE(\"%s\", '%s')",
      date('m/d/Y', time()),
      "%m/%d/%Y"
    );
  }

  public function getStatus() {
    return $this->case_status_type;
  }

  public function getCondition($status_property = "pi.staus") {
    $status_condition = sprintf("%s=\"%s\"", $status_property, $this->getStatus());

    $date_range_condition = null;
    if (!empty($this->from_date) && !empty($this->to_date)) {
      $date_range_condition = sprintf(
        "STR_TO_DATE(pi.date, '%s') >= %s AND STR_TO_DATE(pi.date, '%s') <= %s",
        '%m/%d/%Y',
        $this->getFromDateAsExpression(),
        '%m/%d/%Y',
        $this->getToDateAsExpression()
      );
    } else if (!empty($this->from_date) && empty($this->to_date)) {
      $date_range_condition = sprintf(
        "STR_TO_DATE(pi.date, '%s') >= %s AND STR_TO_DATE(pi.date, '%s') <= %s",
        '%m/%d/%Y',
        $this->getFromDateAsExpression(),
        '%m/%d/%Y',
        $this->getCurrentDateAsDateObject()
      );
    } else if (empty($this->from_date) && !empty($this->to_date)) {
      $date_range_condition = sprintf(
        "STR_TO_DATE(pi.date, '%s') <= %s",
        '%m/%d/%Y',
        $this->getToDateAsExpression()
      );
    }

    if (!empty($date_range_condition)) {
      return sprintf(
        "(%s AND (%s))",
        $status_condition,
        $date_range_condition
      );
    } else {
      return sprintf(
        "(%s)",
        $status_condition
      );
    }
  }

}
