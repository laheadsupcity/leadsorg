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
        `property1`.*,
        `properties_with_updates_info`.`has_unseen_updates`,
        `related_properties`.`count` AS `related_properties_count`
      FROM (
        SELECT
          `full_mail_address`,
          count(`full_mail_address`)-1 AS `count`
        FROM `property`
        WHERE `full_mail_address` <> \"\"
        GROUP BY `full_mail_address`
      ) AS `related_properties`
      RIGHT JOIN `property` AS `property1` ON (
        `property1`.`full_mail_address` = `related_properties`.`full_mail_address`
      )
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
        `properties_with_updates_info`.`parcel_number` = `property1`.`parcel_number`
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

}

?>
