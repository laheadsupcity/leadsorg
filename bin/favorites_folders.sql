DROP TABLE IF EXISTS `favorite_properties`;
DROP TABLE IF EXISTS `favorite_properties_folders`;

CREATE TABLE `favorite_properties_folders` (
  `folder_id` bigint(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_created` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `user_login`(`id`)

);

CREATE TABLE `favorite_properties` (
  `parcel_number` bigint(10) NOT NULL DEFAULT '0',
  `folder_id` bigint(10) NOT NULL DEFAULT '0',
  `date_created` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`folder_id`) REFERENCES `favorite_properties_folders`(`folder_id`),
  FOREIGN KEY (`parcel_number`) REFERENCES `property`(`parcel_number`),
  PRIMARY KEY (`parcel_number`, `folder_id`)
);
