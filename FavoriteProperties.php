<?php
require_once('config.php');

class FavoriteProperties {

  private $db;

  function __construct() {
    $this->db = Database::instance();
  }

  public function getFolderFromID($folder_id) {
    $this->db->select(
      'favorite_properties_folders',
      array(
        'folder_id' => $folder_id
      )
    );

    $results = $this->db->result_array();
    $folder = $results[0];

    return $folder;
  }

  public function getAllFoldersForUser($user_id) {

    $query = sprintf(
      "SELECT
        folder.folder_id,
        folder.name,
        count(fav_property.parcel_number) AS property_count
      FROM favorite_properties_folders AS folder
      LEFT JOIN favorite_properties AS fav_property ON (
        fav_property.folder_id = folder.folder_id
      )
      WHERE folder.user_id = %s
      GROUP BY folder.folder_id, folder.name
      ",
      $user_id
    );

    $this->db->query($query);

    $folders = $this->db->result();

    $results = [];
    foreach ($folders as $folder) {
      $has_unseen_updates = false;

      $properties = $this->getPropertiesForFolder($folder->folder_id);

      foreach ($properties as $property) {
        if ($property['has_unseen_updates']) {
          $has_unseen_updates = true;
          break;
        }
      }

      $folder->has_unseen_updates = $has_unseen_updates;
    }

    return $folders;
  }

  public function getPropertiesForFolder($folder_id) {
    $query = sprintf(
      "SELECT
        `p`.`parcel_number`,
        `p`.`street_number`,
        `p`.`street_name`,
        `p`.`site_address_city_state`,
        `p`.`site_address_zip`,
        `p`.`owner_name2`,
        `p`.`full_mail_address`,
        `p`.`mail_address_zip`,
        `p`.`number_of_units`,
        `p`.`number_of_stories`,
        `p`.`bedrooms`,
        `p`.`bathrooms`,
        `p`.`lot_area_sqft`,
        `p`.`building_area`,
        `p`.`cost_per_sq_ft`,
        `p`.`year_built`,
        `p`.`sales_date`,
        `p`.`sales_price`,
        `p`.`phone1`,
        `p`.`phone2`,
        `p`.`email1`,
        `p`.`email2`,
        `p`.`owner_address_and_zip`,
        `p`.`id`,
        `properties_with_updates_info`.`has_unseen_updates`,
        `related_property_counts`.`count` AS `related_property_count`
      FROM `property` AS `p`
      LEFT JOIN (
        SELECT
          `owner_address_and_zip`,
          count(`owner_address_and_zip`) - 1 AS `count`
        FROM
          `property`
        WHERE
          `full_mail_address` <> \"\"
        GROUP BY
          `owner_address_and_zip`
        HAVING
          `count` > 0
      ) as `related_property_counts`
      ON `p`.`owner_address_and_zip` = `related_property_counts`.`owner_address_and_zip`
      JOIN (
        SELECT
          DISTINCT `property2`.`parcel_number`,
          IF(
            (count(`case_detail`.APN) + count(`pi`.APN) > 0) OR
            (`property2`.date_modified > fav.date_last_viewed),
            1 ,
            0
          ) AS 'has_unseen_updates'
        FROM `property` AS `property2`
        JOIN `favorite_properties` AS `fav` ON (
          fav.parcel_number = property2.parcel_number
        )
        LEFT JOIN property_cases_detail AS `case_detail` ON (
          case_detail.apn = fav.parcel_number AND
          case_detail.date_modified > fav.date_last_viewed
        )
        LEFT JOIN property_inspection AS `pi` ON (
          pi.APN = fav.parcel_number AND
          pi.date_modified > fav.date_last_viewed
        )
        WHERE
          fav.folder_id = %s
        GROUP BY
          `property2`.`parcel_number`,
          `property2`.`date_modified`
      ) AS `properties_with_updates_info` ON (
        `properties_with_updates_info`.`parcel_number` = `p`.`parcel_number`
      )
      ORDER BY `properties_with_updates_info`.`has_unseen_updates` DESC;",
      $folder_id
    );

    $this->db->query($query);

    $properties = $this->db->result_array();

    return $properties;
  }

  public function markPropertyAsSeen($user_id, $parcel_number) {
    date_default_timezone_set('America/Los_Angeles');
    $query = sprintf(
      "UPDATE `favorite_properties` SET
      `date_last_viewed`='%s'
      WHERE `folder_id` IN (
        SELECT `folder_id` FROM `favorite_properties_folders`
        WHERE `user_id`=%s
      ) AND
      parcel_number=%s",
      date('Y-m-d H:i:s'),
      $user_id,
      $parcel_number
    );

    $this->db->query($query);
  }

  public function getPropertyCaseUpdatesForFolder($folder_id, $parcel_number) {
    if (!isset($folder_id)) {
      return array();
    }

    $query = sprintf(
      "
        SELECT
          `case_detail`.`property_case_id`
        FROM `favorite_properties` AS `fav`
        JOIN `favorite_properties_folders` AS `f` ON (
          `fav`.`folder_id` = `f`.`folder_id`
        )
        JOIN property_cases_detail AS `case_detail` ON (
          case_detail.apn = fav.parcel_number AND
          case_detail.date_modified > fav.date_last_viewed
        )
        WHERE
          `fav`.`parcel_number` = %s AND
          `f`.`folder_id` = %s;
      ",
      $parcel_number,
      $folder_id
    );

    $this->db->query($query);

    return array_map(
      function ($result) {
        return $result->property_case_id;
      },
      $this->db->result()
    );
  }

  public function getPropertyCaseInspectionUpdatesForFolder($folder_id, $parcel_number) {
    if (!isset($folder_id)) {
      return array();
    }

    $query = sprintf(
      "
        SELECT
          `pi`.`id`
        FROM `favorite_properties` AS `fav`
        JOIN `favorite_properties_folders` AS `f` ON (
          `fav`.`folder_id` = `f`.`folder_id`
        )
        JOIN property_inspection AS `pi` ON (
          pi.APN = fav.parcel_number AND
          pi.date_modified > fav.date_last_viewed
        )
        WHERE
          `fav`.`parcel_number` = %s AND
          `f`.`folder_id` = %s;
      ",
      $parcel_number,
      $folder_id
    );

    $this->db->query($query);

    return array_map(
      function ($result) {
        return $result->id;
      },
      $this->db->result()
    );
  }

}

?>
