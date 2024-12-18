-- Set up
CREATE DATABASE IF NOT EXISTS riverbendtech;
USE riverbendtech;
SET GLOBAL event_scheduler = ON;
SHOW VARIABLES LIKE 'event_scheduler';

-- Tables
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
        WHERE `sid`=NEW.studentID;
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