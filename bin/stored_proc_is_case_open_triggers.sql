delimiter $$

create trigger property_inspection_rai
after insert on property_inspection
for each row
BEGIN
  call derive_open_flag(new.property_case_detail_id);
END;
$$

create trigger property_inspection_rau
after update on property_inspection
for each row
BEGIN
  call derive_open_flag(new.property_case_detail_id);
END;
$$

create trigger property_inspection_rad
after delete on property_inspection
for each row
BEGIN
  call derive_open_flag(old.property_case_detail_id);
END;
$$

delimiter ;
