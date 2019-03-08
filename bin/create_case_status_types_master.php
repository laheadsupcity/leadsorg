<?php
require_once('../config.php');
require_once('../CaseStatusTypes.php');

$db = Database::instance();

$case_status_types = $db->getCaseStatusTypes();

foreach ($case_status_types as $case_type_id => $status_types) {
  foreach ($status_types as $status) {
    $status_type_id = CaseStatusTypes::getStatusID($case_type_id, $status);

    $db->update(
      'property_inspection',
      array(
        'status_type_id' => $status_type_id
      ),
      array(
        'case_type_id' => $case_type_id,
        'staus' => $status
      )
    );
  }
}

?>
