DROP TABLE IF EXISTS `property_notes`;

CREATE TABLE `property_notes` (
  `note_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) NOT NULL,
  `content` VARCHAR(255) NOT NULL,
  `is_private` BOOLEAN DEFAULT TRUE,
  `parcel_number` BIGINT(10) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `user_login`(`id`),
  FOREIGN KEY (`parcel_number`) REFERENCES `property`(`parcel_number`)
);
