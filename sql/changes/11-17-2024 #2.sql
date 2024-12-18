DROP TRIGGER checkouts_insert;

DELIMITER $$
CREATE TRIGGER checkouts_insert
AFTER INSERT ON checkouts
FOR EACH ROW
BEGIN
    -- Update the student to reflect the loaner they were assigned
    UPDATE students SET loaner_asset=NEW.loanerCB
        WHERE `sid`=NEW.studentID;
    -- Update the device itself to reflect which student it is assigned to
    UPDATE cbinventory SET assignment='LOANER', person=NEW.studentID
        WHERE asset=NEW.loanerCB
        AND person != NEW.studentID;
END$$
DELIMITER ;