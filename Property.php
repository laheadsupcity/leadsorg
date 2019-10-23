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
        SELECT `content` FROM `property_notes`
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

    return empty($results) ? "" : $results[0]['content'];
  }

  public static function getPublicNoteForAPN($parcel_number) {
    $db = Database::instance();

    $query = sprintf(
      "
        SELECT `content` FROM `property_notes`
        WHERE
          `is_private` = 0 AND
          `parcel_number` = %s
      ",
      $parcel_number
    );

    $db->query($query);

    $results = $db->result_array();

    return empty($results) ? "" : $results[0]['content'];
  }

  public static function getCSVExportDataForAPN($user_id, $parcel_number)
  {
    $db = Database::instance();
    $query = sprintf(
      "
      SELECT
        `p`.parcel_number,
        `favorites`.`folders`,
        `p`.owner_name2,
        `p`.phone1,
        `p`.phone2,
        `p`.email1,
        `p`.email2,
        `p`.owner1_first_name,
        `p`.owner1_middle_name,
        `p`.owner1_last_name,
        `p`.owner1_spouse_first_name,
        `p`.owner2_first_name,
        `p`.owner2_middle_name,
        `p`.owner2_last_name,
        `p`.owner2_spouse_first_name,
        `p`.site_address_street_prefix,
        `p`.street_number,
        `p`.street_name,
        `p`.site_address_zip,
        `p`.site_address_city_state,
        `p`.full_mail_address,
        `p`.mail_address_city_state,
        `p`.mail_address_zip,
        `p`.site_address_unit_number,
        `p`.use_code,
        `p`.use_code_descrition,
        `p`.building_area,
        `p`.bedrooms,
        `p`.bathrooms,
        `p`.tract,
        `p`.lot_area_sqft,
        `p`.lot_area_acres,
        `p`.building_area,
        `p`.year_built,
        `p`.pool,
        `p`.year_built,
        `p`.garage_type,
        `p`.sales_date,
        `p`.sales_price,
        `p`.sales_price_code,
        `p`.sales_document_number,
        `p`.tax_exemption_code,
        `p`.fireplace,
        `p`.number_of_units,
        `p`.number_of_stories,
        `p`.owner_occupied,
        `p`.zoning,
        `p`.mail_flag,
        `p`.cost_per_sq_ft,
        `p`.total_assessed_value,
        `p`.total_market_value,
        `p`.assessed_improvement_value,
        `p`.assessed_land_value,
        `p`.assessed_improve_percent,
        `p_detail`.census_tract,
        `p_detail`.address,
        `p_detail`.rent_registration_number,
        `p_detail`.exemption,
        `p_detail`.rentoffice,
        `p_detail`.coderegionalaea,
        `p_detail`.council_district
      FROM property AS `p`
      JOIN property_detail AS `p_detail`
      ON `p`.parcel_number = `p_detail`.apn
      LEFT JOIN (
        SELECT
          `favorite_properties`.`parcel_number` AS `parcel_number`,
          GROUP_CONCAT(`favorite_properties_folders`.`name`) AS `folders`
        FROM `favorite_properties`
        LEFT JOIN `favorite_properties_folders`
        ON `favorite_properties_folders`.`folder_id` = `favorite_properties`.`folder_id`
        WHERE `favorite_properties_folders`.`user_id` = %s
        GROUP BY `favorite_properties`.`parcel_number`
      ) AS `favorites`
      ON `favorites`.`parcel_number` = `p`.`parcel_number`
      WHERE
        `p`.`parcel_number` = %s;
      ",
      $user_id,
      $parcel_number
    );

    $db->query($query);

    $results = $db->result_array();

    $result = $results[0];

    $result['private_note'] = self::getPrivateNoteForAPN($user_id, $parcel_number);
    $result['public_note'] = self::getPublicNoteForAPN($parcel_number);
    $result['related_properties_count'] = count(self::getRelatedPropertiesForAPN($parcel_number));

    return $result;
  }

}
