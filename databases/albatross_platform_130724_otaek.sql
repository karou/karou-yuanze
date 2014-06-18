-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 24, 2013 at 07:57 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `albatross_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `date`
--

CREATE TABLE IF NOT EXISTS `date` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `daily_date` date NOT NULL,
  `bu_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_date_bu_id_uniq` (`daily_date`,`bu_id`),
  KEY `IDX_AA9E377AE0319FBC` (`bu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `date`
--

INSERT INTO `date` (`id`, `daily_date`, `bu_id`) VALUES
(1, '2013-07-15', 1),
(2, '2013-07-16', 2),
(3, '2013-07-17', 3),
(4, '2013-07-18', 4),
(11, '2013-07-19', 2),
(5, '2013-07-19', 5),
(12, '2013-07-20', 2),
(13, '2013-07-20', 5),
(6, '2013-07-20', 6),
(7, '2013-07-21', 7),
(8, '2013-07-22', 8),
(10, '2013-07-23', 9),
(9, '2013-07-26', 10);

-- --------------------------------------------------------

--
-- Table structure for table `number`
--

CREATE TABLE IF NOT EXISTS `number` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_id` bigint(20) DEFAULT NULL,
  `status_id` bigint(20) DEFAULT NULL,
  `number` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date_status_uniq` (`date_id`,`status_id`),
  KEY `IDX_96901F54B897366B` (`date_id`),
  KEY `IDX_96901F546BF700BD` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;

--
-- Dumping data for table `number`
--

INSERT INTO `number` (`id`, `date_id`, `status_id`, `number`) VALUES
(1, 1, 1, 3),
(2, 1, 2, 5),
(3, 1, 13, 5),
(4, 1, 14, 5),
(5, 1, 3, 3),
(6, 1, 4, 5),
(7, 1, 5, 5),
(8, 1, 6, 5),
(9, 1, 7, 5),
(10, 1, 8, 5),
(11, 1, 16, 3),
(12, 2, 2, 4),
(13, 2, 3, 4),
(14, 2, 5, 4),
(15, 6, 4, 4),
(16, 2, 6, 4),
(17, 2, 7, 4),
(18, 2, 15, 4),
(19, 3, 4, 11),
(20, 3, 2, 11),
(21, 3, 1, 11),
(22, 3, 3, 11),
(23, 3, 9, 33),
(24, 4, 13, 20),
(25, 4, 14, 21),
(26, 7, 7, 70),
(27, 8, 11, 89),
(28, 9, 12, 67),
(29, 5, 13, 59),
(30, 6, 11, 2),
(31, 10, 14, 44),
(32, 11, 11, 888),
(33, 12, 12, 555),
(34, 13, 13, 666),
(35, 11, 12, 435),
(36, 11, 14, 0),
(37, 11, 15, 454),
(38, 12, 14, 455);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `editable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_uniq` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status`, `editable`) VALUES
(1, 'Submitted surveys', 0),
(2, 'Assigned surveys', 0),
(3, 'Delayed surveys (> 2 days)', 0),
(4, 'Declined surveys', 0),
(5, 'Pending validation', 0),
(6, 'Pending validation by Submission Time (> 4 days)', 0),
(7, 'Pending validation by Visit Time (> 4 days)', 0),
(8, 'Pending QC', 0),
(9, 'RFA opened', 0),
(10, 'Open opportunity', 0),
(11, 'Survey Validated Number', 1),
(12, 'QC Done', 1),
(13, 'Data Integrity Check Done', 1),
(14, 'LS Translation Done', 1),
(15, 'Report Done', 1),
(16, 'Invalid Survey Number', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `date`
--
ALTER TABLE `date`
  ADD CONSTRAINT `FK_AA9E377AE0319FBC` FOREIGN KEY (`bu_id`) REFERENCES `bu` (`id`);

--
-- Constraints for table `number`
--
ALTER TABLE `number`
  ADD CONSTRAINT `FK_96901F546BF700BD` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `FK_96901F54B897366B` FOREIGN KEY (`date_id`) REFERENCES `date` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
