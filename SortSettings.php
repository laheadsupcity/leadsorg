<?php

class SortSettings {

  const DESCENDING = "DESC";
  const ASCENDING = "ASC";
  const NEITHER = "NEITHER";

  const COLUMN_NAME_MAP = [
    // whatever idiot made the schema stored int values in varchars
    // cannot change the schema at this point without modifying the whole scraper which I don't have access to
    // casting some columns to int before ordering as a workaround
    'num_units' => 'cast(`p`.`number_of_units` AS UNSIGNED)',
    'building_area' => '`p`.`building_area`',
    'lot_area_sqft' => 'cast(`p`.`lot_area_sqft` AS UNSIGNED)',
    'num_beds' => '`p`.`bedrooms`',
    'owner_name' => '`p`.`owner_name2`',
    'sale_date' => '`p`.`sales_date`',
    'year_built' => '`p`.`year_built`',
    'related_properties' => '`related_property_counts`.`count`'
  ];

  private $sort_settings = array();

  function __construct($raw_sort_settings) {
    $this->sort_settings = array_map(
      'self::convertRawSortSetting',
      $raw_sort_settings
    );
  }

  static function convertRawSortSetting($setting) {
    return [
      'column' => $setting['col'],
      'order' => (int)$setting['ord'],
      'direction' => $setting['dir']
    ];
  }

  private function getOrderedSettings() {
    $sort_settings = $this->sort_settings;
    usort(
      $sort_settings,
      function($a, $b) {
        return $a['order'] > $b['order'] ? 1 : ($a['order'] < $b['order'] ? -1 : 0);
      }
    );

    return $sort_settings;
  }

  private static function getColumnName($raw_name) {
    return isset(self::COLUMN_NAME_MAP[$raw_name]) ?
           self::COLUMN_NAME_MAP[$raw_name] :
           null;
  }

  function getSortByClause() {
    $clauses = array();
    foreach ($this->getOrderedSettings() as $setting) {
      $column_name = self::getColumnName($setting['column']);
      if (isset($column_name) && $setting['direction'] != self::NEITHER) {
        $clauses[] = sprintf(
          "%s %s",
          $column_name,
          $setting['direction'] == self::DESCENDING ? self::DESCENDING : self::ASCENDING
        );
      }
    }

    return sprintf(
      "
      ORDER BY
      %s
      ",
      implode(', ', $clauses)
    );
  }

}
