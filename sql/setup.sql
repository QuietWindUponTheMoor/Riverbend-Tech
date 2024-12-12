-- Set up
CREATE DATABASE IF NOT EXISTS ChromebookCheckouts;
USE ChromebookCheckouts;
SET GLOBAL event_scheduler = ON;
SHOW VARIABLES LIKE 'event_scheduler';


-- Schedules
CREATE EVENT update_loaners_assignment
ON SCHEDULE EVERY 10 MINUTE
DO
UPDATE loaners l
JOIN (
    SELECT loanerCB, studentID
    FROM checkouts c1
    WHERE c1.recordID = (
        SELECT MAX(c2.recordID)
        FROM checkouts c2
        WHERE c2.loanerCB = c1.loanerCB
    )
) c ON l.loaner = c.loanerCB
SET l.assignment = c.studentID;

DELIMITER //
CREATE EVENT deprovision_checks
ON SCHEDULE EVERY 30 MINUTE
DO
BEGIN
    -- Insert records with 'DONOR' assignment into cbinventory_deprovisioned
    INSERT INTO cbinventory_deprovisioned
    SELECT * 
    FROM cbinventory
    WHERE assignment = 'DONOR';

    -- Delete records with 'DONOR' assignment from cbinventory
    DELETE FROM cbinventory
    WHERE assignment = 'DONOR';
END //
DELIMITER ;

-- Tables
CREATE TABLE IF NOT EXISTS checkouts (
  recordID bigint(44) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  studentID bigint(44) NOT NULL,
  assignedCB varchar(20) NOT NULL,
  loanerCB varchar(20) NOT NULL,
  school varchar(4) NOT NULL,
  grade int(2) NOT NULL,
  issue varchar(1200) NOT NULL
);
ALTER TABLE checkouts ADD COLUMN (
    started BOOLEAN DEFAULT 0,
    finished BOOLEAN DEFAULT 0,
    softDeleted BOOLEAN DEFAULT 0
);

CREATE TABLE IF NOT EXISTS checkouts_del (
  recordID bigint(44) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  studentID bigint(44) NOT NULL,
  assignedCB varchar(20) NOT NULL,
  loanerCB varchar(20) NOT NULL,
  school varchar(4) NOT NULL,
  grade int(2) NOT NULL,
  issue varchar(1200) NOT NULL
);
ALTER TABLE checkouts_del ADD COLUMN (
    started BOOLEAN DEFAULT 0,
    finished BOOLEAN DEFAULT 0,
    softDeleted BOOLEAN DEFAULT 0
);

CREATE TABLE IF NOT EXISTS loaners (
    loaner VARCHAR(25) PRIMARY KEY NOT NULL,
    serial VARCHAR(15) DEFAULT NULL,
    assignment VARCHAR(128) DEFAULT "SPARE" NOT NULL
);

CREATE TABLE IF NOT EXISTS loaners_del (
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

CREATE TABLE IF NOT EXISTS cbinventory_deprovisioned (
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

CREATE TABLE IF NOT EXISTS dropped_students (
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