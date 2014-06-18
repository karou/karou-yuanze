-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 26, 2013 at 07:35 AM
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
-- Table structure for table `bu`
--

CREATE TABLE IF NOT EXISTS `bu` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_uniq` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_name_uniq` (`client_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=884 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Bu_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_uniq` (`code`),
  KEY `IDX_5373C96621F0B0B8` (`Bu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Table structure for table `date`
--

CREATE TABLE IF NOT EXISTS `date` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `daily_date` date NOT NULL,
  `bu_id` bigint(20) DEFAULT NULL,
  `forecast` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_date_bu_id_uniq` (`daily_date`,`bu_id`),
  KEY `IDX_AA9E377AE0319FBC` (`bu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `myorder` bigint(20) NOT NULL,
  `ace_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ace_id_uniq` (`ace_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `parameters` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_uniq` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `permissiongroup_user`
--

CREATE TABLE IF NOT EXISTS `permissiongroup_user` (
  `permissiongroup_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`permissiongroup_id`,`user_id`),
  KEY `IDX_CBA20248BD0DD96D` (`permissiongroup_id`),
  KEY `IDX_CBA20248A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_group`
--

CREATE TABLE IF NOT EXISTS `permission_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `parameters` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_uniq` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `permission_permissiongroup`
--

CREATE TABLE IF NOT EXISTS `permission_permissiongroup` (
  `permission_id` bigint(20) NOT NULL,
  `permissiongroup_id` bigint(20) NOT NULL,
  PRIMARY KEY (`permission_id`,`permissiongroup_id`),
  KEY `IDX_40944DAAFED90CCA` (`permission_id`),
  KEY `IDX_40944DAABD0DD96D` (`permissiongroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number` bigint(20) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `ace_id` bigint(20) NOT NULL,
  `percent` double NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ace_id_uniq` (`ace_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4240 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `resume` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number` bigint(20) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percentage_done` bigint(20) NOT NULL,
  `actual_percentage_done` double NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `ace_id` bigint(20) NOT NULL,
  `task_type_id` bigint(20) DEFAULT NULL,
  `aol_percent` bigint(20) DEFAULT NULL,
  `status_id` bigint(20) NOT NULL,
  `updated_aol` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ace_id_uniq` (`ace_id`),
  KEY `IDX_527EDB25166D1F9C` (`project_id`),
  KEY `IDX_527EDB25FE54D947` (`group_id`),
  KEY `IDX_527EDB25DAADA679` (`task_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=209369 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_backup`
--

CREATE TABLE IF NOT EXISTS `task_backup` (
  `id` bigint(20) NOT NULL DEFAULT '0',
  `project_id` bigint(20) DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `resume` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `number` bigint(20) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `percentage_done` bigint(20) NOT NULL,
  `actual_percentage_done` double NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `ace_id` bigint(20) NOT NULL,
  `task_type_id` bigint(20) DEFAULT NULL,
  `aol_percent` bigint(20) DEFAULT NULL,
  `status_id` bigint(20) NOT NULL,
  `updated_aol` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `task_type`
--

CREATE TABLE IF NOT EXISTS `task_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `myorder` bigint(20) NOT NULL,
  `ace_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ace_id_uniq` (`ace_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `pic` longtext COLLATE utf8_unicode_ci,
  `aol_username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `aol_password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ace_username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `ace_password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_uniq` (`username`),
  UNIQUE KEY `email_uniq` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_permissiongroup`
--

CREATE TABLE IF NOT EXISTS `user_permissiongroup` (
  `user_id` int(11) NOT NULL,
  `permissiongroup_id` bigint(20) NOT NULL,
  PRIMARY KEY (`user_id`,`permissiongroup_id`),
  KEY `IDX_73F7C3F1A76ED395` (`user_id`),
  KEY `IDX_73F7C3F1BD0DD96D` (`permissiongroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `country`
--
ALTER TABLE `country`
  ADD CONSTRAINT `FK_5373C96621F0B0B8` FOREIGN KEY (`Bu_id`) REFERENCES `bu` (`id`);

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

--
-- Constraints for table `permissiongroup_user`
--
ALTER TABLE `permissiongroup_user`
  ADD CONSTRAINT `FK_CBA20248A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CBA20248BD0DD96D` FOREIGN KEY (`permissiongroup_id`) REFERENCES `permission_group` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_permissiongroup`
--
ALTER TABLE `permission_permissiongroup`
  ADD CONSTRAINT `FK_40944DAABD0DD96D` FOREIGN KEY (`permissiongroup_id`) REFERENCES `permission_group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_40944DAAFED90CCA` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_527EDB25166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_527EDB25DAADA679` FOREIGN KEY (`task_type_id`) REFERENCES `task_type` (`id`),
  ADD CONSTRAINT `FK_527EDB25FE54D947` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`);

--
-- Constraints for table `user_permissiongroup`
--
ALTER TABLE `user_permissiongroup`
  ADD CONSTRAINT `FK_73F7C3F1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_73F7C3F1BD0DD96D` FOREIGN KEY (`permissiongroup_id`) REFERENCES `permission_group` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
