-- Set up --------------------------------------------------
CREATE DATABASE IF NOT EXISTS riverbendtech;
USE riverbendtech;
SHOW VARIABLES LIKE 'event_scheduler';
SET GLOBAL event_scheduler = ON;

-- Tables --------------------------------------------------
CREATE TABLE IF NOT EXISTS checkouts (
  recordID bigint(44) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  studentID bigint(44) NOT NULL,
  loanerCB varchar(20) NOT NULL,
  issue varchar(1200) NOT NULL
);

CREATE TABLE IF NOT EXISTS loaners (
    loaner VARCHAR(25) PRIMARY KEY NOT NULL,
    serial VARCHAR(15) DEFAULT NULL,
    assignment VARCHAR(128) DEFAULT "SPARE" NOT NULL
);

CREATE TABLE IF NOT EXISTS cbinventory (
    asset VARCHAR(128) PRIMARY KEY NOT NULL,
    `serial` VARCHAR(32) NOT NULL,
    PO VARCHAR(128) DEFAULT NULL,
    model VARCHAR(32) NOT NULL,
    building VARCHAR(10) DEFAULT 'FES' NOT NULL,
    assignment VARCHAR(128) NOT NULL, -- OPTIONS: "LOANER", "STUDENT", "STAFF", "DEPROVISIONED", "DONOR", etc
    person VARCHAR(128) DEFAULT 'NONE' NOT NULL -- OPTIONS: "<StudentID>" if assignment "STUDENT", "Mrs. <Name>" if assignment "STAFF", "NONE" if assignment is "LOANER"
);

CREATE TABLE IF NOT EXISTS students (
    `sid` VARCHAR(32) PRIMARY KEY NOT NULL, -- Student ID
    `first` VARCHAR(128) DEFAULT NULL,
    `last` VARCHAR(128) DEFAULT NULL,
    grade VARCHAR(128) NOT NULL, -- Options: "K", 1, 2, 3, -- 12, etc.
    homeroom VARCHAR(128) DEFAULT NULL, -- Examples: 2A, KB, Mr. <Last>, Mrs. <Last>, etc
    email VARCHAR(128) DEFAULT 'UNSET' NOT NULL,
    device_asset VARCHAR(128) DEFAULT 'UNSET' NOT NULL,
    loaner_asset VARCHAR(128) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS users (
    `uid` BIGINT PRIMARY KEY NOT NULL,
    permission_level INT(2) DEFAULT 0 NOT NULL, -- 0 = default, 1 = manager, 2 = admin
    email VARCHAR(256) NOT NULL,
    `image` TEXT NOT NULL,
    `first` VARCHAR(128) NOT NULL,
    `last` VARCHAR(128) NOT NULL,
    full_name VARCHAR(128) NOT NULL
);

CREATE TABLE IF NOT EXISTS managers (
    `email` VARCHAR(256) PRIMARY KEY NOT NULL
);

CREATE TABLE IF NOT EXISTS admins (
    `email` VARCHAR(256) PRIMARY KEY NOT NULL
);

CREATE TABLE IF NOT EXISTS external_whitelist (
    `email` VARCHAR(256) PRIMARY KEY NOT NULL
);

-- EVENTS/SCHEDULES --------------------------------------------------
DELIMITER $$

DROP EVENT IF EXISTS updates$$
CREATE EVENT IF NOT EXISTS updates
ON SCHEDULE EVERY 1 MINUTE
DO
BEGIN
    -- If cbinventory.person is updated
    UPDATE students 
    SET loaner_asset = (
        SELECT asset FROM cbinventory
        WHERE cbinventory.assignment = 'LOANER'
        AND (
            cbinventory.person = students.sid -- Match by person
            OR students.loaner_asset = cbinventory.asset -- Match by loaner_asset
        )
        LIMIT 1
    )
    WHERE `sid` IN (
        SELECT person FROM cbinventory
        WHERE assignment = 'LOANER'
        UNION
        SELECT sid FROM students 
        WHERE loaner_asset IN (
            SELECT asset FROM cbinventory WHERE assignment = 'LOANER'
        )
    );
    
    UPDATE students 
    SET device_asset = (
        SELECT asset FROM cbinventory 
        WHERE cbinventory.assignment = 'STUDENT'
        AND (
            cbinventory.person = students.sid -- Match by person
            OR students.device_asset = cbinventory.asset -- Match by device_asset
        )
        LIMIT 1
    )
    WHERE sid IN (
        SELECT person FROM cbinventory 
        WHERE assignment = 'STUDENT'
        UNION
        SELECT sid FROM students 
        WHERE device_asset IN (
            SELECT asset FROM cbinventory WHERE assignment = 'STUDENT'
        )
    );

    

    -- Set student.device_asset
    UPDATE students SET device_asset = (
        SELECT asset FROM cbinventory
            WHERE assignment = 'STUDENT'
            AND person
    )
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!
    -- WAS WORKING HERE!!!!!!!!!!!!!!!!!!!!!!!!!!



    /*-- Update the student to reflect the loaner they were assigned
    UPDATE students 
    SET loaner_asset = (SELECT loanerCB FROM checkouts WHERE studentID = students.sid)
    WHERE `sid` IN (SELECT studentID FROM checkouts WHERE loanerCB IS NOT NULL);

    -- Update the device itself to reflect which student it is assigned to
    UPDATE cbinventory 
    SET assignment = 'LOANER', person = (SELECT studentID FROM checkouts WHERE loanerCB = cbinventory.asset)
    WHERE asset IN (SELECT loanerCB FROM checkouts WHERE loanerCB IS NOT NULL);

    -- Update cbinventory for device_asset if it has changed
    UPDATE cbinventory 
    SET person = 'NONE', assignment = 'LOANER'
    WHERE asset IN (SELECT OLD.device_asset FROM students WHERE device_asset != OLD.device_asset AND device_asset IS NOT NULL)
    AND person != 'NONE';  -- Unjoin old device with student

    UPDATE cbinventory 
    SET person = NEW.sid, assignment = 'STUDENT'
    WHERE asset IN (SELECT NEW.device_asset FROM students WHERE device_asset != NEW.device_asset AND device_asset IS NOT NULL)
    AND person != NEW.sid;  -- Join new device with student

    -- Update cbinventory for loaner_asset if it has changed
    UPDATE cbinventory 
    SET person = 'NONE', assignment = 'LOANER'
    WHERE asset IN (SELECT OLD.loaner_asset FROM students WHERE loaner_asset != OLD.loaner_asset AND loaner_asset IS NOT NULL)
    AND person != 'NONE';  -- Unjoin old loaner with student

    UPDATE cbinventory 
    SET person = NEW.sid, assignment = 'LOANER'
    WHERE asset IN (SELECT NEW.loaner_asset FROM students WHERE loaner_asset != NEW.loaner_asset AND loaner_asset IS NOT NULL)
    AND person != NEW.sid;  -- Join new loaner with student

    -- Update checkouts for loaner_asset if it has changed
    UPDATE checkouts 
    SET loanerCB = NEW.loaner_asset
    WHERE studentID = NEW.sid
    AND loanerCB != NEW.loaner_asset;

    -- If asset was updated
    UPDATE checkouts 
    SET loanerCB = NEW.asset
    WHERE loanerCB IN (SELECT OLD.asset FROM cbinventory WHERE OLD.asset != NEW.asset)
    AND loanerCB != NEW.asset;

    UPDATE students 
    SET device_asset = NEW.asset
    WHERE device_asset IN (SELECT OLD.asset FROM cbinventory WHERE OLD.asset != NEW.asset)
    AND device_asset != NEW.asset;

    UPDATE students 
    SET loaner_asset = NEW.asset
    WHERE loaner_asset IN (SELECT OLD.asset FROM cbinventory WHERE OLD.asset != NEW.asset)
    AND loaner_asset != NEW.asset;

    -- Update cbinventory for the device_asset (if assigned)
    UPDATE cbinventory 
    SET assignment = 'STUDENT', person = NEW.sid
    WHERE asset IN (SELECT device_asset FROM students WHERE sid = NEW.sid)
    AND person != NEW.sid;

    -- Update cbinventory for the loaner_asset (if assigned)
    UPDATE cbinventory 
    SET assignment = 'LOANER', person = NEW.sid
    WHERE asset IN (SELECT loaner_asset FROM students WHERE sid = NEW.sid)
    AND person != NEW.sid;

    -- Update students with the correct device_asset (if needed)
    UPDATE students 
    SET device_asset = UPPER(NEW.asset)
    WHERE sid IN (SELECT person FROM cbinventory WHERE asset = NEW.asset)
    AND device_asset != UPPER(NEW.asset);*/
END$$


DELIMITER ;