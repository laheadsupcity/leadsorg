drop procedure if exists derive_open_flag;

delimiter $$

create procedure derive_open_flag(IN v_property_case_detail_id INT)
BEGIN
    DECLARE v_is_case_open BOOLEAN;

    SELECT IF(count(*) = 0, 1, 0) INTO `v_is_case_open`
    FROM `property_inspection` AS `pi`
    WHERE
      `property_case_detail_id` = v_property_case_detail_id AND
      `staus` = 'All Violations Resolved Date';

    UPDATE property_cases_detail
    SET is_case_open = v_is_case_open
    WHERE
      id = v_property_case_detail_id AND
      is_case_open != v_is_case_open;
END;
$$

delimiter ;
