-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2015 at 05:11 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `antiquitiestwo`
--

-- --------------------------------------------------------

--
-- Table structure for table `coinsummary`
--

CREATE TABLE IF NOT EXISTS `coinsummary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hoardID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` smallint(6) unsigned DEFAULT NULL,
  `broadperiod` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `denomination` int(4) unsigned DEFAULT NULL,
  `geographyID` int(10) unsigned DEFAULT NULL,
  `ruler_id` int(11) unsigned DEFAULT NULL,
  `mint_id` int(11) unsigned DEFAULT NULL,
  `numdate1` int(11) DEFAULT NULL,
  `numdate2` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(10) unsigned DEFAULT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hoardID` (`hoardID`),
  KEY `broadperiod` (`broadperiod`),
  KEY `denomination` (`denomination`),
  KEY `numdate1` (`numdate1`),
  KEY `numdate2` (`numdate2`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Coin summaries for hoards' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `coinsummary`
--

INSERT INTO `coinsummary` (`id`, `hoardID`, `secuid`, `quantity`, `broadperiod`, `denomination`, `geographyID`, `ruler_id`, `mint_id`, `numdate1`, `numdate2`, `created`, `createdBy`, `updated`, `updatedBy`, `institution`) VALUES
(1, 'PAS5385ED82001FDA', 'PAS5385ED82001FD1', 10, 'ROMAN', 28, NULL, 118, 137, 11, 23, '2000-10-31 00:00:00', 54, '2000-10-31 00:00:00', 54, 'NMS'),
(2, 'PAS5385ED82001FDA', 'PAS5385ED82001FD2', 16, 'ROMAN', 27, NULL, 90, 224, 12, 25, '2000-10-31 00:00:00', 54, '2000-10-31 00:00:00', 54, 'NMS'),
(3, 'PAS5385ED82001FDA', 'PAS5385ED82001FD3', 2, 'IRON AGE', 2, 2, NULL, NULL, -125, -50, '2000-10-31 00:00:00', 54, '2000-10-31 00:00:00', 54, 'NMS'),
(4, 'PAS5429632A001643', 'PAS544FC5FE001DC8', 12, 'ROMAN', 180, NULL, 259, 196, 45, 55, '2014-10-28 16:36:14', 4059, NULL, NULL, NULL),
(5, 'PAS5458A1F7001CE9', 'PAS5458A23C00168E', 1, 'IRON AGE', 189, 3, 12, 42, NULL, NULL, '2014-11-04 09:54:04', 4059, NULL, NULL, NULL),
(6, 'PAS5385ED82001FDA', 'PAS5460A9A50015F7', 12, 'ROMAN', 185, NULL, 265, 224, 45, 34, '2014-11-10 12:03:49', 4059, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
