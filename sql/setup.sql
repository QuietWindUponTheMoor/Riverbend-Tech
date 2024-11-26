-- Set up
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

CREATE TABLE IF NOT EXISTS loaners (
    loaner VARCHAR(25) PRIMARY KEY NOT NULL,
    serial VARCHAR(15) DEFAULT NULL,
    assignment VARCHAR(128) DEFAULT "SPARE" NOT NULL
);