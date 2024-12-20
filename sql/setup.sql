-- Set up --------------------------------------------------
CREATE DATABASE IF NOT EXISTS riverbendtech;
USE riverbendtech;
SHOW VARIABLES LIKE 'event_scheduler';
SET GLOBAL event_scheduler = ON;

-- Tables --------------------------------------------------

CREATE TABLE IF NOT EXISTS checkouts (
  recordID bigint(44) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `sid` bigint(44) NOT NULL,
  issue varchar(1200) NOT NULL
);

CREATE TABLE IF NOT EXISTS devices (
    deviceID BIGINT(44) PRIMARY KEY NOT NULL,
    asset VARCHAR(128) NOT NULL UNIQUE,
    `serial` VARCHAR(32) NOT NULL UNIQUE,
    PO VARCHAR(128) DEFAULT NULL,
    model VARCHAR(32) NOT NULL,
    assignment VARCHAR(128) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS students (
    `sid` VARCHAR(32) PRIMARY KEY NOT NULL, -- Student ID
    `first` VARCHAR(128) DEFAULT NULL,
    `last` VARCHAR(128) DEFAULT NULL,
    grade VARCHAR(128) NOT NULL, -- Options: "K", 1, 2, 3, -- 12, etc.
    homeroom VARCHAR(128) DEFAULT NULL, -- Examples: 2A, KB, Mr. <Last>, Mrs. <Last>, etc
    email VARCHAR(128) DEFAULT 'UNSET' NOT NULL,
    device BIGINT(44) DEFAULT NULL,
    loaner BIGINT(44) DEFAULT NULL
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

CREATE TRIGGER devices_insert
BEFORE INSERT ON devices
FOR EACH ROW
BEGIN
    -- Check if there's a student record where device_asset matches deviceID
    IF EXISTS (SELECT 1 FROM students WHERE device_asset = NEW.deviceID) THEN
        SET NEW.assignment = 'STUDENT';
    
    -- Check if there's a student record where loaner_asset matches deviceID
    ELSEIF EXISTS (SELECT 1 FROM students WHERE loaner_asset = NEW.deviceID) THEN
        SET NEW.assignment = 'LOANER';
    
    -- If no match, set assignment to NULL
    ELSE
        SET NEW.assignment = NULL;
    END IF;
END$$

DELIMITER ;



DROP EVENT IF EXISTS updates$$
DROP PROCEDURE IF EXISTS updates$$
DROP EVENT IF EXISTS updates;