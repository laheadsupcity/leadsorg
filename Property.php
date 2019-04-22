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

}

?>
