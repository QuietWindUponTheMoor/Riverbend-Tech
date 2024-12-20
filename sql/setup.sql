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
    `serial` VARCHAR(32) NOT NULL UNIQUE,
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


-- Update Tables --------------------------------------------------
CREATE TABLE IF NOT EXISTS _studentDeviceUpdated (
    oldValue VARCHAR(128) DEFAULT NULL,
    newValue VARCHAR(128) DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS _studentLoanerUpdated (
    oldValue VARCHAR(128) DEFAULT NULL,
    newValue VARCHAR(128) DEFAULT NULL
);
CREATE TABLE IF NOT EXISTS _cbinventoryAssetUpdated (
    oldValue VARCHAR(128) DEFAULT NULL,
    newValue VARCHAR(128) DEFAULT NULL
);

-- TRIGGERS --------------------------------------------------
DELIMITER $$

DROP TRIGGER IF EXISTS studentsUpdate$$
CREATE TRIGGER studentsUpdate
AFTER UPDATE ON students
FOR EACH ROW
BEGIN
    -- Student Device
    INSERT INTO _studentDeviceUpdated
    (oldValue, newValue) VALUES (OLD.device_asset, NEW.device_asset);
    -- Student Loaner
    INSERT INTO _studentLoanerUpdated
    (oldValue, newValue) VALUES (OLD.loaner_asset, NEW.loaner_asset);
END$$

DROP TRIGGER IF EXISTS cbinventoryUpdate$$
CREATE TRIGGER cbinventoryUpdate
AFTER UPDATE ON cbinventory
FOR EACH ROW
BEGIN
    -- Loaner
    INSERT INTO _cbinventoryAssetUpdated
    (oldValue, newValue) VALUES (OLD.asset, NEW.asset);
END$$

DELIMITER ;


-- PROCEDURES --------------------------------------------------
DELIMITER $$

DROP PROCEDURE IF EXISTS updates$$
CREATE PROCEDURE updates()
BEGIN
    -- cbinventory.person
    UPDATE cbinventory
    SET
        assignment = 'LOANER',
        person = _studentDeviceUpdated.newValue
    WHERE
        person = _studentDeviceUpdated.oldValue
        AND person != _studentDeviceUpdated.newValue
        OR (person IS NULL AND _studentDeviceUpdated.newValue IS NOT NULL)
        OR (person IS NOT NULL AND _studentDeviceUpdated.newValue IS NULL);
    UPDATE cbinventory
    SET
        assignment = 'LOANER',
        person = 'NONE'
    WHERE
        person = _studentDeviceUpdated.oldValue
        AND (UPPER(person) != 'NONE'
        OR person IS NOT NULL);

    UPDATE cbinventory
    SET
        assignment = 'LOANER',
        person = _studentLoanerUpdated.newValue
    WHERE
        person = _studentLoanerUpdated.oldValue
        AND person != _studentLoanerUpdated.newValue
        OR (person IS NULL AND _studentLoanerUpdated.newValue IS NOT NULL)
        OR (person IS NOT NULL AND _studentLoanerUpdated.newValue IS NULL);
    UPDATE cbinventory
    SET
        assignment = 'LOANER',
        person = 'NONE'
    WHERE
        person = _studentLoanerUpdated.oldValue
        AND (UPPER(person) != 'NONE'
        OR person IS NOT NULL);
    TRUNCATE TABLE _studentDeviceUpdated;
    TRUNCATE TABLE _studentLoanerUpdated;

    -- students.device_asset
    UPDATE students
    SET
        device_asset = _cbinventoryAssetUpdated.newValue
    WHERE
        device_asset = _cbinventoryAssetUpdated.oldValue
        AND device_asset != _cbinventoryAssetUpdated.newValue
        OR (person IS NULL AND _cbinventoryAssetUpdated.newValue IS NOT NULL)
        OR (person IS NOT NULL AND _cbinventoryAssetUpdated.newValue IS NULL);
    UPDATE students
    SET
        loaner_asset = _cbinventoryAssetUpdated.newValue
    WHERE
        loaner_asset = _cbinventoryAssetUpdated.oldValue
        AND loaner_asset != _cbinventoryAssetUpdated.newValue
        OR (person IS NULL AND _cbinventoryAssetUpdated.newValue IS NOT NULL)
        OR (person IS NOT NULL AND _cbinventoryAssetUpdated.newValue IS NULL);

    TRUNCATE TABLE _cbinventoryAssetUpdated;
END$$

DELIMITER ;

-- EVENTS/SCHEDULES --------------------------------------------------
DELIMITER $$

DROP EVENT IF EXISTS updates$$
CREATE EVENT IF NOT EXISTS updates
ON SCHEDULE EVERY 1 MINUTE
DO
BEGIN
    -- _studentDeviceUpdated
    CALL updates();
END$$

DELIMITER ;

DROP EVENT IF EXISTS updates;