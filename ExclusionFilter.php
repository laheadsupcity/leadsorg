<?php

class ExclusionFilter {

  private $case_type_id;

  function __construct($data_array) {
    $this->case_type_id = $data_array['case_type_id'];
  }

  public function getCaseTypeID() {
    return $this->case_type_id;
  }

}
