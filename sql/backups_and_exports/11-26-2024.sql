-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 09:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chromebookcheckouts`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

CREATE TABLE `checkouts` (
  `recordID` bigint(44) NOT NULL,
  `studentID` bigint(44) NOT NULL,
  `assignedCB` varchar(20) NOT NULL,
  `loanerCB` varchar(20) NOT NULL,
  `school` varchar(4) NOT NULL,
  `grade` int(2) NOT NULL,
  `issue` varchar(1200) NOT NULL,
  `started` tinyint(1) DEFAULT 0,
  `finished` tinyint(1) DEFAULT 0,
  `softDeleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkouts`
--

INSERT INTO `checkouts` (`recordID`, `studentID`, `assignedCB`, `loanerCB`, `school`, `grade`, `issue`, `started`, `finished`, `softDeleted`) VALUES
(1, 2031035, '99HP21', '325HP19', 'RBMS', 6, 'Won\'t turn on', 1, 1, 0),
(2, 2029084, '44HP21', 'N/A', 'RBMS', 8, 'Display cracked, and missing hinge cover', 0, 0, 0),
(3, 2029042, '89HP21', 'N/A', 'RBMS', 8, 'Cracked display', 0, 0, 0),
(4, 2031080, 'E505HP21', '320HP19', 'RBMS', 6, 'Cracked screen, shut Chromebook on earbuds', 0, 1, 0),
(5, 2034076, '152', '380HP19', 'FES', 3, 'Completely dead, needs a new motherboard', 0, 0, 0),
(6, 2034100, '144', '07HPT19', 'FES', 3, 'Refuses to charge with any charger', 0, 0, 0),
(7, 2033094, '52', 'E503HP21', 'FES', 4, 'Was sent home temporarily while the student was gone for a week, and was lost. Refer to email https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox/FMfcgzQXJGhGKcctTdDVCKxgtqpWJqKk', 0, 0, 0),
(8, 2029024, '54HP21', '83HP21', 'RBMS', 8, 'Claims he shut his Chromebook without realizing someone else put a half-broken pencil on his keyboard, so it smashed his screen', 0, 0, 0),
(9, 2030010, '247HP21', '80HP21', 'RBMS', 7, 'Need to order display cable', 0, 0, 0),
(10, 2030036, '107HP21', '188HP21', 'RBMS', 7, 'Won\'t turn on', 0, 0, 0),
(11, 2031024, '324HP19', '72HP21', 'RBMS', 6, 'Screen cracked', 0, 0, 0),
(12, 2029016, '136HP21', '148HP21', 'RBMS', 8, 'Cracked display, accidentally hit it off desk', 0, 0, 0),
(13, 2031034, '29HPT19', '122HP21', 'RBMS', 6, 'Cracked display - Left headphones on keyboard', 0, 0, 0),
(14, 2031094, '26HPG720', '154HP21', 'RBMS', 6, 'Won\'t charge', 0, 0, 0),
(15, 2029062, '151HP21', '188HP21', 'RBMS', 8, 'Won\'t turn on', 0, 0, 0),
(28, 2031019, '203HP21', '219HP21', 'RBMS', 6, 'Cracked display', 0, 0, 0),
(29, 2030123, '42HP21', '33HP21', 'RBMS', 7, 'Cracked display', 0, 0, 0),
(30, 2031111, '95HP21', '164HP21', 'RBMS', 6, 'Cracked display, keyboard, and hinge', 0, 0, 0),
(31, 2030010, '247HP21', '50HPT19', 'RBMS', 7, 'Won\'t charge properly?', 0, 0, 0),
(32, 2035112, '23HPTG9-0535-23', 'N/A', 'FES', 2, 'Enter later', 0, 0, 0),
(33, 2031029, '53HPT19', '176HP21', 'RBMS', 6, 'Won\'t turn on and doesn\'t detect when plugged in to charge', 0, 0, 0),
(34, 2031111, '164HP21', '341HP19', 'RBMS', 6, 'Broke the display again. 2nd Offense', 0, 0, 0),
(35, 2031132, '124HP21', '80HP21', 'RBMS', 6, 'Display cable bad', 0, 0, 0),
(36, 2031143, 'Covered - 5CD937BV8H', 'E507HP19', 'RBMS', 6, 'Display is cracked', 0, 0, 0),
(37, 2030063, '216HP21', '176HP21', 'RBMS', 7, 'Display is broken', 0, 0, 0),
(38, 2029122, '41HP21', '05HP21', 'RBMS', 8, 'Cracked display', 0, 0, 0),
(39, 2031143, 'N/A', '107HP21', 'RBMS', 6, 'Assigned Loaner', 0, 0, 0),
(40, 2037036, '23HPTG9-0490-23', '23HPTG9-0489-23', 'FES', 0, 'Object stuck inside aux port, needs remove or motherboard replaced', 0, 0, 0),
(41, 2030002, '174HP21', '07HP21', 'RBMS', 7, 'Cracked display and missing hinge cover', 0, 0, 0),
(42, 2029036, '37HP21', '320HP19', 'RBMS', 8, 'Camera not working', 0, 0, 0),
(43, 2029134, '162HP21', '244HP21', 'RBMS', 8, 'Camera not working', 0, 0, 0),
(44, 2029135, '155HP21', '154HP21', 'RBMS', 8, 'Won\'t power wash and camera doesn\'t work', 0, 0, 0),
(45, 2029088, '82HP21', '72HP21', 'RBMS', 8, 'Camera not working', 0, 0, 0),
(46, 2029110, '140HP21', '33HP21', 'RBMS', 8, 'Water spilled on by another student, keyboard and touchbad barely responsive', 0, 0, 0),
(47, 2029058, '46HP21', '331HP19', 'RBMS', 8, 'Had his Chromebook in his case, it was stepped on in the bleachers and is completely destroyed.', 0, 0, 0),
(48, 2031029, '53HPT19', '164HP21', 'RBMS', 6, 'Screen is cracked, claims he doesn\'t know how it happened.', 0, 0, 0),
(49, 2029101, '25HP21', '50HPT19', 'RBMS', 8, 'Repeatedly stops in because the screen won\'t turn on. Sending in for repair.', 0, 0, 0),
(50, 2030056, '165HP21', '176HP21', 'RBMS', 7, 'Overheating badly', 0, 0, 0),
(51, 2030031, '196HP21', '97HP21', 'RBMS', 7, 'Keeps either not turning on or saying ChromeOS is missing or damaged', 0, 0, 0),
(52, 2030003, '157HP21', '80HP21', 'RBMS', 7, 'Display is cracked', 0, 0, 0),
(53, 2029001, '163HP21', 'E503HP21', 'RBMS', 8, 'ChromeOS is missing or damaged, tried to powerwash with no success.', 0, 0, 0),
(54, 2029069, '31HP21', '08HPG720', 'RBMS', 8, 'Cracked display', 0, 0, 0),
(55, 2029016, '30HP21', '54HPG720', 'RBMS', 8, 'Cracked display', 0, 0, 0),
(56, 2034076, '152', '49HPT19', 'FES', 3, 'Won\'t turn on', 0, 0, 0),
(57, 2029001, '163HP21', '45HPG720', 'RBMS', 8, 'Was originally given loaner E503HP21, until it started freaking out', 0, 0, 0),
(58, 2031047, '150HP21', '95HP21', 'RBMS', 6, 'Keeps either not turning on or saying ChromeOS is missing or damaged', 0, 0, 0),
(59, 2029003, '86HP21', '162HP21', 'RBMS', 8, 'Sent home a loaner temporarily while he is sick.', 0, 0, 0),
(60, 2031132, '124HP21', '219HP21', 'RBMS', 6, 'Broken display', 0, 0, 0),
(61, 2030069, '236HP21', '17HP21', 'RBMS', 7, 'Broken display', 0, 0, 0),
(62, 2030056, '165HP21', '176HP21', 'RBMS', 7, 'Overheating badly & occasionally will not turn on because of it.', 0, 0, 0),
(63, 2030010, '247HP21', '137HP21', 'RBMS', 7, 'Was assigned this loaner and immediately broke the display', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `checkouts_del`
--

CREATE TABLE `checkouts_del` (
  `recordID` bigint(44) NOT NULL,
  `studentID` bigint(44) NOT NULL,
  `assignedCB` varchar(20) NOT NULL,
  `loanerCB` varchar(20) NOT NULL,
  `school` varchar(4) NOT NULL,
  `grade` int(2) NOT NULL,
  `issue` varchar(1200) NOT NULL,
  `started` tinyint(1) DEFAULT 0,
  `finished` tinyint(1) DEFAULT 0,
  `softDeleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loaners`
--

CREATE TABLE `loaners` (
  `loaner` varchar(25) NOT NULL,
  `serial` varchar(15) DEFAULT NULL,
  `assignment` varchar(128) NOT NULL DEFAULT 'SPARE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaners`
--

INSERT INTO `loaners` (`loaner`, `serial`, `assignment`) VALUES
('05HP21', '5CD113BZQ8', '2029122'),
('07HP21', '5CD104BZNP', '2030002'),
('07HPT19', NULL, '2034100'),
('08HPG720', '5CD937BSFR', '2029069'),
('107HP21', '5CD113F535', '2031143'),
('122HP21', NULL, '2031034'),
('13HP21', '5CD104BZM7', 'GETTING FIXED'),
('148HP21', NULL, '2029016'),
('154HP21', NULL, '2029135'),
('164HP21', NULL, '2031029'),
('16HPG720', '5CD937BSES', 'SPARE'),
('176HP21', NULL, '2030056'),
('188HP21', NULL, '2029062'),
('219HP21', '5CD113F2XN', '2031132'),
('244HP21', '5CD113F53P', '2029134'),
('28HPG720', '5CD937BSG2', 'SPARE'),
('30HP21', '5CD113F2RL', '2039016 - PERMANENT'),
('320HP19', '5CD9236W91', '2029036'),
('325HP19', NULL, '2031035'),
('331HP19', '5CD9220S7M', '2029058'),
('33HP21', '5CD113F48L', '2029110'),
('341HP19 ( staff assigned)', '5CD9236WLG', 'Kayleigh Bonneur'),
('380HP19', NULL, '2034076'),
('45hpg720', '5CD937BSF1', '2029001'),
('50HPT19', NULL, '2029101'),
('54HPG720', '5CD937B SC7', '2029016'),
('58HPT19', '5CD92471MR', 'SPARE'),
('72HP21', '5CD113F2YM', '2029088'),
('80HP21', NULL, '2030003'),
('83HP21', NULL, '2029024'),
('E503HP21', '5CD9237PYC', '2029001'),
('E507HP19', '5CD9236W7P', '2031143');

-- --------------------------------------------------------

--
-- Table structure for table `loaners_del`
--

CREATE TABLE `loaners_del` (
  `loaner` varchar(25) NOT NULL,
  `serial` varchar(15) DEFAULT NULL,
  `assignment` varchar(128) NOT NULL DEFAULT 'SPARE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`recordID`);

--
-- Indexes for table `checkouts_del`
--
ALTER TABLE `checkouts_del`
  ADD PRIMARY KEY (`recordID`);

--
-- Indexes for table `loaners`
--
ALTER TABLE `loaners`
  ADD PRIMARY KEY (`loaner`);

--
-- Indexes for table `loaners_del`
--
ALTER TABLE `loaners_del`
  ADD PRIMARY KEY (`loaner`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `recordID` bigint(44) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `checkouts_del`
--
ALTER TABLE `checkouts_del`
  MODIFY `recordID` bigint(44) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_loaners_assignment` ON SCHEDULE EVERY 10 MINUTE STARTS '2024-11-26 09:24:04' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE loaners l
JOIN (
    SELECT loanerCB, studentID
    FROM checkouts c1
    WHERE c1.recordID = (
        SELECT MAX(c2.recordID)
        FROM checkouts c2
        WHERE c2.loanerCB = c1.loanerCB
    )
) c ON l.loaner = c.loanerCB
SET l.assignment = c.studentID$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
