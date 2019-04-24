<?php
require_once('config.php');

class Property {

  public static function getRelatedPropertiesForAPN($parcel_number) {
    $db = Database::instance();

    $query = sprintf(
      "
      SELECT * FROM `property`
        WHERE
        `owner_address_and_zip` IN (
            SELECT `owner_address_and_zip` FROM `property`
            WHERE `parcel_number` = %s
        ) AND
        `full_mail_address` <> \"\" AND
        `parcel_number` <> %s

      ",
      $parcel_number,
      $parcel_number,
      $parcel_number
    );

    $db->query($query);

    return $db->result_array();
  }

  public static function getPrivateNoteForAPN($user_id, $parcel_number) {
    $db = Database::instance();

    $query = sprintf(
      "
        SELECT * FROM `property_notes`
        WHERE
          `user_id` = %s AND
          `is_private` = 1 AND
          `parcel_number` = %s
      ",
      $user_id,
      $parcel_number
    );

    $db->query($query);

    $results = $db->result_array();

    return empty($results) ? null : $results[0];
  }

  public static function getPublicNoteForAPN($parcel_number) {
    $db = Database::instance();

    $query = sprintf(
      "
        SELECT * FROM `property_notes`
        WHERE
          `is_private` = 0 AND
          `parcel_number` = %s
      ",
      $parcel_number
    );

    $db->query($query);

    $results = $db->result_array();

    return empty($results) ? null : $results[0];
  }

}

?>
