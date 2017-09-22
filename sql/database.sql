-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 22, 2017 at 11:55 AM
-- Server version: 5.5.34-0ubuntu0.13.04.1-log
-- PHP Version: 5.4.9-4ubuntu2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `antiquitiesThree`
--

-- --------------------------------------------------------

--
-- Table structure for table `abbreviations`
--

CREATE TABLE IF NOT EXISTS `abbreviations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expanded` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Abbreviations in use on the database' AUTO_INCREMENT=50 ;

-- --------------------------------------------------------

--
-- Table structure for table `abcNumbers`
--

CREATE TABLE IF NOT EXISTS `abcNumbers` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `term` int(5) NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(6) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `term` (`term`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ABC Chris Rudd Numbers' AUTO_INCREMENT=65538 ;

-- --------------------------------------------------------

--
-- Table structure for table `accreditedMuseums`
--

CREATE TABLE IF NOT EXISTS `accreditedMuseums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `museumName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accreditedNumber` int(11) DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `geohash` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accreditedNumber` (`accreditedNumber`),
  KEY `woeid` (`woeid`),
  KEY `geohash` (`geohash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='A list of accredited museums that can bid for Treasure' AUTO_INCREMENT=1764 ;

-- --------------------------------------------------------

--
-- Table structure for table `accreditedRegions`
--

CREATE TABLE IF NOT EXISTS `accreditedRegions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regionName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Accredited Museum regions' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `accreditedStatus`
--

CREATE TABLE IF NOT EXISTS `accreditedStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Accredited Museum Status' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `agreedTreasureValuations`
--

CREATE TABLE IF NOT EXISTS `agreedTreasureValuations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `value` int(12) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `dateOfValuation` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Valuations for Treasure cases' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `allentypes`
--

CREATE TABLE IF NOT EXISTS `allentypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT '56',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Allen Types for Iron Age coins' AUTO_INCREMENT=374 ;

-- --------------------------------------------------------

--
-- Table structure for table `approveReject`
--

CREATE TABLE IF NOT EXISTS `approveReject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Approved','Rejected') COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Approved and rejected accounts' AUTO_INCREMENT=361 ;

-- --------------------------------------------------------

--
-- Table structure for table `archaeology`
--

CREATE TABLE IF NOT EXISTS `archaeology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hoardID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `knownsite` tinyint(1) DEFAULT NULL,
  `excavated` tinyint(1) DEFAULT NULL,
  `sitecontext` smallint(6) unsigned DEFAULT NULL,
  `broadperiod` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period1` tinyint(2) unsigned DEFAULT NULL,
  `subperiod1` tinyint(1) unsigned DEFAULT NULL,
  `period2` tinyint(2) unsigned DEFAULT NULL,
  `subperiod2` tinyint(1) unsigned DEFAULT NULL,
  `sitedateyear1` int(11) DEFAULT NULL,
  `sitedateyear2` int(11) DEFAULT NULL,
  `sitetype` smallint(6) unsigned DEFAULT NULL,
  `feature` smallint(6) unsigned DEFAULT NULL,
  `featuredateyear1` int(11) DEFAULT NULL,
  `featuredateyear2` int(11) DEFAULT NULL,
  `landscapetopography` smallint(6) unsigned DEFAULT NULL,
  `recmethod` smallint(6) unsigned DEFAULT NULL,
  `yearexc1` int(11) DEFAULT NULL,
  `yearexc2` int(11) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `contextualrating` int(1) unsigned DEFAULT NULL,
  `archiveloc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Archaeological context information for hoards' AUTO_INCREMENT=1274 ;

-- --------------------------------------------------------

--
-- Table structure for table `archaeologyaudit`
--

CREATE TABLE IF NOT EXISTS `archaeologyaudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `coinID` (`recordID`),
  KEY `findID` (`entityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1193 ;

-- --------------------------------------------------------

--
-- Table structure for table `archfeature`
--

CREATE TABLE IF NOT EXISTS `archfeature` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `monTypeEH` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feature` (`feature`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Archaeological features for hoards (archaeological context information)' AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `archsiteclass`
--

CREATE TABLE IF NOT EXISTS `archsiteclass` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `siteclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `siteclass` (`siteclass`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Classes of archaeological site for hoards (archaeological context information)' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `archsitetype`
--

CREATE TABLE IF NOT EXISTS `archsitetype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sitetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `monTypeEH` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sitetype` (`sitetype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of archaeological site for hoards (archaeological context information)' AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `bibliography`
--

CREATE TABLE IF NOT EXISTS `bibliography` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_publicationID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `findID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pages_plates` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vol_no` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pubID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pubID` (`pubID`),
  KEY `findID` (`findID`),
  KEY `secuid` (`secuid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=120163 ;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE IF NOT EXISTS `bookmarks` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Social Bookmarks' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `categoriescoins`
--

CREATE TABLE IF NOT EXISTS `categoriescoins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `periodID` int(10) unsigned DEFAULT NULL,
  `valid` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `periodID` (`periodID`),
  KEY `valid` (`valid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Categories for medieval coins' AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Table structure for table `cciVa`
--

CREATE TABLE IF NOT EXISTS `cciVa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cciNumber` varchar(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `va_type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cciNumber` (`cciNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32409 ;

-- --------------------------------------------------------

--
-- Table structure for table `certaintytypes`
--

CREATE TABLE IF NOT EXISTS `certaintytypes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(11) unsigned DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `classesJettonGroups`
--

CREATE TABLE IF NOT EXISTS `classesJettonGroups` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `classID` int(2) DEFAULT NULL,
  `groupID` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classID` (`classID`),
  KEY `groupID` (`groupID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Link table for jetton groups and classes' AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Table structure for table `coinclassifications`
--

CREATE TABLE IF NOT EXISTS `coinclassifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` tinyint(2) DEFAULT NULL,
  `referenceName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `valid` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Roman & iron age coins classifications' AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `coincountry_origin`
--

CREATE TABLE IF NOT EXISTS `coincountry_origin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Countries of origin for coin groups' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `coins`
--

CREATE TABLE IF NOT EXISTS `coins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `findID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geographyID` int(10) unsigned DEFAULT NULL,
  `geography_qualifier` tinyint(1) DEFAULT NULL,
  `greekstateID` int(10) unsigned DEFAULT NULL,
  `ruler_id` int(11) unsigned DEFAULT NULL,
  `ruler2_id` int(10) unsigned DEFAULT NULL,
  `ruler2_qualifier` tinyint(1) DEFAULT NULL,
  `tribe` int(3) DEFAULT NULL,
  `tribe_qualifier` tinyint(1) DEFAULT NULL,
  `ruler_qualifier` tinyint(10) unsigned DEFAULT NULL,
  `denomination` int(4) unsigned DEFAULT NULL,
  `denomination_qualifier` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mint_id` int(11) unsigned DEFAULT NULL,
  `mint_qualifier` tinyint(10) unsigned DEFAULT NULL,
  `categoryID` int(10) unsigned DEFAULT NULL,
  `jettonClass` int(11) DEFAULT NULL,
  `jettonGroup` int(11) DEFAULT NULL,
  `jettonType` int(11) DEFAULT NULL,
  `typeID` int(10) unsigned DEFAULT NULL,
  `type` text COLLATE utf8_unicode_ci,
  `status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_qualifier` tinyint(10) unsigned DEFAULT NULL,
  `moneyer` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reeceID` int(10) unsigned DEFAULT NULL,
  `rrcID` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ricID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priceID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `obverse_description` text COLLATE utf8_unicode_ci,
  `obverse_inscription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `initial_mark` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reverse_description` text COLLATE utf8_unicode_ci,
  `reverse_inscription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reverse_mintmark` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `revtypeID` int(5) DEFAULT NULL,
  `revTypeID_qualifier` tinyint(1) DEFAULT NULL,
  `degree_of_wear` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `die_axis_measurement` tinyint(2) unsigned DEFAULT NULL,
  `die_axis_certainty` tinyint(4) unsigned DEFAULT NULL,
  `cciNumber` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pleiadesID` int(11) DEFAULT NULL,
  `allen_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mack_type` float DEFAULT NULL,
  `bmc_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rudd_type` float DEFAULT NULL,
  `va_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phase_date_1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phase_date_2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `context` text COLLATE utf8_unicode_ci,
  `depositionDate` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numChiab` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classification` float DEFAULT NULL,
  `volume` float DEFAULT NULL,
  `reference` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(10) unsigned DEFAULT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tribe` (`tribe`),
  KEY `revtypeID` (`revtypeID`),
  KEY `denomination` (`denomination`),
  KEY `ruler_id` (`ruler_id`),
  KEY `ruler2_id` (`ruler2_id`),
  KEY `reeceID` (`reeceID`),
  KEY `die_axis_measurement` (`die_axis_measurement`),
  KEY `categoryID` (`categoryID`),
  KEY `greekstateID` (`greekstateID`),
  KEY `geographyID` (`geographyID`),
  KEY `allen_type` (`allen_type`),
  KEY `mack_type` (`mack_type`),
  KEY `bmc_type` (`bmc_type`),
  KEY `rudd_type` (`rudd_type`),
  KEY `va_type` (`va_type`),
  KEY `typeID` (`typeID`),
  KEY `findID` (`findID`),
  KEY `mint_id` (`mint_id`),
  KEY `createdBy` (`createdBy`),
  KEY `moneyer` (`moneyer`),
  KEY `status` (`status`),
  KEY `cciNumber` (`cciNumber`),
  KEY `institution` (`institution`),
  KEY `pleiadesID` (`pleiadesID`),
  FULLTEXT KEY `reverse_description` (`reverse_description`),
  FULLTEXT KEY `obverse_inscription` (`obverse_inscription`),
  FULLTEXT KEY `obverse_description` (`obverse_description`),
  FULLTEXT KEY `reverse_inscription` (`reverse_inscription`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=419201 ;

-- --------------------------------------------------------

--
-- Table structure for table `coinsAudit`
--

CREATE TABLE IF NOT EXISTS `coinsAudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `coinID` (`recordID`),
  KEY `findID` (`entityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=290184 ;

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
  `moneyer` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Coin summaries for hoards' AUTO_INCREMENT=27494 ;

-- --------------------------------------------------------

--
-- Table structure for table `coins_denomxruler`
--

CREATE TABLE IF NOT EXISTS `coins_denomxruler` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `denomID` int(3) DEFAULT NULL,
  `rulerID` int(10) unsigned NOT NULL DEFAULT '0',
  `periodID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `denomID` (`denomID`),
  KEY `rulerID` (`rulerID`),
  KEY `periodID` (`periodID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Realtions between the Rulers and Denominations for coins' AUTO_INCREMENT=1731 ;

-- --------------------------------------------------------

--
-- Table structure for table `coins_rulers`
--

CREATE TABLE IF NOT EXISTS `coins_rulers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_period` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` int(11) DEFAULT NULL,
  `sortorder` int(11) DEFAULT NULL,
  `old_sortorder` int(11) DEFAULT NULL,
  `name` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `region` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_date1qual` char(255) CHARACTER SET latin1 DEFAULT NULL,
  `date1qual` smallint(6) DEFAULT NULL,
  `date1` smallint(6) DEFAULT NULL,
  `old_date2qual` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date2qual` smallint(6) DEFAULT NULL,
  `date2` smallint(6) DEFAULT NULL,
  `valid` smallint(6) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `last_udpated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `place` (`place`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1058 ;

-- --------------------------------------------------------

--
-- Table structure for table `coinxclass`
--

CREATE TABLE IF NOT EXISTS `coinxclass` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `findID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classID` int(10) unsigned NOT NULL DEFAULT '0',
  `vol_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` datetime DEFAULT '0000-00-00 00:00:00',
  `updatedBy` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `findID` (`findID`),
  KEY `classID` (`classID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16898 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contentID` int(11) DEFAULT NULL,
  `comment_author` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_author_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_author_url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_ip` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  `comment_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8_unicode_ci,
  `comment_approved` varchar(25) COLLATE utf8_unicode_ci DEFAULT 'moderation',
  `commentStatus` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_parent` bigint(20) DEFAULT '0',
  `user_id` bigint(20) DEFAULT '0',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_approved` (`comment_approved`),
  KEY `commentStatus` (`commentStatus`),
  KEY `createdBy` (`createdBy`),
  KEY `contentID` (`contentID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36122 ;

-- --------------------------------------------------------

--
-- Table structure for table `completeness`
--

CREATE TABLE IF NOT EXISTS `completeness` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menuTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8_unicode_ci,
  `body` text COLLATE utf8_unicode_ci,
  `section` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `frontPage` int(11) DEFAULT NULL,
  `publishState` int(1) DEFAULT NULL,
  `metaDescription` text COLLATE utf8_unicode_ci,
  `metaKeywords` text COLLATE utf8_unicode_ci,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `frontPage` (`frontPage`),
  KEY `author` (`author`),
  KEY `publishState` (`publishState`),
  KEY `slug` (`slug`),
  KEY `section` (`section`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Site content' AUTO_INCREMENT=268 ;

-- --------------------------------------------------------

--
-- Table structure for table `contentAudit`
--

CREATE TABLE IF NOT EXISTS `contentAudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=76410 ;

-- --------------------------------------------------------

--
-- Table structure for table `contentOld`
--

CREATE TABLE IF NOT EXISTS `contentOld` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menuTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8_unicode_ci,
  `body` text COLLATE utf8_unicode_ci,
  `section` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `frontPage` int(11) DEFAULT NULL,
  `publishState` int(1) DEFAULT NULL,
  `metaDescription` text COLLATE utf8_unicode_ci,
  `metaKeywords` text COLLATE utf8_unicode_ci,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `frontPage` (`frontPage`),
  KEY `author` (`author`),
  KEY `publishState` (`publishState`),
  KEY `slug` (`slug`),
  KEY `section` (`section`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Site content' AUTO_INCREMENT=242 ;

-- --------------------------------------------------------

--
-- Table structure for table `copyCoin`
--

CREATE TABLE IF NOT EXISTS `copyCoin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fields` text COLLATE utf8_unicode_ci,
  `userID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Copy last find ' AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `copyFind`
--

CREATE TABLE IF NOT EXISTS `copyFind` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fields` text COLLATE utf8_unicode_ci,
  `userID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Copy last find ' AUTO_INCREMENT=126 ;

-- --------------------------------------------------------

--
-- Table structure for table `copyFindSpot`
--

CREATE TABLE IF NOT EXISTS `copyFindSpot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fields` text COLLATE utf8_unicode_ci,
  `userID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Copy last find ' AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- Table structure for table `copyHoards`
--

CREATE TABLE IF NOT EXISTS `copyHoards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fields` text COLLATE utf8_unicode_ci,
  `userID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Copy last find ' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `copyrights`
--

CREATE TABLE IF NOT EXISTS `copyrights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `copyright` text COLLATE utf8_unicode_ci,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Image copyrights' AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `coroners`
--

CREATE TABLE IF NOT EXISTS `coroners` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(155) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(155) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(155) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regionID` int(3) DEFAULT NULL,
  `region_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(155) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `createdBy` int(3) DEFAULT '3',
  `created` datetime DEFAULT NULL,
  `updatedBy` int(3) DEFAULT '0',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `regionID` (`regionID`),
  KEY `woeid` (`woeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=128 ;

-- --------------------------------------------------------

--
-- Table structure for table `counties`
--

CREATE TABLE IF NOT EXISTS `counties` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `county` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regionID` int(10) unsigned DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `county` (`county`),
  KEY `valid` (`valid`),
  KEY `regionID` (`regionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `iso` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `printable_name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso3` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`iso`),
  KEY `printable_name` (`printable_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countyToFlo`
--

CREATE TABLE IF NOT EXISTS `countyToFlo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institutionID` int(11) NOT NULL,
  `countyID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `institutionID` (`institutionID`),
  KEY `countyID` (`countyID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='County to recording institutions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crimeTypes`
--

CREATE TABLE IF NOT EXISTS `crimeTypes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Crime typologies' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cultures`
--

CREATE TABLE IF NOT EXISTS `cultures` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `term` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmCultureID` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `periodo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','0') COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` int(3) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(3) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `dataquality`
--

CREATE TABLE IF NOT EXISTS `dataquality` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rating` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rating` (`rating`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Quality of the data in hoards' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `datequalifiers`
--

CREATE TABLE IF NOT EXISTS `datequalifiers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(11) unsigned DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `decmethods`
--

CREATE TABLE IF NOT EXISTS `decmethods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','2') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `decstyles`
--

CREATE TABLE IF NOT EXISTS `decstyles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','2') COLLATE utf8_unicode_ci DEFAULT '1',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `denominations`
--

CREATE TABLE IF NOT EXISTS `denominations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` int(10) unsigned DEFAULT NULL,
  `denomination` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomismaID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dbpediaID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rarity` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `weight` int(5) DEFAULT NULL,
  `diameter` int(5) DEFAULT NULL,
  `thickness` int(5) DEFAULT NULL,
  `design` text COLLATE utf8_unicode_ci,
  `obverse` text COLLATE utf8_unicode_ci,
  `notes` text COLLATE utf8_unicode_ci,
  `old_material` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `material` int(10) unsigned DEFAULT NULL,
  `valid` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `createdBy` int(10) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `denomination` (`denomination`),
  KEY `period` (`period`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1170 ;

-- --------------------------------------------------------

--
-- Table structure for table `denominations_rulers`
--

CREATE TABLE IF NOT EXISTS `denominations_rulers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `denomination_id` int(10) unsigned DEFAULT NULL,
  `ruler_id` int(10) unsigned DEFAULT NULL,
  `period_id` int(10) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT '56',
  PRIMARY KEY (`id`),
  KEY `ruler_id` (`ruler_id`),
  KEY `denomination_id` (`denomination_id`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Realtions between the Rulers and Denominations for coins' AUTO_INCREMENT=4626 ;

-- --------------------------------------------------------

--
-- Table structure for table `dieaxes`
--

CREATE TABLE IF NOT EXISTS `dieaxes` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `die_axis_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` int(1) DEFAULT NULL,
  `createdBy` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `discmethods`
--

CREATE TABLE IF NOT EXISTS `discmethods` (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` smallint(1) DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `method` (`method`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instock` tinyint(1) NOT NULL,
  `mimetype` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filesize` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Scheme pubications for download and reqest' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dynasties`
--

CREATE TABLE IF NOT EXISTS `dynasties` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `dynasty` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wikipedia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_from` int(4) NOT NULL DEFAULT '0',
  `date_to` int(4) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL,
  `createdBy` int(3) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedBy` int(3) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `wikipedia` (`wikipedia`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `edm`
--

CREATE TABLE IF NOT EXISTS `edm` (
  `id` int(11) NOT NULL,
  `member_id` int(10) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Edm signatories';

-- --------------------------------------------------------

--
-- Table structure for table `ehObjects`
--

CREATE TABLE IF NOT EXISTS `ehObjects` (
  `subject` int(6) NOT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`subject`),
  KEY `label` (`label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emperors`
--

CREATE TABLE IF NOT EXISTS `emperors` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reeceID` int(3) DEFAULT NULL,
  `pasID` int(11) DEFAULT NULL,
  `dbpedia` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `viaf` int(11) DEFAULT NULL,
  `nomismaID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_from` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_to` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `biography` text COLLATE utf8_unicode_ci,
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zoomfolder` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dynasty` int(2) DEFAULT NULL,
  `murdoch` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pasID` (`pasID`),
  KEY `date_from` (`date_from`),
  KEY `reeceID` (`reeceID`),
  KEY `dynasty` (`dynasty`),
  KEY `viaf` (`viaf`),
  KEY `nomismaID` (`nomismaID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=242 ;

-- --------------------------------------------------------

--
-- Table structure for table `errorreports`
--

CREATE TABLE IF NOT EXISTS `errorreports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_findID` int(11) DEFAULT NULL,
  `comment_subject` text COLLATE utf8_unicode_ci,
  `comment_author` tinytext COLLATE utf8_unicode_ci,
  `comment_author_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_author_url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL,
  `comment_date_gmt` datetime DEFAULT NULL,
  `comment_content` text COLLATE utf8_unicode_ci,
  `comment_karma` int(11) DEFAULT NULL,
  `comment_approved` enum('1','2','spam') COLLATE utf8_unicode_ci DEFAULT '1',
  `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_parent` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_approved` (`comment_approved`),
  KEY `comment_findID` (`comment_findID`),
  KEY `comment_findID_2` (`comment_findID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7357 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `eventTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `eventDescription` text COLLATE utf8_unicode_ci,
  `eventType` int(2) DEFAULT NULL,
  `eventLocation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `eventStartDate` date DEFAULT NULL,
  `eventStartTime` time DEFAULT NULL,
  `eventEndDate` date DEFAULT NULL,
  `eventEndTime` time DEFAULT NULL,
  `eventRegion` int(11) DEFAULT NULL,
  `accessLevel` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'public',
  `adultsAttend` int(11) DEFAULT NULL,
  `childrenAttend` int(11) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `organisation` varchar(55) CHARACTER SET latin1 DEFAULT 'PAS',
  PRIMARY KEY (`id`),
  KEY `organisation` (`organisation`),
  KEY `createdBy` (`createdBy`),
  KEY `eventRegion` (`eventRegion`),
  KEY `eventStartDate` (`eventStartDate`),
  KEY `eventEndDate` (`eventEndDate`),
  KEY `createdBy_2` (`createdBy`),
  KEY `eventType` (`eventType`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1229 ;

-- --------------------------------------------------------

--
-- Table structure for table `eventtypes`
--

CREATE TABLE IF NOT EXISTS `eventtypes` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of events' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `question` text COLLATE utf8_unicode_ci,
  `answer` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createdBy` int(3) DEFAULT '0',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `updatedBy` int(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `findofnotereasons`
--

CREATE TABLE IF NOT EXISTS `findofnotereasons` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` timestamp NULL DEFAULT NULL,
  `createdBy` int(5) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `finds`
--

CREATE TABLE IF NOT EXISTS `finds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hoardID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_findID` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_finderID` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finderID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finder2ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smr_ref` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_ref` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datefound1qual` tinyint(1) DEFAULT NULL,
  `datefound1` date DEFAULT NULL,
  `datefound1flag` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datefound2` date DEFAULT NULL,
  `datefound2flag` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datefound2qual` tinyint(1) DEFAULT NULL,
  `culture` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discmethod` tinyint(2) DEFAULT NULL,
  `disccircum` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `objecttype` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objecttypecert` tinyint(1) DEFAULT NULL,
  `old_candidate` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classification` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subclass` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inscription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objdate1cert` int(11) DEFAULT NULL,
  `objdate1subperiod_old` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objdate1period` tinyint(2) unsigned DEFAULT NULL,
  `objdate2cert` tinyint(1) DEFAULT NULL,
  `objdate2subperiod_old` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objdate2period` tinyint(2) unsigned DEFAULT NULL,
  `objdate1subperiod` tinyint(1) unsigned DEFAULT NULL,
  `objdate2subperiod` tinyint(1) unsigned DEFAULT NULL,
  `broadperiod` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numdate1qual` tinyint(1) DEFAULT NULL,
  `numdate1` int(11) DEFAULT NULL,
  `numdate2qual` tinyint(1) DEFAULT NULL,
  `numdate2` int(11) DEFAULT NULL,
  `material1` tinyint(2) unsigned DEFAULT NULL,
  `material2` tinyint(2) unsigned DEFAULT NULL,
  `manmethod` tinyint(2) DEFAULT NULL,
  `decmethod` tinyint(2) DEFAULT NULL,
  `surftreat` tinyint(2) DEFAULT NULL,
  `decstyle` tinyint(2) DEFAULT NULL,
  `wear` tinyint(2) DEFAULT NULL,
  `preservation` tinyint(2) DEFAULT NULL,
  `completeness` tinyint(2) DEFAULT NULL,
  `reuse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reuse_period` tinyint(2) DEFAULT NULL,
  `length` double unsigned DEFAULT NULL,
  `width` double unsigned DEFAULT NULL,
  `height` double unsigned DEFAULT NULL,
  `thickness` double unsigned DEFAULT NULL,
  `diameter` double unsigned DEFAULT NULL,
  `weight` double unsigned DEFAULT NULL,
  `quantity` smallint(6) unsigned DEFAULT NULL,
  `curr_loc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recorderID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identifier1ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identifier2ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smrrefno` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `musaccno` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subs_action` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sectag` int(11) unsigned DEFAULT NULL,
  `secowner` int(11) unsigned DEFAULT NULL,
  `secwfstage` tinyint(1) unsigned DEFAULT NULL,
  `findofnote` tinyint(3) DEFAULT NULL,
  `findofnotereason` tinyint(2) DEFAULT NULL,
  `treasure` tinyint(1) DEFAULT NULL,
  `treasureID` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rally` tinyint(1) DEFAULT NULL,
  `rallyID` int(11) DEFAULT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hoardcontainer` int(1) unsigned DEFAULT '0',
  `dbpediaSlug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `old_findID_2` (`old_findID`),
  UNIQUE KEY `old_findID_3` (`old_findID`),
  UNIQUE KEY `old_findID_4` (`old_findID`),
  UNIQUE KEY `old_findID_5` (`old_findID`),
  UNIQUE KEY `old_findID_6` (`old_findID`),
  UNIQUE KEY `old_findID_7` (`old_findID`),
  KEY `objecttype` (`objecttype`),
  KEY `objdate1period` (`objdate1period`),
  KEY `objdate2period` (`objdate2period`),
  KEY `old_findID` (`old_findID`),
  KEY `updatedBy` (`updatedBy`),
  KEY `createdBy` (`createdBy`),
  KEY `rallyID` (`rallyID`),
  KEY `treasureID` (`treasureID`),
  KEY `finderID` (`finderID`),
  KEY `finder2ID` (`finder2ID`),
  KEY `findofnotereason` (`findofnotereason`),
  KEY `recorderID` (`recorderID`),
  KEY `identifier1ID` (`identifier1ID`),
  KEY `broadperiod` (`broadperiod`),
  KEY `manmethod` (`manmethod`),
  KEY `decmethod` (`decmethod`),
  KEY `surftreat` (`surftreat`),
  KEY `material1` (`material1`),
  KEY `material2` (`material2`),
  KEY `preservation` (`preservation`),
  KEY `secuid` (`secuid`),
  KEY `quantity` (`quantity`),
  KEY `other_ref` (`other_ref`),
  KEY `findofnote` (`findofnote`),
  KEY `secwfstage` (`secwfstage`),
  KEY `created` (`created`),
  KEY `identifier2ID` (`identifier2ID`),
  KEY `completeness` (`completeness`),
  KEY `discmethod` (`discmethod`),
  KEY `institution` (`institution`),
  KEY `dbpediaSlug` (`dbpediaSlug`),
  KEY `objdate1subperiod` (`objdate1subperiod`),
  KEY `objdate2subperiod` (`objdate2subperiod`),
  KEY `hoardID` (`hoardID`),
  KEY `hoardID_2` (`hoardID`),
  KEY `hoardID_3` (`hoardID`),
  KEY `hoardID_4` (`hoardID`),
  KEY `hoardID_5` (`hoardID`),
  KEY `hoardID_6` (`hoardID`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `classification` (`classification`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=866296 ;

-- --------------------------------------------------------

--
-- Table structure for table `findsAudit`
--

CREATE TABLE IF NOT EXISTS `findsAudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT '0',
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `findID` (`recordID`),
  KEY `entityID` (`entityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2056921 ;

-- --------------------------------------------------------

--
-- Table structure for table `findsBackup`
--

CREATE TABLE IF NOT EXISTS `findsBackup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hoardID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_findID` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_finderID` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finderID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finder2ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smr_ref` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_ref` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datefound1qual` tinyint(1) DEFAULT NULL,
  `datefound1` date DEFAULT NULL,
  `datefound1flag` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datefound2` date DEFAULT NULL,
  `datefound2flag` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datefound2qual` tinyint(1) DEFAULT NULL,
  `culture` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discmethod` tinyint(2) DEFAULT NULL,
  `disccircum` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `objecttype` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objecttypecert` tinyint(1) DEFAULT NULL,
  `old_candidate` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classification` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subclass` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inscription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objdate1cert` int(11) DEFAULT NULL,
  `objdate1subperiod_old` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objdate1period` tinyint(2) unsigned DEFAULT NULL,
  `objdate2cert` tinyint(1) DEFAULT NULL,
  `objdate2subperiod_old` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `objdate2period` tinyint(2) unsigned DEFAULT NULL,
  `objdate1subperiod` tinyint(1) unsigned DEFAULT NULL,
  `objdate2subperiod` tinyint(1) unsigned DEFAULT NULL,
  `broadperiod` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numdate1qual` tinyint(1) DEFAULT NULL,
  `numdate1` int(11) DEFAULT NULL,
  `numdate2qual` tinyint(1) DEFAULT NULL,
  `numdate2` int(11) DEFAULT NULL,
  `material1` tinyint(2) unsigned DEFAULT NULL,
  `material2` tinyint(2) unsigned DEFAULT NULL,
  `manmethod` tinyint(2) DEFAULT NULL,
  `decmethod` tinyint(2) DEFAULT NULL,
  `surftreat` tinyint(2) DEFAULT NULL,
  `decstyle` tinyint(2) DEFAULT NULL,
  `wear` tinyint(2) DEFAULT NULL,
  `preservation` tinyint(2) DEFAULT NULL,
  `completeness` tinyint(2) DEFAULT NULL,
  `reuse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reuse_period` tinyint(2) DEFAULT NULL,
  `length` double unsigned DEFAULT NULL,
  `width` double unsigned DEFAULT NULL,
  `height` double unsigned DEFAULT NULL,
  `thickness` double unsigned DEFAULT NULL,
  `diameter` double unsigned DEFAULT NULL,
  `weight` double unsigned DEFAULT NULL,
  `quantity` smallint(6) unsigned DEFAULT NULL,
  `curr_loc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recorderID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identifier1ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identifier2ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smrrefno` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `musaccno` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subs_action` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sectag` int(11) unsigned DEFAULT NULL,
  `secowner` int(11) unsigned DEFAULT NULL,
  `secwfstage` tinyint(1) unsigned DEFAULT NULL,
  `findofnote` tinyint(3) DEFAULT NULL,
  `findofnotereason` tinyint(2) DEFAULT NULL,
  `treasure` tinyint(1) DEFAULT NULL,
  `treasureID` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rally` tinyint(1) DEFAULT NULL,
  `rallyID` int(11) DEFAULT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hoardcontainer` int(1) unsigned DEFAULT '0',
  `dbpediaSlug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `objecttype` (`objecttype`),
  KEY `objdate1period` (`objdate1period`),
  KEY `objdate2period` (`objdate2period`),
  KEY `old_findID` (`old_findID`),
  KEY `updatedBy` (`updatedBy`),
  KEY `createdBy` (`createdBy`),
  KEY `rallyID` (`rallyID`),
  KEY `treasureID` (`treasureID`),
  KEY `finderID` (`finderID`),
  KEY `finder2ID` (`finder2ID`),
  KEY `findofnotereason` (`findofnotereason`),
  KEY `recorderID` (`recorderID`),
  KEY `identifier1ID` (`identifier1ID`),
  KEY `broadperiod` (`broadperiod`),
  KEY `manmethod` (`manmethod`),
  KEY `decmethod` (`decmethod`),
  KEY `surftreat` (`surftreat`),
  KEY `material1` (`material1`),
  KEY `material2` (`material2`),
  KEY `preservation` (`preservation`),
  KEY `secuid` (`secuid`),
  KEY `quantity` (`quantity`),
  KEY `other_ref` (`other_ref`),
  KEY `findofnote` (`findofnote`),
  KEY `secwfstage` (`secwfstage`),
  KEY `created` (`created`),
  KEY `identifier2ID` (`identifier2ID`),
  KEY `completeness` (`completeness`),
  KEY `discmethod` (`discmethod`),
  KEY `institution` (`institution`),
  KEY `dbpediaSlug` (`dbpediaSlug`),
  KEY `objdate1subperiod` (`objdate1subperiod`),
  KEY `objdate2subperiod` (`objdate2subperiod`),
  KEY `hoardID` (`hoardID`),
  KEY `hoardID_2` (`hoardID`),
  KEY `hoardID_3` (`hoardID`),
  KEY `hoardID_4` (`hoardID`),
  KEY `hoardID_5` (`hoardID`),
  KEY `hoardID_6` (`hoardID`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `classification` (`classification`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=701690 ;

-- --------------------------------------------------------

--
-- Table structure for table `findspots`
--

CREATE TABLE IF NOT EXISTS `findspots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `findID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_findspotid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `address` text COLLATE utf8_unicode_ci,
  `postcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accuracy` smallint(2) unsigned DEFAULT NULL,
  `gridlen` tinyint(2) DEFAULT NULL,
  `gridref` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fourFigure` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gridrefsrc` tinyint(10) unsigned DEFAULT NULL,
  `gridrefcert` tinyint(3) unsigned DEFAULT NULL,
  `easting` int(11) DEFAULT NULL,
  `northing` int(11) DEFAULT NULL,
  `declong` double DEFAULT NULL,
  `declat` double DEFAULT NULL,
  `fourFigureLat` double DEFAULT NULL,
  `fourFigureLon` double DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `osmNode` int(11) DEFAULT NULL,
  `geohash` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `what3words` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `elevation` double DEFAULT NULL,
  `knownas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alsoknownas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qualityrating` int(1) unsigned DEFAULT NULL,
  `disccircum` text COLLATE utf8_unicode_ci,
  `comments` text COLLATE utf8_unicode_ci,
  `landusevalue` tinyint(2) unsigned DEFAULT NULL,
  `landusecode` smallint(6) unsigned DEFAULT NULL,
  `depthdiscovery` tinyint(2) DEFAULT NULL,
  `soiltype` tinyint(2) DEFAULT NULL,
  `highsensitivity` tinyint(1) DEFAULT NULL,
  `old_occupierid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupier` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `smrref` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherref` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `landowner` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map25k` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map10k` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parishID` int(11) DEFAULT NULL,
  `regionID` int(11) DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `countyID` int(11) DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `districtID` int(11) DEFAULT NULL,
  `country` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parish` (`parish`),
  KEY `declong` (`declong`),
  KEY `declat` (`declat`),
  KEY `county` (`county`),
  KEY `district` (`district`),
  KEY `findID` (`findID`),
  KEY `gridref` (`gridref`),
  KEY `knownas` (`knownas`),
  KEY `fourFigure` (`fourFigure`),
  KEY `country` (`country`),
  KEY `secuid` (`secuid`),
  KEY `woeid` (`woeid`),
  KEY `landusevalue` (`landusevalue`),
  KEY `landusecode` (`landusecode`),
  KEY `createdBy` (`createdBy`),
  KEY `countyID` (`countyID`),
  KEY `parishID` (`parishID`),
  KEY `landowner` (`landowner`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=866311 ;

-- --------------------------------------------------------

--
-- Table structure for table `findspotsAudit`
--

CREATE TABLE IF NOT EXISTS `findspotsAudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `findspotID` (`entityID`),
  KEY `findID` (`recordID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1585538 ;

-- --------------------------------------------------------

--
-- Table structure for table `findspotsBackup`
--

CREATE TABLE IF NOT EXISTS `findspotsBackup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `findID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_findspotid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `address` text COLLATE utf8_unicode_ci,
  `postcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accuracy` smallint(2) unsigned DEFAULT NULL,
  `gridlen` tinyint(2) DEFAULT NULL,
  `gridref` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fourFigure` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gridrefsrc` tinyint(10) unsigned DEFAULT NULL,
  `gridrefcert` tinyint(3) unsigned DEFAULT NULL,
  `easting` int(11) DEFAULT NULL,
  `northing` int(11) DEFAULT NULL,
  `declong` double DEFAULT NULL,
  `declat` double DEFAULT NULL,
  `fourFigureLat` double DEFAULT NULL,
  `fourFigureLon` double DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `osmNode` int(11) DEFAULT NULL,
  `geohash` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `elevation` double DEFAULT NULL,
  `knownas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disccircum` text COLLATE utf8_unicode_ci,
  `comments` text COLLATE utf8_unicode_ci,
  `landusevalue` tinyint(2) unsigned DEFAULT NULL,
  `landusecode` smallint(6) unsigned DEFAULT NULL,
  `depthdiscovery` tinyint(2) DEFAULT NULL,
  `soiltype` tinyint(2) DEFAULT NULL,
  `highsensitivity` tinyint(1) DEFAULT NULL,
  `old_occupierid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupier` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `smrref` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherref` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `landowner` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map25k` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map10k` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parishID` int(11) DEFAULT NULL,
  `regionID` int(11) DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `countyID` int(11) DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `districtID` int(11) DEFAULT NULL,
  `country` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parish` (`parish`),
  KEY `declong` (`declong`),
  KEY `declat` (`declat`),
  KEY `county` (`county`),
  KEY `district` (`district`),
  KEY `findID` (`findID`),
  KEY `gridref` (`gridref`),
  KEY `knownas` (`knownas`),
  KEY `fourFigure` (`fourFigure`),
  KEY `country` (`country`),
  KEY `secuid` (`secuid`),
  KEY `woeid` (`woeid`),
  KEY `landusevalue` (`landusevalue`),
  KEY `landusecode` (`landusecode`),
  KEY `createdBy` (`createdBy`),
  KEY `countyID` (`countyID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=554474 ;

-- --------------------------------------------------------

--
-- Table structure for table `finds_images`
--

CREATE TABLE IF NOT EXISTS `finds_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `find_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`),
  KEY `find_id` (`find_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=621950 ;

-- --------------------------------------------------------

--
-- Table structure for table `finds_publications`
--

CREATE TABLE IF NOT EXISTS `finds_publications` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publication_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_publicationID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `find_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pages_plates` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vol_no` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `last_updated_by` int(11) DEFAULT NULL,
  `exported` tinyint(4) DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secreplica` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `IDX_secuid` (`secuid`),
  KEY `IDX_findID` (`find_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29366 ;

-- --------------------------------------------------------

--
-- Table structure for table `findxfind`
--

CREATE TABLE IF NOT EXISTS `findxfind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `find1ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `find2ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `relationship` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sectag` int(10) unsigned NOT NULL DEFAULT '0',
  `secowner` int(10) unsigned NOT NULL DEFAULT '0',
  `updatedBy` int(10) unsigned DEFAULT '0',
  `createdBy` int(10) unsigned DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` date DEFAULT '0000-00-00',
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secreplica` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `find1ID` (`find1ID`),
  KEY `find2ID` (`find2ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15288 ;

-- --------------------------------------------------------

--
-- Table structure for table `geographyironage`
--

CREATE TABLE IF NOT EXISTS `geographyironage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tribe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `valid` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valid` (`valid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `bmID` (`bmID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Geography data for the Iron Age coins' AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `geoplanetadjacent`
--

CREATE TABLE IF NOT EXISTS `geoplanetadjacent` (
  `PLACE_WOE_ID` int(11) NOT NULL,
  `ISO` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NEIGHBOUR_WOE_ID` int(11) NOT NULL,
  KEY `PLACE_WOE_ID` (`PLACE_WOE_ID`),
  KEY `NEIGHBOUR_WOE_ID` (`NEIGHBOUR_WOE_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `geoplanetaliases`
--

CREATE TABLE IF NOT EXISTS `geoplanetaliases` (
  `WOE_ID` int(11) NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `geoplanetplaces`
--

CREATE TABLE IF NOT EXISTS `geoplanetplaces` (
  `WOE_ID` int(11) NOT NULL,
  `ISO` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`WOE_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `greekstates`
--

CREATE TABLE IF NOT EXISTS `greekstates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='State dropdown values for Greek and Roman Provincial period ' AUTO_INCREMENT=807 ;

-- --------------------------------------------------------

--
-- Table structure for table `gridrefsources`
--

CREATE TABLE IF NOT EXISTS `gridrefsources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `groupsJettonsTypes`
--

CREATE TABLE IF NOT EXISTS `groupsJettonsTypes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `groupID` int(3) DEFAULT NULL,
  `typeID` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Jetton groups to types' AUTO_INCREMENT=209 ;

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

CREATE TABLE IF NOT EXISTS `help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menuTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8_unicode_ci,
  `body` text COLLATE utf8_unicode_ci,
  `section` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `frontPage` int(11) DEFAULT NULL,
  `publishState` int(1) DEFAULT NULL,
  `metaDescription` text COLLATE utf8_unicode_ci,
  `metaKeywords` text COLLATE utf8_unicode_ci,
  `slug` tinytext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `frontPage` (`frontPage`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `heritagecrime`
--

CREATE TABLE IF NOT EXISTS `heritagecrime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crimeType` tinyint(1) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reporterID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `incidentDate` date DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gridref` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fourFigure` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `elevation` int(11) DEFAULT NULL,
  `easting` int(6) DEFAULT NULL,
  `northing` int(6) DEFAULT NULL,
  `map10k` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map25k` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `reliability` tinyint(1) DEFAULT NULL,
  `evaluation` text COLLATE utf8_unicode_ci,
  `samID` int(11) NOT NULL,
  `intellEvaluation` text COLLATE utf8_unicode_ci,
  `reportSubject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subjectDetails` text COLLATE utf8_unicode_ci NOT NULL,
  `reportingPerson` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crimeType` (`crimeType`),
  KEY `samID` (`samID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Heritage crime reports' AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `hers`
--

CREATE TABLE IF NOT EXISTS `hers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` int(3) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Table structure for table `hitlog`
--

CREATE TABLE IF NOT EXISTS `hitlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `findID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `visited` datetime DEFAULT NULL,
  `ipAddress` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userAgent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hoards`
--

CREATE TABLE IF NOT EXISTS `hoards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hoardID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broadperiod` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period1` tinyint(2) unsigned DEFAULT NULL,
  `subperiod1` tinyint(1) unsigned DEFAULT NULL,
  `period2` tinyint(2) unsigned DEFAULT NULL,
  `subperiod2` tinyint(1) unsigned DEFAULT NULL,
  `numdate1` int(11) DEFAULT NULL,
  `numdate2` int(11) DEFAULT NULL,
  `lastrulerID` int(10) unsigned DEFAULT NULL,
  `reeceID` int(10) unsigned DEFAULT NULL,
  `quantityCoins` int(11) DEFAULT NULL,
  `quantityArtefacts` int(11) DEFAULT NULL,
  `quantityContainers` int(11) DEFAULT NULL,
  `terminalyear1` int(11) DEFAULT NULL,
  `terminalyear2` int(11) DEFAULT NULL,
  `terminalreason` int(11) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `notes` text COLLATE utf8_unicode_ci,
  `secwfstage` tinyint(1) unsigned DEFAULT NULL,
  `findofnote` tinyint(3) DEFAULT NULL,
  `findofnotereason` tinyint(2) DEFAULT NULL,
  `treasure` tinyint(1) DEFAULT NULL,
  `treasureID` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qualityrating` int(1) unsigned DEFAULT NULL,
  `materials` text COLLATE utf8_unicode_ci,
  `recorderID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identifier1ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identifier2ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disccircum` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discmethod` tinyint(2) DEFAULT NULL,
  `datefound1` date DEFAULT NULL,
  `datefound2` date DEFAULT NULL,
  `rally` tinyint(1) DEFAULT NULL,
  `rallyID` int(11) DEFAULT NULL,
  `legacyID` int(11) DEFAULT NULL,
  `other_ref` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smrrefno` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `musaccno` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `curr_loc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subs_action` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hoardID_2` (`hoardID`),
  KEY `hoardID` (`hoardID`),
  KEY `secuid` (`secuid`),
  KEY `terminalyear1` (`terminalyear1`),
  KEY `terminalyear2` (`terminalyear2`),
  KEY `terminalreason` (`terminalreason`),
  KEY `broadperiod` (`broadperiod`),
  KEY `secwfstage` (`secwfstage`),
  KEY `findofnote` (`findofnote`),
  KEY `findofnotereason` (`findofnotereason`),
  KEY `treasureID` (`treasureID`),
  KEY `recorderID` (`recorderID`),
  KEY `identifier1ID` (`identifier1ID`),
  KEY `identifier2ID` (`identifier2ID`),
  KEY `discmethod` (`discmethod`),
  KEY `rallyID` (`rallyID`),
  KEY `legacyID` (`legacyID`),
  KEY `other_ref` (`other_ref`),
  KEY `created` (`created`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `institution` (`institution`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Hoard records' AUTO_INCREMENT=3294 ;

-- --------------------------------------------------------

--
-- Table structure for table `hoardsAudit`
--

CREATE TABLE IF NOT EXISTS `hoardsAudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT '0',
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `findID` (`recordID`),
  KEY `entityID` (`entityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=51107 ;

-- --------------------------------------------------------

--
-- Table structure for table `hoardSpots`
--

CREATE TABLE IF NOT EXISTS `hoardSpots` (
  `HoardID` int(11) NOT NULL AUTO_INCREMENT,
  `FindspotName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FindspotOtherNames` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FindspotLocation1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `districtID` int(6) DEFAULT NULL,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parishID` int(6) DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `countyID` int(6) DEFAULT NULL,
  `regionID` int(6) DEFAULT NULL,
  `FindspotLocation4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FindspotLocation5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`HoardID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3128 ;

-- --------------------------------------------------------

--
-- Table structure for table `hoards_finders`
--

CREATE TABLE IF NOT EXISTS `hoards_finders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hoardID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finderID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `viewOrder` int(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hoardID` (`hoardID`),
  KEY `finderID` (`finderID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Multiple finders per hoard record' AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Table structure for table `imagetypes`
--

CREATE TABLE IF NOT EXISTS `imagetypes` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `type` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(2) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE IF NOT EXISTS `institutions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `institution` (`institution`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Recording institutions' AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Table structure for table `instLogos`
--

CREATE TABLE IF NOT EXISTS `instLogos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instID` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `instID` (`instID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Institutional logos for partners' AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `ironagedenomxregion`
--

CREATE TABLE IF NOT EXISTS `ironagedenomxregion` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `denomID` int(10) unsigned NOT NULL DEFAULT '0',
  `regionID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Denomination-to-Region relaations for the Iron Age coins' AUTO_INCREMENT=321 ;

-- --------------------------------------------------------

--
-- Table structure for table `ironageregionstribes`
--

CREATE TABLE IF NOT EXISTS `ironageregionstribes` (
  `id` int(3) NOT NULL,
  `regionID` int(3) NOT NULL,
  `tribeID` int(3) NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Region to tribe lookup table';

-- --------------------------------------------------------

--
-- Table structure for table `ironagerulerxregion`
--

CREATE TABLE IF NOT EXISTS `ironagerulerxregion` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rulerID` int(10) unsigned NOT NULL DEFAULT '0',
  `regionID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ruler-to-Region relations for the Iron Age coins' AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Table structure for table `ironagetribes`
--

CREATE TABLE IF NOT EXISTS `ironagetribes` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `tribe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmTribeID` int(6) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bmTribeID` (`bmTribeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Iron Age tribes ' AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Table structure for table `issuers`
--

CREATE TABLE IF NOT EXISTS `issuers` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pasID` int(3) DEFAULT NULL,
  `period` int(2) DEFAULT NULL,
  `date_from` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_to` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `biography` text COLLATE utf8_unicode_ci,
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zoomfolder` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(3) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=860 ;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE IF NOT EXISTS `issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issueTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issueDescription` text COLLATE utf8_unicode_ci,
  `resolutionApplied` text COLLATE utf8_unicode_ci,
  `status` tinyint(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Issues raised with the database' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `issueStatuses`
--

CREATE TABLE IF NOT EXISTS `issueStatuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Issue status' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jettonClasses`
--

CREATE TABLE IF NOT EXISTS `jettonClasses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `className` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Jetton and token classes' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `jettonGroup`
--

CREATE TABLE IF NOT EXISTS `jettonGroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupName` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Jetton groupings' AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `jettonTypes`
--

CREATE TABLE IF NOT EXISTS `jettonTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Jetton types' AUTO_INCREMENT=199 ;

-- --------------------------------------------------------

--
-- Table structure for table `landscapetopography`
--

CREATE TABLE IF NOT EXISTS `landscapetopography` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feature` (`feature`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Landscape and topography for hoards (archaeological context information)' AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `landuses`
--

CREATE TABLE IF NOT EXISTS `landuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oldID` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `belongsto` int(6) DEFAULT NULL,
  `valid` tinyint(6) unsigned DEFAULT '1',
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `term` (`term`),
  KEY `belongsto` (`belongsto`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Table structure for table `licenseType`
--

CREATE TABLE IF NOT EXISTS `licenseType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `license` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flickrID` int(11) DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `acronym` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `createdBy` int(6) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(6) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flickrID` (`flickrID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='License types for images' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8_unicode_ci,
  `type` int(2) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `loginRedirect`
--

CREATE TABLE IF NOT EXISTS `loginRedirect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Login redirects' AUTO_INCREMENT=110 ;

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE IF NOT EXISTS `logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loginDate` datetime DEFAULT NULL,
  `ipAddress` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userAgent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loginDate` (`loginDate`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Login user history' AUTO_INCREMENT=545348 ;

-- --------------------------------------------------------

--
-- Table structure for table `macktypes`
--

CREATE TABLE IF NOT EXISTS `macktypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT '56',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mack Types for Iron Age coins' AUTO_INCREMENT=19138 ;

-- --------------------------------------------------------

--
-- Table structure for table `mailinglist`
--

CREATE TABLE IF NOT EXISTS `mailinglist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town_city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_address` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mailing list sign ups' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `manufactures`
--

CREATE TABLE IF NOT EXISTS `manufactures` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` smallint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `term` (`term`),
  KEY `bmID` (`bmID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `maporigins`
--

CREATE TABLE IF NOT EXISTS `maporigins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Origins of grid references' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(8) DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `parentID` int(50) unsigned DEFAULT NULL,
  `valid` tinyint(6) unsigned NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `valid` (`valid`),
  KEY `updatedBy` (`updatedBy`),
  KEY `bmID` (`bmID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Table structure for table `mda_obj_prefs`
--

CREATE TABLE IF NOT EXISTS `mda_obj_prefs` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `THE_TE_UID_1` int(11) unsigned DEFAULT NULL,
  `THE_TE_UID_2` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=494 ;

-- --------------------------------------------------------

--
-- Table structure for table `mda_obj_rels`
--

CREATE TABLE IF NOT EXISTS `mda_obj_rels` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TH_T_U_UID_1` int(11) unsigned DEFAULT NULL,
  `TH_T_U_UID_2` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=203 ;

-- --------------------------------------------------------

--
-- Table structure for table `mda_obj_uses`
--

CREATE TABLE IF NOT EXISTS `mda_obj_uses` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TH_T_U_UID` int(11) unsigned DEFAULT NULL,
  `TERM` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CLA_GR_UID` smallint(6) unsigned DEFAULT NULL,
  `BROAD_TERM_U_UID` int(11) unsigned DEFAULT NULL,
  `TOP_TERM_U_UID` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1851 ;

-- --------------------------------------------------------

--
-- Table structure for table `medievalcategories`
--

CREATE TABLE IF NOT EXISTS `medievalcategories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `periodID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `medievaltypes`
--

CREATE TABLE IF NOT EXISTS `medievaltypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rulerID` int(10) unsigned DEFAULT NULL,
  `periodID` int(10) unsigned DEFAULT NULL,
  `datefrom` int(11) DEFAULT NULL,
  `dateto` int(11) DEFAULT NULL,
  `categoryID` int(10) unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rulerID` (`rulerID`),
  KEY `categoryID` (`categoryID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Coin types' AUTO_INCREMENT=4481 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_author` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_content` text COLLATE utf8_unicode_ci,
  `comment_author_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_author_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `comment_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `comment_approved` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replied` tinyint(4) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Log of messages from contact us form' AUTO_INCREMENT=1873 ;

-- --------------------------------------------------------

--
-- Table structure for table `mints`
--

CREATE TABLE IF NOT EXISTS `mints` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` int(10) unsigned DEFAULT NULL,
  `old_period` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mint_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `definition` text COLLATE utf8_unicode_ci,
  `osID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomismaID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pleiadesID` int(11) DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `bmID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `gettyID` int(12) DEFAULT NULL,
  `dbpediaID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `what3words` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `modernCountry` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mint_name` (`mint_name`),
  KEY `valid` (`valid`),
  KEY `period` (`period`),
  KEY `pleiadesID` (`pleiadesID`),
  KEY `woeid` (`woeid`),
  KEY `geonamesID` (`geonamesID`),
  KEY `nomismaID` (`nomismaID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1568 ;

-- --------------------------------------------------------

--
-- Table structure for table `mintsOld`
--

CREATE TABLE IF NOT EXISTS `mintsOld` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` int(10) unsigned DEFAULT NULL,
  `old_period` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mint_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `definition` text COLLATE utf8_unicode_ci,
  `osID` int(18) DEFAULT NULL,
  `nomismaID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pleiadesID` int(11) DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `bmID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `gettyID` int(12) DEFAULT NULL,
  `dbpediaID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `what3words` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `modernCountry` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mint_name` (`mint_name`),
  KEY `valid` (`valid`),
  KEY `period` (`period`),
  KEY `pleiadesID` (`pleiadesID`),
  KEY `woeid` (`woeid`),
  KEY `geonamesID` (`geonamesID`),
  KEY `nomismaID` (`nomismaID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1566 ;

-- --------------------------------------------------------

--
-- Table structure for table `mints_rulers`
--

CREATE TABLE IF NOT EXISTS `mints_rulers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ruler_id` int(10) unsigned NOT NULL DEFAULT '0',
  `mint_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mint_id` (`mint_id`),
  KEY `ruler_id` (`ruler_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3385 ;

-- --------------------------------------------------------

--
-- Table structure for table `mint_reversetype`
--

CREATE TABLE IF NOT EXISTS `mint_reversetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mintID` int(4) DEFAULT NULL,
  `reverseID` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mintID` (`mintID`),
  KEY `reverseID` (`reverseID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mint to reverse link table' AUTO_INCREMENT=1552 ;

-- --------------------------------------------------------

--
-- Table structure for table `monarchs`
--

CREATE TABLE IF NOT EXISTS `monarchs` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `dbaseID` int(11) DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `styled` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_from` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `biography` text COLLATE utf8_unicode_ci,
  `born` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `died` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dynasty` int(3) DEFAULT NULL,
  `publishState` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dbaseID` (`dbaseID`),
  KEY `createdby` (`createdby`),
  KEY `publishState` (`publishState`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `moneyers`
--

CREATE TABLE IF NOT EXISTS `moneyers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `viaf` int(11) DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `dbpediaID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wikipediaEntry` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomismaID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` int(2) DEFAULT '21',
  `alt_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_1` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_2` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mint` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `RRC` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appear` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nomismaID` (`nomismaID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Roman Republic Moneyers' AUTO_INCREMENT=375 ;

-- --------------------------------------------------------

--
-- Table structure for table `moneyersOld`
--

CREATE TABLE IF NOT EXISTS `moneyersOld` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `viaf` int(11) DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `dbpediaID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wikipediaEntry` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomismaID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` int(2) DEFAULT '21',
  `alt_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_1` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_2` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mint` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `RRC` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appear` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nomismaID` (`nomismaID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Roman Republic Moneyers' AUTO_INCREMENT=372 ;

-- --------------------------------------------------------

--
-- Table structure for table `myresearch`
--

CREATE TABLE IF NOT EXISTS `myresearch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `public` int(1) NOT NULL DEFAULT '0',
  `createdBy` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `secuid` (`secuid`),
  KEY `public` (`public`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Research catalogues' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8_unicode_ci,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datePublished` datetime DEFAULT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contactName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contactTel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contactEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `editorNotes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contents` text COLLATE utf8_unicode_ci,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regionID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `typeID` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `publish_state` tinyint(1) NOT NULL,
  `golive` datetime NOT NULL,
  `primaryNewsLocation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(3) DEFAULT NULL,
  `updatedBy` int(3) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `woeid` (`woeid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `created` (`created`),
  KEY `golive` (`golive`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=293 ;

-- --------------------------------------------------------

--
-- Table structure for table `oai_pmh_repository_tokens`
--

CREATE TABLE IF NOT EXISTS `oai_pmh_repository_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `verb` enum('ListIdentifiers','ListRecords','ListSets') COLLATE utf8_unicode_ci NOT NULL,
  `metadata_prefix` text COLLATE utf8_unicode_ci NOT NULL,
  `cursor` int(10) unsigned NOT NULL DEFAULT '0',
  `from` datetime DEFAULT NULL,
  `until` datetime DEFAULT NULL,
  `set` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiration` datetime NOT NULL,
  `ipaddress` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `useragent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expiration` (`expiration`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=322950 ;

-- --------------------------------------------------------

--
-- Table structure for table `oauthTokens`
--

CREATE TABLE IF NOT EXISTS `oauthTokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accessToken` text COLLATE utf8_unicode_ci NOT NULL,
  `tokenSecret` text COLLATE utf8_unicode_ci NOT NULL,
  `service` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sessionHandle` text COLLATE utf8_unicode_ci NOT NULL,
  `guid` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expires` (`expires`),
  KEY `service` (`service`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Oauth tokens' AUTO_INCREMENT=23434 ;

-- --------------------------------------------------------

--
-- Table structure for table `objectterms`
--

CREATE TABLE IF NOT EXISTS `objectterms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `ehID` int(6) DEFAULT NULL,
  `term` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `indexTerm` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scopeNote` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `claUid` smallint(6) unsigned DEFAULT NULL,
  `status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `term` (`term`),
  KEY `indexTerm` (`indexTerm`),
  KEY `ehID` (`ehID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2128 ;

-- --------------------------------------------------------

--
-- Table structure for table `oldrulers`
--

CREATE TABLE IF NOT EXISTS `oldrulers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` int(11) DEFAULT NULL,
  `issuer` char(255) DEFAULT NULL,
  `viaf` int(11) DEFAULT NULL,
  `dbpedia` varchar(100) DEFAULT NULL,
  `nomismaID` varchar(100) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `region` char(255) DEFAULT NULL,
  `date1` smallint(6) DEFAULT NULL,
  `date2` smallint(6) DEFAULT NULL,
  `valid` smallint(6) DEFAULT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` char(255) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `issuer` (`issuer`),
  KEY `country` (`country`),
  KEY `display` (`display`),
  KEY `date1` (`date1`),
  KEY `date2` (`date2`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1375 ;

-- --------------------------------------------------------

--
-- Table structure for table `opencalais`
--

CREATE TABLE IF NOT EXISTS `opencalais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contentID` int(11) DEFAULT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contenttype` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `origin` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `woeid` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `woeid` (`woeid`),
  KEY `contenttype` (`contenttype`),
  KEY `origin` (`origin`),
  KEY `contentID` (`contentID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Opencalais tagged content' AUTO_INCREMENT=901646 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisations`
--

CREATE TABLE IF NOT EXISTS `organisations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address3` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `town_city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `contactpersonID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(20) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(20) unsigned DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `woeid` (`woeid`),
  KEY `secuid` (`secuid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=514 ;

-- --------------------------------------------------------

--
-- Table structure for table `organisationsAudit`
--

CREATE TABLE IF NOT EXISTS `organisationsAudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=661 ;

-- --------------------------------------------------------

--
-- Table structure for table `osCounties`
--

CREATE TABLE IF NOT EXISTS `osCounties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osID` int(11) DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regionID` int(11) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `typeUri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `northing` int(8) DEFAULT NULL,
  `easting` int(8) DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `osID` (`osID`),
  KEY `regionID` (`regionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='OS counties' AUTO_INCREMENT=388 ;

-- --------------------------------------------------------

--
-- Table structure for table `osdata`
--

CREATE TABLE IF NOT EXISTS `osdata` (
  `id` int(11) NOT NULL,
  `km_ref` char(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `name` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tile_ref` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lat_degrees` int(2) DEFAULT NULL,
  `lat_minutes` float DEFAULT NULL,
  `lon_degrees` int(2) DEFAULT NULL,
  `lon_minutes` float DEFAULT NULL,
  `northing` int(7) DEFAULT NULL,
  `easting` int(7) DEFAULT NULL,
  `gmt` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county_code` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `full_county` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `f_code` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `e_date` char(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_code` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sheet1` int(3) DEFAULT NULL,
  `sheet2` int(3) DEFAULT NULL,
  `sheet3` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `latitude` (`latitude`),
  KEY `longitude` (`longitude`),
  KEY `f_code` (`f_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='OSDATA 1:50000';

-- --------------------------------------------------------

--
-- Table structure for table `osDistricts`
--

CREATE TABLE IF NOT EXISTS `osDistricts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osID` int(11) DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `countyID` int(11) DEFAULT NULL,
  `regionID` int(11) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `typeURI` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `northing` int(8) DEFAULT NULL,
  `easting` int(8) DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `countyID` (`countyID`),
  KEY `regionID` (`regionID`),
  KEY `osID` (`osID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='OS regions' AUTO_INCREMENT=515 ;

-- --------------------------------------------------------

--
-- Table structure for table `osParishes`
--

CREATE TABLE IF NOT EXISTS `osParishes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osID` int(11) DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `districtID` int(11) DEFAULT NULL,
  `countyID` int(11) DEFAULT NULL,
  `regionID` int(11) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `typeUri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `northing` float DEFAULT NULL,
  `easting` float DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `odID` (`osID`),
  KEY `districtID` (`districtID`),
  KEY `countyID` (`countyID`),
  KEY `regionID` (`regionID`),
  KEY `label` (`label`),
  KEY `osID` (`osID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23178 ;

-- --------------------------------------------------------

--
-- Table structure for table `osRegions`
--

CREATE TABLE IF NOT EXISTS `osRegions` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `osID` int(6) DEFAULT NULL,
  `uri` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `northing` double DEFAULT NULL,
  `easting` double DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(6) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(6) DEFAULT NULL,
  `valid` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `osID` (`osID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ordanance Survey regions' AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organisationID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `surname` varchar(90) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forename` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullname` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `town_city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `countyID` int(11) DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hometel` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `worktel` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `faxno` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `type` smallint(6) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secreplica` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primary_activity` int(11) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `dbaseID` int(11) DEFAULT NULL,
  `canRecord` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `primary_activity` (`primary_activity`),
  KEY `woeid` (`woeid`),
  KEY `secuid` (`secuid`),
  KEY `dbaseID` (`dbaseID`),
  KEY `organisationID` (`organisationID`),
  KEY `fullname` (`fullname`),
  KEY `canRecord` (`canRecord`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32968 ;

-- --------------------------------------------------------

--
-- Table structure for table `peopleAudit`
--

CREATE TABLE IF NOT EXISTS `peopleAudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) NOT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=48000 ;

-- --------------------------------------------------------

--
-- Table structure for table `peopletypes`
--

CREATE TABLE IF NOT EXISTS `peopletypes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modified` datetime NOT NULL,
  KEY `ID` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE IF NOT EXISTS `periods` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `term` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ehTerm` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `periodo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `parent` int(11) NOT NULL DEFAULT '0',
  `broadterm` int(10) unsigned DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `fromdate` int(11) DEFAULT NULL,
  `todate` int(11) DEFAULT NULL,
  `old_sortorder` int(10) unsigned NOT NULL DEFAULT '0',
  `sortorder` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `preferred` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `valid` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valid` (`valid`),
  KEY `ehTerm` (`ehTerm`),
  KEY `bmID` (`bmID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE IF NOT EXISTS `places` (
  `old_county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `placeID` int(11) unsigned DEFAULT NULL,
  `parentID` int(11) DEFAULT NULL,
  `adln_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `npl_flag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(4) unsigned DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `IDX_placeID` (`placeID`),
  KEY `parish` (`parish`),
  KEY `county` (`county`),
  KEY `district` (`district`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12306 ;

-- --------------------------------------------------------

--
-- Table structure for table `places2`
--

CREATE TABLE IF NOT EXISTS `places2` (
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `placeID` int(11) unsigned DEFAULT NULL,
  `parentID` int(11) DEFAULT NULL,
  `adln_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `npl_flag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(4) unsigned DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `county` (`county`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12297 ;

-- --------------------------------------------------------

--
-- Table structure for table `preservations`
--

CREATE TABLE IF NOT EXISTS `preservations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','0') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `valid` (`valid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `bmID` (`bmID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `primaryactivities`
--

CREATE TABLE IF NOT EXISTS `primaryactivities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(11) unsigned DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `projecttypes`
--

CREATE TABLE IF NOT EXISTS `projecttypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of research project' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE IF NOT EXISTS `publications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authors` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `editors` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reprint_year` smallint(6) DEFAULT NULL,
  `in_publication` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `article_pages` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edition` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publisher` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_place` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_year` smallint(6) DEFAULT NULL,
  `vol_no` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ISBN` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `biab` int(11) DEFAULT NULL,
  `doi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accessedDate` date DEFAULT NULL,
  `medium` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) unsigned DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `in_publication` (`in_publication`),
  KEY `publication_type` (`publication_type`),
  KEY `secuid` (`secuid`),
  KEY `biab` (`biab`),
  KEY `doi` (`doi`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `authors` (`authors`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4710 ;

-- --------------------------------------------------------

--
-- Table structure for table `publicationtypes`
--

CREATE TABLE IF NOT EXISTS `publicationtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` text COLLATE utf8_unicode_ci,
  `quotedBy` text COLLATE utf8_unicode_ci,
  `type` varchar(155) COLLATE utf8_unicode_ci DEFAULT 'quote',
  `status` int(1) DEFAULT '1',
  `expire` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `status` (`status`),
  KEY `expire` (`expire`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Quotes about the Scheme' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `rallies`
--

CREATE TABLE IF NOT EXISTS `rallies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rally_name` mediumtext COLLATE utf8_unicode_ci,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parishID` int(8) DEFAULT NULL,
  `districtID` int(8) DEFAULT NULL,
  `countyID` int(8) DEFAULT NULL,
  `gridref` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `easting` int(11) DEFAULT NULL,
  `northing` int(11) DEFAULT NULL,
  `map25k` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map10k` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fourFigure` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `record_method` text COLLATE utf8_unicode_ci,
  `organiser` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `date_from` (`date_from`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Rally locations' AUTO_INCREMENT=528 ;

-- --------------------------------------------------------

--
-- Table structure for table `rallyXflo`
--

CREATE TABLE IF NOT EXISTS `rallyXflo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rallyID` int(11) DEFAULT NULL,
  `staffID` int(11) DEFAULT NULL,
  `dateFrom` date DEFAULT NULL,
  `dateTo` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rallyID` (`rallyID`),
  KEY `staffID` (`staffID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Flos attending a rally' AUTO_INCREMENT=295 ;

-- --------------------------------------------------------

--
-- Table structure for table `recmethods`
--

CREATE TABLE IF NOT EXISTS `recmethods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `method` (`method`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Recovery methods for hoards (archaeological context information)' AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `reeceperiods`
--

CREATE TABLE IF NOT EXISTS `reeceperiods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_range` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_period` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` int(11) DEFAULT NULL,
  `valid` smallint(6) DEFAULT NULL,
  `createdBy` tinyint(6) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` tinyint(6) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valid` (`valid`),
  KEY `updatedBy` (`updatedBy`),
  KEY `period_name` (`period_name`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `reeceperiods_rulers`
--

CREATE TABLE IF NOT EXISTS `reeceperiods_rulers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ruler_id` int(10) NOT NULL,
  `reeceperiod_id` int(10) NOT NULL,
  `periodID` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL DEFAULT '56',
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL DEFAULT '56',
  PRIMARY KEY (`id`),
  KEY `ruler_id` (`ruler_id`),
  KEY `reeceperiod_id` (`reeceperiod_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=167 ;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `region` (`region`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `reliability`
--

CREATE TABLE IF NOT EXISTS `reliability` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `term` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Reliability of evidence' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE IF NOT EXISTS `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messagetext` text COLLATE utf8_unicode_ci,
  `messageID` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `messageID` (`messageID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Replies to submitted messages' AUTO_INCREMENT=340 ;

-- --------------------------------------------------------

--
-- Table structure for table `researchprojects`
--

CREATE TABLE IF NOT EXISTS `researchprojects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `investigator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) unsigned DEFAULT NULL,
  `valid` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `valid` (`valid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='List of research projects' AUTO_INCREMENT=606 ;

-- --------------------------------------------------------

--
-- Table structure for table `reverses`
--

CREATE TABLE IF NOT EXISTS `reverses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wikipediaName` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zoomer` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `image` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` datetime DEFAULT '0000-00-00 00:00:00',
  `type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attrib1` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attrib2` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attrib3` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `greek` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Table structure for table `revtypes`
--

CREATE TABLE IF NOT EXISTS `revtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text COLLATE utf8_unicode_ci,
  `translation` tinytext COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `gendate` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reeceID` int(2) NOT NULL,
  `common` enum('1','2') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` varchar(11) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `common` (`common`),
  KEY `reeceID` (`reeceID`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fourth Century reverse types, Roman coins' AUTO_INCREMENT=712 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT '56',
  PRIMARY KEY (`id`),
  KEY `role` (`role`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='User roles' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `romandenoms`
--

CREATE TABLE IF NOT EXISTS `romandenoms` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `pasID` int(11) NOT NULL,
  `denomination` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rarity` text COLLATE utf8_unicode_ci,
  `weight` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `metal` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diameter` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thickness` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `design` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `obverse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reverse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(3) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(3) NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `romanmints`
--

CREATE TABLE IF NOT EXISTS `romanmints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pasID` int(3) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `pleiadesID` int(11) DEFAULT NULL,
  `nomismaID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `dbpediaID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `abbrev` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(3) DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pasID` (`pasID`),
  KEY `bmID` (`bmID`),
  KEY `dbpediaID` (`dbpediaID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `rulerImages`
--

CREATE TABLE IF NOT EXISTS `rulerImages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `caption` text COLLATE utf8_unicode_ci,
  `rulerID` int(11) DEFAULT NULL,
  `zoomroute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filesize` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mimetype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rulerID` (`rulerID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Ruler images' AUTO_INCREMENT=171 ;

-- --------------------------------------------------------

--
-- Table structure for table `rulers`
--

CREATE TABLE IF NOT EXISTS `rulers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` int(11) DEFAULT NULL,
  `issuer` char(255) DEFAULT NULL,
  `viaf` int(11) DEFAULT NULL,
  `nomismaID` varchar(100) DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `dbpedia` varchar(100) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `region` char(255) DEFAULT NULL,
  `date1` smallint(6) DEFAULT NULL,
  `date2` smallint(6) DEFAULT NULL,
  `valid` smallint(6) DEFAULT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` char(255) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `issuer` (`issuer`),
  KEY `country` (`country`),
  KEY `display` (`display`),
  KEY `date1` (`date1`),
  KEY `date2` (`date2`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2384 ;

-- --------------------------------------------------------

--
-- Table structure for table `ruler_reversetype`
--

CREATE TABLE IF NOT EXISTS `ruler_reversetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reverseID` int(4) DEFAULT NULL,
  `rulerID` int(4) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  PRIMARY KEY (`id`),
  KEY `rulerID` (`rulerID`),
  KEY `reverseID` (`reverseID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Reverse types to ruler link table' AUTO_INCREMENT=2233 ;

-- --------------------------------------------------------

--
-- Table structure for table `savedSearches`
--

CREATE TABLE IF NOT EXISTS `savedSearches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `searchString` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `searchDescription` text COLLATE utf8_unicode_ci,
  `public` tinyint(1) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `public` (`public`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Saved searchs referenced to users' AUTO_INCREMENT=1594 ;

-- --------------------------------------------------------

--
-- Table structure for table `scheduledMonuments`
--

CREATE TABLE IF NOT EXISTS `scheduledMonuments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `county` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monumentNumber` int(11) DEFAULT NULL,
  `monumentName` text COLLATE utf8_unicode_ci,
  `dateScheduled` date DEFAULT NULL,
  `gridref` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fourFigure` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map25k` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map10k` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `easting` int(10) DEFAULT NULL,
  `northing` int(10) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `elevation` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gridref` (`gridref`),
  KEY `fourFigure` (`fourFigure`),
  KEY `lat` (`lat`),
  KEY `lon` (`lon`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Scheduled monuments' AUTO_INCREMENT=25047 ;

-- --------------------------------------------------------

--
-- Table structure for table `searches`
--

CREATE TABLE IF NOT EXISTS `searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `searchString` text COLLATE utf8_unicode_ci,
  `date` datetime DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `ipaddress` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `useragent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21183708 ;

-- --------------------------------------------------------

--
-- Table structure for table `sketchFab`
--

CREATE TABLE IF NOT EXISTS `sketchFab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modelID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `findID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table to hold details for SketchFab models' AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE IF NOT EXISTS `slides` (
  `imageID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mimetype` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filename` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filesize` int(10) unsigned DEFAULT NULL,
  `filedate` datetime DEFAULT NULL,
  `label` text COLLATE utf8_unicode_ci,
  `period` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filecreated` datetime DEFAULT NULL,
  `imagecreated` smallint(4) unsigned DEFAULT NULL,
  `imagerights` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imagesite` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fileowner` int(10) unsigned NOT NULL DEFAULT '0',
  `attrmodified` datetime DEFAULT NULL,
  `filecopyright` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ccLicense` int(1) DEFAULT '5',
  `imagetitle` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imagecreator` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imagesource` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(10) unsigned DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `institution` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`imageID`),
  KEY `imagecreator` (`imagecreator`),
  KEY `county` (`county`),
  KEY `filename` (`filename`),
  KEY `secuid` (`secuid`),
  KEY `createdBy` (`createdBy`),
  KEY `period` (`period`),
  KEY `ccLicense` (`ccLicense`),
  KEY `institution` (`institution`),
  FULLTEXT KEY `label` (`label`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=630757 ;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dbaseID` int(11) DEFAULT NULL,
  `email_one` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_two` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `identifier` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` int(2) DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile` text COLLATE utf8_unicode_ci,
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updatedBy` int(3) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `alumni` int(1) DEFAULT NULL,
  `blog_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dbaseID` (`dbaseID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=200 ;

-- --------------------------------------------------------

--
-- Table structure for table `staffregions`
--

CREATE TABLE IF NOT EXISTS `staffregions` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `regionID` int(11) DEFAULT NULL,
  `prefix` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county_map` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kml_file` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prefix` (`prefix`),
  KEY `regionID` (`regionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table `staffroles`
--

CREATE TABLE IF NOT EXISTS `staffroles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(4) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE IF NOT EXISTS `statuses` (
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `subperiods`
--

CREATE TABLE IF NOT EXISTS `subperiods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(11) unsigned DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `subsequentActions`
--

CREATE TABLE IF NOT EXISTS `subsequentActions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `action` (`action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Subsequent actions by flos' AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `suggestedResearch`
--

CREATE TABLE IF NOT EXISTS `suggestedResearch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `period` int(2) DEFAULT NULL,
  `level` int(2) DEFAULT NULL,
  `taken` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(4) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` bigint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Suggested research topice from the Scheme' AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `summaryaudit`
--

CREATE TABLE IF NOT EXISTS `summaryaudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `coinID` (`recordID`),
  KEY `findID` (`entityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2943 ;

-- --------------------------------------------------------

--
-- Table structure for table `surftreatments`
--

CREATE TABLE IF NOT EXISTS `surftreatments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','2') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `valid` (`valid`),
  KEY `updatedBy` (`updatedBy`),
  KEY `bmID` (`bmID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `systemroles`
--

CREATE TABLE IF NOT EXISTS `systemroles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='System roles on database' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `taggedcontent`
--

CREATE TABLE IF NOT EXISTS `taggedcontent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` text COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Content tags' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tempfindspots`
--

CREATE TABLE IF NOT EXISTS `tempfindspots` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `knownas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gridref` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `easting` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `northing` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smr_ref` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `findspot_desc` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1173 ;

-- --------------------------------------------------------

--
-- Table structure for table `terminalreason`
--

CREATE TABLE IF NOT EXISTS `terminalreason` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reason` (`reason`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Reasons for the terminal date in coin hoards' AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `thes_chronuk2`
--

CREATE TABLE IF NOT EXISTS `thes_chronuk2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `termID` int(11) NOT NULL DEFAULT '0',
  `partof` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `treasureActions`
--

CREATE TABLE IF NOT EXISTS `treasureActions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `actionID` int(2) NOT NULL,
  `actionTaken` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Actions associated with Treasure case' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `treasureActionTypes`
--

CREATE TABLE IF NOT EXISTS `treasureActionTypes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Actions that can be used in Treasure management' AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `treasureAssignations`
--

CREATE TABLE IF NOT EXISTS `treasureAssignations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `curatorID` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `chaseDate` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Assignations for Treasure cases' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `treasureStatus`
--

CREATE TABLE IF NOT EXISTS `treasureStatus` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `treasureID` (`treasureID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Status of Treasure case' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `treasureStatusTypes`
--

CREATE TABLE IF NOT EXISTS `treasureStatusTypes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure management status list' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `treasureValuations`
--

CREATE TABLE IF NOT EXISTS `treasureValuations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `valuerID` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `value` double unsigned DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `dateOfValuation` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Valuations for Treasure cases' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `tvcDates`
--

CREATE TABLE IF NOT EXISTS `tvcDates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `location` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `secuid` (`secuid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure Valuation Committe dates' AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `tvcDatesToCases`
--

CREATE TABLE IF NOT EXISTS `tvcDatesToCases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treasureID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tvcID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `treasureID` (`treasureID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure Valuation committe to object ' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `userOnlineAccounts`
--

CREATE TABLE IF NOT EXISTS `userOnlineAccounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accountName` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `public` (`public`),
  KEY `userID` (`userID`),
  KEY `accountName` (`accountName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='online accounts for users for foaf' AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seclevel` smallint(6) unsigned DEFAULT '0',
  `password` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `institution` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `copyright` text COLLATE utf8_unicode_ci,
  `phone` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastvisit` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullname` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `preferred_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activationKey` varchar(34) COLLATE utf8_unicode_ci DEFAULT NULL,
  `higherLevel` tinyint(1) DEFAULT NULL,
  `researchOutline` text COLLATE utf8_unicode_ci,
  `already` tinyint(1) DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referenceEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visits` int(11) unsigned DEFAULT '0',
  `imagedir` varchar(60) COLLATE utf8_unicode_ci DEFAULT 'images/',
  `path` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `webaddr` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(3) unsigned DEFAULT '1',
  `peopleID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `canRecord` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`),
  KEY `institution` (`institution`),
  KEY `lastLogin` (`lastLogin`),
  KEY `visits` (`visits`),
  KEY `email` (`email`),
  KEY `higherLevel` (`higherLevel`),
  KEY `canRecord` (`canRecord`),
  KEY `peopleID` (`peopleID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34047 ;

-- --------------------------------------------------------

--
-- Table structure for table `usersAudit`
--

CREATE TABLE IF NOT EXISTS `usersAudit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3804 ;

-- --------------------------------------------------------

--
-- Table structure for table `usersEducation`
--

CREATE TABLE IF NOT EXISTS `usersEducation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `schoolUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` int(3) DEFAULT NULL,
  `dateFrom` date DEFAULT NULL,
  `dateTo` date DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school` (`school`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users education for cross link and foaf' AUTO_INCREMENT=116 ;

-- --------------------------------------------------------

--
-- Table structure for table `usersInterests`
--

CREATE TABLE IF NOT EXISTS `usersInterests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interest` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `interest` (`interest`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users interests for cross linking and foad' AUTO_INCREMENT=436 ;

-- --------------------------------------------------------

--
-- Table structure for table `vacancies`
--

CREATE TABLE IF NOT EXISTS `vacancies` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regionID` int(2) DEFAULT NULL,
  `specification` text COLLATE utf8_unicode_ci,
  `salary` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8_unicode_ci DEFAULT '1',
  `live` date DEFAULT NULL,
  `expire` date DEFAULT NULL,
  `createdBy` int(3) DEFAULT NULL,
  `updatedBy` int(3) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `live` (`live`),
  KEY `expire` (`expire`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `status` (`status`),
  KEY `regionID` (`regionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=183 ;

-- --------------------------------------------------------

--
-- Table structure for table `vanarsdelltypes`
--

CREATE TABLE IF NOT EXISTS `vanarsdelltypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=843 ;

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE IF NOT EXISTS `volunteers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `managedBy` int(11) DEFAULT NULL,
  `suitableFor` int(2) DEFAULT NULL,
  `length` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `assignedTo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Volunteer opportunities with the scheme' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `weartypes`
--

CREATE TABLE IF NOT EXISTS `weartypes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomismaID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `valid` (`valid`),
  KEY `createdBy_2` (`createdBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `webServices`
--

CREATE TABLE IF NOT EXISTS `webServices` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serviceUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service` (`service`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Web services for social networking' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `workflowstages`
--

CREATE TABLE IF NOT EXISTS `workflowstages` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `workflowstage` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workflowstage` (`workflowstage`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
