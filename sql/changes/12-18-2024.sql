DROP TRIGGER IF EXISTS students_update;

CREATE TABLE IF NOT EXISTS trigger_debug_log (
    debug_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    old_value VARCHAR(255),
    new_value VARCHAR(255)
);





DELIMITER $$

CREATE PROCEDURE IF NOT EXISTS log_debug(old_value VARCHAR(255), new_value VARCHAR(255))
BEGIN
    INSERT INTO trigger_debug_log (old_value, new_value)
    VALUES (old_value, new_value);
END$$

CREATE TRIGGER students_update
AFTER UPDATE ON students
FOR EACH ROW
BEGIN
    -- If device_asset was updated
    IF OLD.device_asset != NEW.device_asset OR (OLD.device_asset IS NULL AND NEW.device_asset IS NOT NULL) OR (OLD.device_asset IS NOT NULL AND NEW.device_asset IS NULL) THEN
        CALL log_debug(OLD.loaner_asset, NEW.loaner_asset); -- Debug
        UPDATE cbinventory SET person='NONE', assignment='LOANER'
            WHERE asset=OLD.device_asset
            AND person != 'NONE'; -- Unjoin old device with student
        UPDATE cbinventory SET person=NEW.sid, assignment='STUDENT'
            WHERE asset=NEW.device_asset
            AND person != NEW.sid; -- Join new device with student
    END IF;
    -- If loaner_asset was updated
    IF OLD.loaner_asset != NEW.loaner_asset OR (OLD.loaner_asset IS NULL AND NEW.loaner_asset IS NOT NULL) OR (OLD.loaner_asset IS NOT NULL AND NEW.loaner_asset IS NULL) THEN
        CALL log_debug(OLD.loaner_asset, NEW.loaner_asset); -- Debug
        UPDATE cbinventory SET person='NONE', assignment='LOANER'
            WHERE asset=OLD.loaner_asset
            AND person != 'NONE'; -- Unjoin old loaner with student
        UPDATE cbinventory SET person=NEW.sid, assignment='LOANER'
            WHERE asset=NEW.loaner_asset
            AND person != NEW.sid; -- Join new loaner with student
        UPDATE checkouts SET loanerCB=NEW.loaner_asset
            WHERE studentID=NEW.sid
            AND loanerCB != NEW.loaner_asset;
    END IF;
END$$

DELIMITER ;