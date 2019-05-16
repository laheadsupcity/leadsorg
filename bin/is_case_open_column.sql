ALTER TABLE `property_cases_detail`
ADD COLUMN `is_case_open` BOOLEAN NOT NULL;

ALTER TABLE `property_cases_detail`
ADD INDEX `is_case_open_idx` (`is_case_open`);
