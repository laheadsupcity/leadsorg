<?php
require_once('config.php');

class FavoriteProperties {

  private $db;

  function __construct() {
    $this->db = Database::instance();
  }

  public function getAllFoldersForUser($user_id) {
    $query = sprintf(
      "SELECT folder.folder_id, folder.name, count(fav_property.parcel_number) AS property_count FROM favorite_properties_folders AS folder
      JOIN favorite_properties AS fav_property ON (
        fav_property.folder_id = folder.folder_id
      )
      GROUP BY folder.folder_id, folder.name
      "
    );

    $this->db->query($query);

    $folders = $this->db->result_array();

    return $folders;
  }

}

?>
