USE riverbendtech;

DROP TABLE IF EXISTS cbinventory_deprovisioned, checkouts_del, dropped_students, loaners_del;

DROP EVENT IF EXISTS update_loaners_assignment;
DROP EVENT IF EXISTS deprovision_checks;

ALTER TABLE checkouts DROP COLUMN school,
    DROP COLUMN assignedCB,
    DROP COLUMN grade,
    DROP COLUMN started,
    DROP COLUMN finished,
    DROP COLUMN softDeleted;

-- TRIGGERS
DELIMITER $$

-- devices insert
CREATE TRIGGER cbinventory_insert
AFTER INSERT ON cbinventory
FOR EACH ROW
BEGIN
    UPDATE students SET device_asset=UPPER(NEW.asset)
        WHERE `sid`=NEW.person
        AND device_asset != UPPER (NEW.asset);
END$$

-- students insert
CREATE TRIGGER students_insert
AFTER INSERT ON students
FOR EACH ROW
BEGIN
    UPDATE cbinventory SET assignment='STUDENT', person=NEW.sid
        WHERE asset=NEW.device_asset
        AND person != NEW.sid;
    UPDATE cbinventory SET assignment='LOANER', person=NEW.sid
        WHERE asset=NEW.loaner_asset
        AND person != NEW.sid;
END$$

-- checkouts insert
CREATE TRIGGER checkouts_insert
AFTER INSERT ON checkouts
FOR EACH ROW
BEGIN
    -- Update the student to reflect the loaner they were assigned
    UPDATE students SET loaner_asset=NEW.loanerCB
        WHERE `sid`=NEW.studentID
        AND loaner_asset != NEW.loanerCB;
    -- Update the device itself to reflect which student it is assigned to
    UPDATE cbinventory SET assignment='LOANER', person=NEW.studentID
        WHERE asset=NEW.loanerCB
        AND person != NEW.studentID;
END$$

-- cbinventory/devices updates
CREATE TRIGGER cbinventory_update
AFTER UPDATE ON cbinventory
FOR EACH ROW
BEGIN
    -- If asset was updated
    IF OLD.asset != NEW.asset THEN
        UPDATE checkouts SET loanerCB=NEW.asset
            WHERE loanerCB=OLD.asset
            AND loanerCB != NEW.asset;
        UPDATE students SET device_asset=NEW.asset
            WHERE device_asset=OLD.asset
            AND device_asset != NEW.asset;
        UPDATE students SET loaner_asset=NEW.asset
            WHERE loaner_asset=OLD.asset
            AND loaner_asset != NEW.asset;
    END IF;
END$$

-- students updates
CREATE TRIGGER students_update
AFTER UPDATE ON students
FOR EACH ROW
BEGIN
    -- If device_asset was updated
    IF OLD.device_asset != NEW.device_asset THEN
        UPDATE cbinventory SET person='NONE', assignment='LOANER'
            WHERE asset=OLD.device_asset
            AND person != 'NONE'; -- Unjoin old device with student
        UPDATE cbinventory SET person=NEW.sid, assignment='STUDENT'
            WHERE asset=NEW.device_asset
            AND person != NEW.sid; -- Join new device with student
    END IF;
    -- If loaner_asset was updated
    IF OLD.loaner_asset != NEW.loaner_asset THEN
        UPDATE cbinventory SET person='NONE', assignment='LOANER'
            WHERE asset=OLD.loaner_asset
            AND person != 'NONE'; -- Unjoin old loaner with student
        UPDATE cbinventory SET person=NEW.sid, assignment='LOANER'
            WHERE asset=NEW.loaner_asset
            AND person != NEW.sid;
        UPDATE checkouts SET loanerCB=NEW.loaner_asset
            WHERE studentID=NEW.sid
            AND loanerCB != NEW.loaner_asset;
    END IF;
END$$

DELIMITER ;