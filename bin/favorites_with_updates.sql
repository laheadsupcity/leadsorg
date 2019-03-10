SELECT
  DISTINCT `property2`.`parcel_number`,
  count(`cases`.APN) AS `case_updates`,
  count(`case_detail`.APN) AS `case_detail_updates`,
  count(`property_detail`.APN) AS `property_detail_updates`,
  count(`pi`.APN) AS `property_inspection_updates`,
  (`property2`.date_modified > fav.date_last_viewed) AS `property_updates`,
  `property2`.date_modified,
  fav.date_last_viewed,
  IF(
    (count(`cases`.APN) +
    count(`case_detail`.APN) +
    count(`property_detail`.APN) +
    count(`pi`.APN) > 0) OR
    (`property2`.date_modified > fav.date_last_viewed),
    1 ,
    0
  ) AS 'has_unseen_updates'
FROM `property` AS `property2`
JOIN `favorite_properties` AS `fav` ON (
  fav.parcel_number = property2.parcel_number
)
LEFT JOIN property_cases AS `cases` ON (
  cases.APN = fav.parcel_number AND
  cases.date_modified > fav.date_last_viewed
)
LEFT JOIN property_cases_detail AS `case_detail` ON (
  case_detail.apn = fav.parcel_number AND
  case_detail.date_modified > fav.date_last_viewed
)
LEFT JOIN property_detail AS `property_detail` ON (
  property_detail.apn = fav.parcel_number AND
  property_detail.date_modified > fav.date_last_viewed
)
LEFT JOIN property_inspection AS `pi` ON (
  pi.APN = fav.parcel_number AND
  pi.date_modified > fav.date_last_viewed
)
WHERE
  fav.folder_id = 3
GROUP BY
  `property2`.`parcel_number`,
  `property2`.`date_modified`;
