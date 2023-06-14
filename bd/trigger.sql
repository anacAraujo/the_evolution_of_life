DELIMITER //
CREATE TRIGGER planet_created AFTER INSERT ON users
FOR EACH ROW
BEGIN
    DECLARE item_id INT;
    DECLARE item_qnt_default INT;
    
    DECLARE done INT DEFAULT FALSE;
    
    DECLARE items_cursor CURSOR FOR
    SELECT id, qnt_elements_default FROM items;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN items_cursor;
    
    getitem: LOOP
        FETCH items_cursor INTO item_id, item_qnt_default;
        
        IF done THEN
            LEAVE getitem;
        END IF;
        
        INSERT INTO planets_items_inventory (planet_id, item_id, quantity)
        VALUES (users.user_id, item_id, item_qnt_default);
    END LOOP getitem;
    
    CLOSE items_cursor;
END
//DELIMITER ;