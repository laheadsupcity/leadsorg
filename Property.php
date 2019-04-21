<?php
require_once('config.php');

class Property {

  public static function getRelatedPropertiesForAPN($parcel_number) {
    $db = Database::instance();

    $query = sprintf(
      "
      SELECT * FROM `property`
        WHERE
        `full_mail_address` IN (
            SELECT `full_mail_address` FROM `property`
            WHERE `parcel_number` = %s
        ) AND
        `mail_address_zip` IN (
          SELECT `mail_address_zip` FROM `property`
          WHERE `parcel_number` = %s
        ) AND
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
