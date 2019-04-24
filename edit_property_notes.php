<?php
require_once('config.php');

$db = Database::instance();

$user_id = $_POST['user_id'];
$parcel_number = $_POST['parcel_number'];
$content = $_POST['content'];
$is_private = $_POST['is_private'] == "true" ? 1 : 0;

if ($is_private) {
  $db->select(
    'property_notes',
    array(
      'user_id' => $user_id,
      'parcel_number' => $parcel_number,
      'is_private' => $is_private
    )
  );
} else {
  $db->select(
    'property_notes',
    array(
      'parcel_number' => $parcel_number,
      'is_private' => $is_private
    )
  );
}

$existing_notes = $db->result_array();

$existing_note = empty($existing_notes) ? null : $existing_notes[0];

if (isset($existing_note)) {
  $db->update(
    'property_notes',
    array(
      'content' => $content,
      'is_private' => $is_private
    ),
    array(
      'note_id' => $existing_note['note_id']
    )
  );
} else if ($content != "") {
  $db->insert(
    'property_notes',
    array(
      'parcel_number' => $parcel_number,
      'user_id' => $user_id,
      'content' => $content,
      'is_private' => $is_private
    )
  );
}
