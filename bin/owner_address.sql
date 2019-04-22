ALTER TABLE `property`
DROP COLUMN `owner_address_and_zip`;

ALTER TABLE `property`
ADD COLUMN `owner_address_and_zip` VARCHAR(255) NOT NULL;

DROP TRIGGER IF EXISTS insert_trigger_property_owner_address;
DROP TRIGGER IF EXISTS update_trigger_property_owner_address;

UPDATE `property`
SET `owner_address_and_zip` = TRIM(
  CONCAT(
    TRIM(full_mail_address),
    ' ',
    TRIM(mail_address_zip)
  )
);

CREATE TRIGGER insert_trigger_property_owner_address
BEFORE INSERT ON `property`
FOR EACH ROW
SET new.owner_address_and_zip = TRIM(
  CONCAT(
    TRIM(new.full_mail_address),
    ' ',
    TRIM(new.mail_address_zip)
  )
);

CREATE TRIGGER update_trigger_property_owner_address
BEFORE UPDATE ON `property`
FOR EACH ROW
SET new.owner_address_and_zip = TRIM(
  CONCAT(
    TRIM(new.full_mail_address),
    ' ',
    TRIM(new.mail_address_zip)
  )
);
