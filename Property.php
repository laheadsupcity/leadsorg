<?php
require_once('config.php');

class Property {

  public static function getRelatedPropertiesForAPN($parcel_number) {
    $db = Database::instance();

    $query = sprintf(
      "
      SELECT
        p.parcel_number,
        p.street_number,
        p.street_name,
        p.site_address_city_state,
        p.site_address_zip,
        p.owner_name2,
        p.full_mail_address,
        p.mail_address_zip,
        p.number_of_units,
        p.number_of_stories,
        p.bedrooms,
        p.bathrooms,
        p.lot_area_sqft,
        p.building_area,
        p.cost_per_sq_ft,
        p.year_built,
        p.sales_date,
        p.sales_price,
        p.phone1,
        p.phone2,
        p.email1,
        p.email2,
        p.owner_address_and_zip,
        p.id
      FROM `property` AS `p`
        WHERE
        `owner_address_and_zip` IN (
            SELECT `owner_address_and_zip` FROM `property`
            WHERE `parcel_number` = %s
        ) AND
        `full_mail_address` <> \"\" AND
        `parcel_number` <> %s
      ",
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
