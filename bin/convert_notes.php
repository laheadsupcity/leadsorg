<?php
require_once('config.php');

$query = sprintf(
  "
    SELECT `parcel_number`, `notes` FROM `property`
    WHERE `notes` <> \"\"
  "
);

$db->query($query);

$results = $db->result();

foreach ($results as $entry) {
  $db->insert(
    'property_notes',
    array(
      'user_id' => 1,
      'content' => $entry->notes,
      'is_private' => 1,
      'parcel_number' => $entry->parcel_number
    )
  );
}

?>
