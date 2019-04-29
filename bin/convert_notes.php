<?php
require_once('config.php');

$delete_query = sprintf(
  "
    DELETE FROM `property_notes`
  "
);

$db->query($delete_query);

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
