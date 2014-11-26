-- phpMyAdmin SQL Dump
-- version 4.2.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 02, 2014 at 08:55 AM
-- Server version: 5.6.21
-- PHP Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `antiquitiesTwo`
--

-- --------------------------------------------------------

--
-- Table structure for table `abbreviations`
--

CREATE TABLE IF NOT EXISTS `abbreviations` (
`id` int(11) NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expanded` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Abbreviations in use on the database';

-- --------------------------------------------------------

--
-- Table structure for table `abcNumbers`
--

CREATE TABLE IF NOT EXISTS `abcNumbers` (
`id` int(6) NOT NULL,
  `term` int(5) NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(6) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ABC Chris Rudd Numbers';

-- --------------------------------------------------------

--
-- Table structure for table `accreditedMuseums`
--

CREATE TABLE IF NOT EXISTS `accreditedMuseums` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='A list of accredited museums that can bid for Treasure';

-- --------------------------------------------------------

--
-- Table structure for table `accreditedRegions`
--

CREATE TABLE IF NOT EXISTS `accreditedRegions` (
`id` int(11) NOT NULL,
  `regionName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Accredited Museum regions';

-- --------------------------------------------------------

--
-- Table structure for table `accreditedStatus`
--

CREATE TABLE IF NOT EXISTS `accreditedStatus` (
`id` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Accredited Museum Status';

-- --------------------------------------------------------

--
-- Table structure for table `agreedTreasureValuations`
--

CREATE TABLE IF NOT EXISTS `agreedTreasureValuations` (
`id` int(11) NOT NULL,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `value` int(12) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `dateOfValuation` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Valuations for Treasure cases';

-- --------------------------------------------------------

--
-- Table structure for table `allentypes`
--

CREATE TABLE IF NOT EXISTS `allentypes` (
`id` int(10) unsigned NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT '56'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Allen Types for Iron Age coins';

-- --------------------------------------------------------

--
-- Table structure for table `approveReject`
--

CREATE TABLE IF NOT EXISTS `approveReject` (
`id` int(11) NOT NULL,
  `status` enum('Approved','Rejected') COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Approved and rejected accounts';

-- --------------------------------------------------------

--
-- Table structure for table `archaeology`
--

CREATE TABLE IF NOT EXISTS `archaeology` (
`id` int(11) NOT NULL,
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
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Archaeological context information for hoards';

-- --------------------------------------------------------

--
-- Table structure for table `archaeologyAudit`
--

CREATE TABLE IF NOT EXISTS `archaeologyAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archfeature`
--

CREATE TABLE IF NOT EXISTS `archfeature` (
`id` int(11) unsigned NOT NULL,
  `feature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Archaeological features for hoards (archaeological context information)';

-- --------------------------------------------------------

--
-- Table structure for table `archsiteclass`
--

CREATE TABLE IF NOT EXISTS `archsiteclass` (
`id` int(11) unsigned NOT NULL,
  `siteclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Classes of archaeological site for hoards (archaeological context information)';

-- --------------------------------------------------------

--
-- Table structure for table `archsitetype`
--

CREATE TABLE IF NOT EXISTS `archsitetype` (
`id` int(11) unsigned NOT NULL,
  `sitetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of archaeological site for hoards (archaeological context information)';

-- --------------------------------------------------------

--
-- Table structure for table `bibliography`
--

CREATE TABLE IF NOT EXISTS `bibliography` (
`id` int(10) unsigned NOT NULL,
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
  `pubID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE IF NOT EXISTS `bookmarks` (
`id` int(4) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Social Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `categoriescoins`
--

CREATE TABLE IF NOT EXISTS `categoriescoins` (
`id` int(10) unsigned NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `periodID` int(10) unsigned DEFAULT NULL,
  `valid` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Categories for medieval coins';

-- --------------------------------------------------------

--
-- Table structure for table `cciVa`
--

CREATE TABLE IF NOT EXISTS `cciVa` (
`id` int(11) NOT NULL,
  `cciNumber` varchar(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `va_type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certaintytypes`
--

CREATE TABLE IF NOT EXISTS `certaintytypes` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(11) unsigned DEFAULT '1',
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classesJettonGroups`
--

CREATE TABLE IF NOT EXISTS `classesJettonGroups` (
`id` int(2) NOT NULL,
  `classID` int(2) DEFAULT NULL,
  `groupID` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Link table for jetton groups and classes';

-- --------------------------------------------------------

--
-- Table structure for table `coinclassifications`
--

CREATE TABLE IF NOT EXISTS `coinclassifications` (
`id` int(11) NOT NULL,
  `period` tinyint(2) DEFAULT NULL,
  `referenceName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `valid` tinyint(4) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Roman & iron age coins classifications';

-- --------------------------------------------------------

--
-- Table structure for table `coincountry_origin`
--

CREATE TABLE IF NOT EXISTS `coincountry_origin` (
`id` int(11) NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Countries of origin for coin groups';

-- --------------------------------------------------------

--
-- Table structure for table `coins`
--

CREATE TABLE IF NOT EXISTS `coins` (
`id` int(11) unsigned NOT NULL,
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
  `rrcID` int(6) DEFAULT NULL,
  `ricID` int(6) DEFAULT NULL,
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
  `intruder` int(1) unsigned DEFAULT NULL,
  `latestcoin` int(1) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(10) unsigned DEFAULT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `coinsAudit`
--

CREATE TABLE IF NOT EXISTS `coinsAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coinsummary`
--

CREATE TABLE IF NOT EXISTS `coinsummary` (
`id` int(11) NOT NULL,
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
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Coin summaries for hoards';

-- --------------------------------------------------------

--
-- Table structure for table `coins_denomxruler`
--

CREATE TABLE IF NOT EXISTS `coins_denomxruler` (
`ID` int(10) unsigned NOT NULL,
  `denomID` int(3) DEFAULT NULL,
  `rulerID` int(10) unsigned NOT NULL DEFAULT '0',
  `periodID` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Realtions between the Rulers and Denominations for coins';

-- --------------------------------------------------------

--
-- Table structure for table `coins_rulers`
--

CREATE TABLE IF NOT EXISTS `coins_rulers` (
`id` int(11) NOT NULL,
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
  `last_udpated_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coinxclass`
--

CREATE TABLE IF NOT EXISTS `coinxclass` (
`id` int(10) unsigned NOT NULL,
  `findID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classID` int(10) unsigned NOT NULL DEFAULT '0',
  `vol_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` datetime DEFAULT '0000-00-00 00:00:00',
  `updatedBy` int(10) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` bigint(20) unsigned NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `completeness`
--

CREATE TABLE IF NOT EXISTS `completeness` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Site content';

-- --------------------------------------------------------

--
-- Table structure for table `contentAudit`
--

CREATE TABLE IF NOT EXISTS `contentAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `copyCoin`
--

CREATE TABLE IF NOT EXISTS `copyCoin` (
`id` int(11) NOT NULL,
  `fields` text COLLATE utf8_unicode_ci,
  `userID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Copy last find ';

-- --------------------------------------------------------

--
-- Table structure for table `copyFind`
--

CREATE TABLE IF NOT EXISTS `copyFind` (
`id` int(11) NOT NULL,
  `fields` text COLLATE utf8_unicode_ci,
  `userID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Copy last find ';

-- --------------------------------------------------------

--
-- Table structure for table `copyFindSpot`
--

CREATE TABLE IF NOT EXISTS `copyFindSpot` (
`id` int(11) NOT NULL,
  `fields` text COLLATE utf8_unicode_ci,
  `userID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Copy last find ';

-- --------------------------------------------------------

--
-- Table structure for table `copyHoards`
--

CREATE TABLE IF NOT EXISTS `copyHoards` (
`id` int(11) NOT NULL,
  `fields` text COLLATE utf8_unicode_ci,
  `userID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Copy last find ';

-- --------------------------------------------------------

--
-- Table structure for table `copyrights`
--

CREATE TABLE IF NOT EXISTS `copyrights` (
`id` int(11) NOT NULL,
  `copyright` text COLLATE utf8_unicode_ci,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Image copyrights';

-- --------------------------------------------------------

--
-- Table structure for table `coroners`
--

CREATE TABLE IF NOT EXISTS `coroners` (
`id` int(3) NOT NULL,
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
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counties`
--

CREATE TABLE IF NOT EXISTS `counties` (
`ID` int(11) NOT NULL,
  `county` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regionID` int(10) unsigned DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `iso` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `printable_name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso3` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countyToFlo`
--

CREATE TABLE IF NOT EXISTS `countyToFlo` (
`id` int(11) NOT NULL,
  `institutionID` int(11) NOT NULL,
  `countyID` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='County to recording institutions';

-- --------------------------------------------------------

--
-- Table structure for table `crimeTypes`
--

CREATE TABLE IF NOT EXISTS `crimeTypes` (
`id` int(3) NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Crime typologies';

-- --------------------------------------------------------

--
-- Table structure for table `cultures`
--

CREATE TABLE IF NOT EXISTS `cultures` (
`id` int(2) NOT NULL,
  `term` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmCultureID` int(6) NOT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','0') COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` int(3) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(3) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dataquality`
--

CREATE TABLE IF NOT EXISTS `dataquality` (
`id` int(11) unsigned NOT NULL,
  `rating` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Quality of the data in hoards';

-- --------------------------------------------------------

--
-- Table structure for table `datequalifiers`
--

CREATE TABLE IF NOT EXISTS `datequalifiers` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(11) unsigned DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `decmethods`
--

CREATE TABLE IF NOT EXISTS `decmethods` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','2') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `decstyles`
--

CREATE TABLE IF NOT EXISTS `decstyles` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','2') COLLATE utf8_unicode_ci DEFAULT '1',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `denominations`
--

CREATE TABLE IF NOT EXISTS `denominations` (
`id` int(11) unsigned NOT NULL,
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
  `updatedBy` int(10) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `denominations_rulers`
--

CREATE TABLE IF NOT EXISTS `denominations_rulers` (
`id` int(10) unsigned NOT NULL,
  `denomination_id` int(10) unsigned DEFAULT NULL,
  `ruler_id` int(10) unsigned DEFAULT NULL,
  `period_id` int(10) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT '56'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Realtions between the Rulers and Denominations for coins';

-- --------------------------------------------------------

--
-- Table structure for table `dieaxes`
--

CREATE TABLE IF NOT EXISTS `dieaxes` (
`id` int(2) NOT NULL,
  `die_axis_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` int(1) DEFAULT NULL,
  `createdBy` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `discmethods`
--

CREATE TABLE IF NOT EXISTS `discmethods` (
`id` int(50) unsigned NOT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` smallint(1) DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instock` tinyint(1) NOT NULL,
  `mimetype` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filesize` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Scheme pubications for download and reqest';

-- --------------------------------------------------------

--
-- Table structure for table `dynasties`
--

CREATE TABLE IF NOT EXISTS `dynasties` (
`id` int(2) NOT NULL,
  `dynasty` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wikipedia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_from` int(4) NOT NULL DEFAULT '0',
  `date_to` int(4) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL,
  `createdBy` int(3) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedBy` int(3) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edm`
--

CREATE TABLE IF NOT EXISTS `edm` (
  `id` int(11) NOT NULL,
  `member_id` int(10) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Edm signatories';

-- --------------------------------------------------------

--
-- Table structure for table `ehObjects`
--

CREATE TABLE IF NOT EXISTS `ehObjects` (
  `subject` int(6) NOT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emperors`
--

CREATE TABLE IF NOT EXISTS `emperors` (
`id` int(4) NOT NULL,
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
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `errorreports`
--

CREATE TABLE IF NOT EXISTS `errorreports` (
`id` bigint(20) unsigned NOT NULL,
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
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`id` mediumint(9) NOT NULL,
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
  `adultsAttend` int(11) NOT NULL,
  `childrenAttend` int(11) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `organisation` varchar(55) CHARACTER SET latin1 DEFAULT 'PAS'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eventtypes`
--

CREATE TABLE IF NOT EXISTS `eventtypes` (
`id` int(2) NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of events';

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
`id` int(3) NOT NULL,
  `question` text COLLATE utf8_unicode_ci,
  `answer` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createdBy` int(3) DEFAULT '0',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `updatedBy` int(3) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `findofnotereasons`
--

CREATE TABLE IF NOT EXISTS `findofnotereasons` (
`id` int(2) NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` timestamp NULL DEFAULT NULL,
  `createdBy` int(5) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finds`
--

CREATE TABLE IF NOT EXISTS `finds` (
`id` int(11) NOT NULL,
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
  `hoardcontainer` int(1) unsigned DEFAULT NULL,
  `dbpediaSlug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `findsAudit`
--

CREATE TABLE IF NOT EXISTS `findsAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT '0',
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `findspots`
--

CREATE TABLE IF NOT EXISTS `findspots` (
`id` int(11) NOT NULL,
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
  `elevation` double DEFAULT NULL,
  `knownas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `alsoknownas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `findspotsAudit`
--

CREATE TABLE IF NOT EXISTS `findspotsAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `findspotsBackup`
--

CREATE TABLE IF NOT EXISTS `findspotsBackup` (
`id` int(11) NOT NULL,
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
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `finds_images`
--

CREATE TABLE IF NOT EXISTS `finds_images` (
`id` int(11) unsigned NOT NULL,
  `image_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `find_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(10) unsigned DEFAULT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finds_publications`
--

CREATE TABLE IF NOT EXISTS `finds_publications` (
`ID` int(10) unsigned NOT NULL,
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
  `secreplica` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `findxfind`
--

CREATE TABLE IF NOT EXISTS `findxfind` (
`id` int(11) unsigned NOT NULL,
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
  `secreplica` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `geographyironage`
--

CREATE TABLE IF NOT EXISTS `geographyironage` (
`id` int(10) unsigned NOT NULL,
  `area` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tribe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `valid` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Geography data for the Iron Age coins';

-- --------------------------------------------------------

--
-- Table structure for table `geoplanetadjacent`
--

CREATE TABLE IF NOT EXISTS `geoplanetadjacent` (
  `PLACE_WOE_ID` int(11) NOT NULL,
  `ISO` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NEIGHBOUR_WOE_ID` int(11) NOT NULL
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
  `Name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `greekstates`
--

CREATE TABLE IF NOT EXISTS `greekstates` (
`id` int(10) unsigned NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='State dropdown values for Greek and Roman Provincial period ';

-- --------------------------------------------------------

--
-- Table structure for table `gridrefsources`
--

CREATE TABLE IF NOT EXISTS `gridrefsources` (
`id` int(11) unsigned NOT NULL,
  `term` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupsJettonsTypes`
--

CREATE TABLE IF NOT EXISTS `groupsJettonsTypes` (
`id` int(3) NOT NULL,
  `groupID` int(3) DEFAULT NULL,
  `typeID` int(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Jetton groups to types';

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

CREATE TABLE IF NOT EXISTS `help` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `heritagecrime`
--

CREATE TABLE IF NOT EXISTS `heritagecrime` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Heritage crime reports';

-- --------------------------------------------------------

--
-- Table structure for table `hers`
--

CREATE TABLE IF NOT EXISTS `hers` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` int(3) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hitlog`
--

CREATE TABLE IF NOT EXISTS `hitlog` (
`id` int(11) NOT NULL,
  `findID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `visited` datetime DEFAULT NULL,
  `ipAddress` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userAgent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hoards`
--

CREATE TABLE IF NOT EXISTS `hoards` (
`id` int(11) NOT NULL,
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
  `finderID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finder2ID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Hoard records';

-- --------------------------------------------------------

--
-- Table structure for table `hoardsAudit`
--

CREATE TABLE IF NOT EXISTS `hoardsAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT '0',
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hoards_finders`
--

CREATE TABLE IF NOT EXISTS `hoards_finders` (
`id` int(10) unsigned NOT NULL,
  `hoardID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finderID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `viewOrder` int(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Multiple finders per hoard record';

-- --------------------------------------------------------

--
-- Table structure for table `hoards_materials`
--

CREATE TABLE IF NOT EXISTS `hoards_materials` (
`id` int(10) unsigned NOT NULL,
  `hoardID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `materialID` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Multiple materials per hoard record';

-- --------------------------------------------------------

--
-- Table structure for table `imagetypes`
--

CREATE TABLE IF NOT EXISTS `imagetypes` (
`id` int(2) NOT NULL,
  `type` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(2) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE IF NOT EXISTS `institutions` (
`id` int(10) unsigned NOT NULL,
  `institution` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Recording institutions';

-- --------------------------------------------------------

--
-- Table structure for table `instLogos`
--

CREATE TABLE IF NOT EXISTS `instLogos` (
`id` int(11) unsigned NOT NULL,
  `image` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instID` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Institutional logos for partners';

-- --------------------------------------------------------

--
-- Table structure for table `ironagedenomxregion`
--

CREATE TABLE IF NOT EXISTS `ironagedenomxregion` (
`ID` int(10) unsigned NOT NULL,
  `denomID` int(10) unsigned NOT NULL DEFAULT '0',
  `regionID` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Denomination-to-Region relaations for the Iron Age coins';

-- --------------------------------------------------------

--
-- Table structure for table `ironageregionstribes`
--

CREATE TABLE IF NOT EXISTS `ironageregionstribes` (
  `id` int(3) NOT NULL,
  `regionID` int(3) NOT NULL,
  `tribeID` int(3) NOT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Region to tribe lookup table';

-- --------------------------------------------------------

--
-- Table structure for table `ironagerulerxregion`
--

CREATE TABLE IF NOT EXISTS `ironagerulerxregion` (
`ID` int(10) unsigned NOT NULL,
  `rulerID` int(10) unsigned NOT NULL DEFAULT '0',
  `regionID` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ruler-to-Region relations for the Iron Age coins';

-- --------------------------------------------------------

--
-- Table structure for table `ironagetribes`
--

CREATE TABLE IF NOT EXISTS `ironagetribes` (
`id` int(2) NOT NULL,
  `tribe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmTribeID` int(6) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Iron Age tribes ';

-- --------------------------------------------------------

--
-- Table structure for table `issuers`
--

CREATE TABLE IF NOT EXISTS `issuers` (
`id` int(4) NOT NULL,
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
  `created_by` int(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE IF NOT EXISTS `issues` (
`id` int(11) NOT NULL,
  `issueTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issueDescription` text COLLATE utf8_unicode_ci,
  `resolutionApplied` text COLLATE utf8_unicode_ci,
  `status` tinyint(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Issues raised with the database';

-- --------------------------------------------------------

--
-- Table structure for table `issueStatuses`
--

CREATE TABLE IF NOT EXISTS `issueStatuses` (
`id` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Issue status';

-- --------------------------------------------------------

--
-- Table structure for table `jettonClasses`
--

CREATE TABLE IF NOT EXISTS `jettonClasses` (
`id` int(11) NOT NULL,
  `className` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Jetton and token classes';

-- --------------------------------------------------------

--
-- Table structure for table `jettonGroup`
--

CREATE TABLE IF NOT EXISTS `jettonGroup` (
`id` int(11) NOT NULL,
  `groupName` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Jetton groupings';

-- --------------------------------------------------------

--
-- Table structure for table `jettonTypes`
--

CREATE TABLE IF NOT EXISTS `jettonTypes` (
`id` int(11) NOT NULL,
  `typeName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Jetton types';

-- --------------------------------------------------------

--
-- Table structure for table `landscapetopography`
--

CREATE TABLE IF NOT EXISTS `landscapetopography` (
`id` int(11) unsigned NOT NULL,
  `feature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Landscape and topography for hoards (archaeological context information)';

-- --------------------------------------------------------

--
-- Table structure for table `landuses`
--

CREATE TABLE IF NOT EXISTS `landuses` (
`id` int(11) unsigned NOT NULL,
  `oldID` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `belongsto` int(6) DEFAULT NULL,
  `valid` tinyint(6) unsigned DEFAULT '1',
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `licenseType`
--

CREATE TABLE IF NOT EXISTS `licenseType` (
`id` int(11) NOT NULL,
  `license` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flickrID` int(11) DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `acronym` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `createdBy` int(6) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(6) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='License types for images';

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
`id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8_unicode_ci,
  `type` int(2) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loginRedirect`
--

CREATE TABLE IF NOT EXISTS `loginRedirect` (
`id` int(11) NOT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Login redirects';

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE IF NOT EXISTS `logins` (
`id` int(11) NOT NULL,
  `loginDate` datetime DEFAULT NULL,
  `ipAddress` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userAgent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Login user history';

-- --------------------------------------------------------

--
-- Table structure for table `macktypes`
--

CREATE TABLE IF NOT EXISTS `macktypes` (
`id` int(10) unsigned NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT '56'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mack Types for Iron Age coins';

-- --------------------------------------------------------

--
-- Table structure for table `mailinglist`
--

CREATE TABLE IF NOT EXISTS `mailinglist` (
`id` int(11) NOT NULL,
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
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mailing list sign ups';

-- --------------------------------------------------------

--
-- Table structure for table `manufactures`
--

CREATE TABLE IF NOT EXISTS `manufactures` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` smallint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maporigins`
--

CREATE TABLE IF NOT EXISTS `maporigins` (
`id` int(11) NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Origins of grid references';

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(8) DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `parentID` int(50) unsigned DEFAULT NULL,
  `valid` tinyint(6) unsigned NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mda_obj_prefs`
--

CREATE TABLE IF NOT EXISTS `mda_obj_prefs` (
`ID` int(11) unsigned NOT NULL,
  `THE_TE_UID_1` int(11) unsigned DEFAULT NULL,
  `THE_TE_UID_2` int(11) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mda_obj_rels`
--

CREATE TABLE IF NOT EXISTS `mda_obj_rels` (
`ID` int(11) unsigned NOT NULL,
  `TH_T_U_UID_1` int(11) unsigned DEFAULT NULL,
  `TH_T_U_UID_2` int(11) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mda_obj_uses`
--

CREATE TABLE IF NOT EXISTS `mda_obj_uses` (
`ID` int(11) unsigned NOT NULL,
  `TH_T_U_UID` int(11) unsigned DEFAULT NULL,
  `TERM` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CLA_GR_UID` smallint(6) unsigned DEFAULT NULL,
  `BROAD_TERM_U_UID` int(11) unsigned DEFAULT NULL,
  `TOP_TERM_U_UID` int(11) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medievalcategories`
--

CREATE TABLE IF NOT EXISTS `medievalcategories` (
`id` int(10) unsigned NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `periodID` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medievaltypes`
--

CREATE TABLE IF NOT EXISTS `medievaltypes` (
`id` int(10) unsigned NOT NULL,
  `rulerID` int(10) unsigned DEFAULT NULL,
  `periodID` int(10) unsigned DEFAULT NULL,
  `datefrom` int(11) DEFAULT NULL,
  `dateto` int(11) DEFAULT NULL,
  `categoryID` int(10) unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Coin types';

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Log of messages from contact us form';

-- --------------------------------------------------------

--
-- Table structure for table `mints`
--

CREATE TABLE IF NOT EXISTS `mints` (
`id` int(11) unsigned NOT NULL,
  `period` int(10) unsigned DEFAULT NULL,
  `old_period` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mint_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomismaID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pleiadesID` int(11) DEFAULT NULL,
  `geonamesID` int(11) DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `woeid` int(11) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `valid` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `createdBy` int(11) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mints_rulers`
--

CREATE TABLE IF NOT EXISTS `mints_rulers` (
`id` int(10) unsigned NOT NULL,
  `ruler_id` int(10) unsigned NOT NULL DEFAULT '0',
  `mint_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mint_reversetype`
--

CREATE TABLE IF NOT EXISTS `mint_reversetype` (
`id` int(11) NOT NULL,
  `mintID` int(4) DEFAULT NULL,
  `reverseID` int(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mint to reverse link table';

-- --------------------------------------------------------

--
-- Table structure for table `monarchs`
--

CREATE TABLE IF NOT EXISTS `monarchs` (
`id` int(2) NOT NULL,
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
  `updatedby` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moneyers`
--

CREATE TABLE IF NOT EXISTS `moneyers` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `viaf` int(11) DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `dbpediaID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wikipediaEntry` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Roman Republic Moneyers';

-- --------------------------------------------------------

--
-- Table structure for table `myresearch`
--

CREATE TABLE IF NOT EXISTS `myresearch` (
`id` int(11) NOT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `public` int(1) NOT NULL DEFAULT '0',
  `createdBy` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Research catalogues';

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
`id` int(4) NOT NULL,
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
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `oai_pmh_repository_tokens`
--

CREATE TABLE IF NOT EXISTS `oai_pmh_repository_tokens` (
`id` int(10) unsigned NOT NULL,
  `verb` enum('ListIdentifiers','ListRecords','ListSets') COLLATE utf8_unicode_ci NOT NULL,
  `metadata_prefix` text COLLATE utf8_unicode_ci NOT NULL,
  `cursor` int(10) unsigned NOT NULL DEFAULT '0',
  `from` datetime DEFAULT NULL,
  `until` datetime DEFAULT NULL,
  `set` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiration` datetime NOT NULL,
  `ipaddress` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `useragent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `oauthTokens`
--

CREATE TABLE IF NOT EXISTS `oauthTokens` (
`id` int(11) NOT NULL,
  `accessToken` text COLLATE utf8_unicode_ci NOT NULL,
  `tokenSecret` text COLLATE utf8_unicode_ci NOT NULL,
  `service` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sessionHandle` text COLLATE utf8_unicode_ci NOT NULL,
  `guid` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `expires` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Oauth tokens';

-- --------------------------------------------------------

--
-- Table structure for table `objectterms`
--

CREATE TABLE IF NOT EXISTS `objectterms` (
`id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `ehID` int(6) DEFAULT NULL,
  `term` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `indexTerm` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scopeNote` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `claUid` smallint(6) unsigned DEFAULT NULL,
  `status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oldrulers`
--

CREATE TABLE IF NOT EXISTS `oldrulers` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `organisations`
--

CREATE TABLE IF NOT EXISTS `organisations` (
`id` int(11) NOT NULL,
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
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `organisationsAudit`
--

CREATE TABLE IF NOT EXISTS `organisationsAudit` (
`id` int(11) unsigned NOT NULL,
  `orgID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `osCounties`
--

CREATE TABLE IF NOT EXISTS `osCounties` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='OS counties';

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
  `sheet3` int(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='OSDATA 1:50000';

-- --------------------------------------------------------

--
-- Table structure for table `osDistricts`
--

CREATE TABLE IF NOT EXISTS `osDistricts` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='OS regions';

-- --------------------------------------------------------

--
-- Table structure for table `osParishes`
--

CREATE TABLE IF NOT EXISTS `osParishes` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `osRegions`
--

CREATE TABLE IF NOT EXISTS `osRegions` (
`id` int(2) NOT NULL,
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
  `valid` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ordanance Survey regions';

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
`id` int(11) NOT NULL,
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
  `canRecord` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peopleAudit`
--

CREATE TABLE IF NOT EXISTS `peopleAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) NOT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peopletypes`
--

CREATE TABLE IF NOT EXISTS `peopletypes` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE IF NOT EXISTS `periods` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `term` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(8) DEFAULT NULL,
  `ehTerm` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
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
`ID` int(11) unsigned NOT NULL,
  `active` tinyint(4) unsigned DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
`id` int(11) unsigned NOT NULL,
  `active` tinyint(4) unsigned DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preservations`
--

CREATE TABLE IF NOT EXISTS `preservations` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(6) DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','0') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `primaryactivities`
--

CREATE TABLE IF NOT EXISTS `primaryactivities` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(11) unsigned DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projecttypes`
--

CREATE TABLE IF NOT EXISTS `projecttypes` (
`id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of research project';

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE IF NOT EXISTS `publications` (
`id` int(11) NOT NULL,
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
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publicationtypes`
--

CREATE TABLE IF NOT EXISTS `publicationtypes` (
`id` int(11) NOT NULL,
  `term` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE IF NOT EXISTS `quotes` (
`id` int(11) NOT NULL,
  `quote` text COLLATE utf8_unicode_ci,
  `quotedBy` text COLLATE utf8_unicode_ci,
  `type` varchar(155) COLLATE utf8_unicode_ci DEFAULT 'quote',
  `status` int(1) DEFAULT '1',
  `expire` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Quotes about the Scheme';

-- --------------------------------------------------------

--
-- Table structure for table `rallies`
--

CREATE TABLE IF NOT EXISTS `rallies` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Rally locations';

-- --------------------------------------------------------

--
-- Table structure for table `rallyXflo`
--

CREATE TABLE IF NOT EXISTS `rallyXflo` (
`id` int(11) NOT NULL,
  `rallyID` int(11) DEFAULT NULL,
  `staffID` int(11) DEFAULT NULL,
  `dateFrom` date DEFAULT NULL,
  `dateTo` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Flos attending a rally';

-- --------------------------------------------------------

--
-- Table structure for table `recmethods`
--

CREATE TABLE IF NOT EXISTS `recmethods` (
`id` int(11) unsigned NOT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Recovery methods for hoards (archaeological context information)';

-- --------------------------------------------------------

--
-- Table structure for table `reeceperiods`
--

CREATE TABLE IF NOT EXISTS `reeceperiods` (
`id` int(11) NOT NULL,
  `period_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_range` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_period` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` int(11) DEFAULT NULL,
  `valid` smallint(6) DEFAULT NULL,
  `createdBy` tinyint(6) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` tinyint(6) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reeceperiods_rulers`
--

CREATE TABLE IF NOT EXISTS `reeceperiods_rulers` (
`id` int(10) unsigned NOT NULL,
  `ruler_id` int(10) NOT NULL,
  `reeceperiod_id` int(10) NOT NULL,
  `periodID` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL DEFAULT '56',
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL DEFAULT '56'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
`id` int(10) unsigned NOT NULL,
  `region` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reliability`
--

CREATE TABLE IF NOT EXISTS `reliability` (
`id` int(1) NOT NULL,
  `term` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Reliability of evidence';

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE IF NOT EXISTS `replies` (
`id` int(11) NOT NULL,
  `messagetext` text COLLATE utf8_unicode_ci,
  `messageID` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Replies to submitted messages';

-- --------------------------------------------------------

--
-- Table structure for table `researchprojects`
--

CREATE TABLE IF NOT EXISTS `researchprojects` (
`id` int(11) unsigned NOT NULL,
  `title` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `investigator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) unsigned DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) unsigned DEFAULT NULL,
  `valid` tinyint(1) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='List of research projects';

-- --------------------------------------------------------

--
-- Table structure for table `reverses`
--

CREATE TABLE IF NOT EXISTS `reverses` (
`id` int(11) NOT NULL,
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
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revtypes`
--

CREATE TABLE IF NOT EXISTS `revtypes` (
`id` int(11) NOT NULL,
  `type` text COLLATE utf8_unicode_ci,
  `translation` tinytext COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `gendate` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reeceID` int(2) NOT NULL,
  `common` enum('1','2') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` varchar(11) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fourth Century reverse types, Roman coins';

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
`id` int(2) unsigned NOT NULL,
  `role` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT '56'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='User roles';

-- --------------------------------------------------------

--
-- Table structure for table `romandenoms`
--

CREATE TABLE IF NOT EXISTS `romandenoms` (
`id` int(3) NOT NULL,
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
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `romanmints`
--

CREATE TABLE IF NOT EXISTS `romanmints` (
`id` int(11) NOT NULL,
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
  `updated_on` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rulerImages`
--

CREATE TABLE IF NOT EXISTS `rulerImages` (
`id` int(11) unsigned NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `caption` text COLLATE utf8_unicode_ci,
  `rulerID` int(11) DEFAULT NULL,
  `zoomroute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filesize` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mimetype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Ruler images';

-- --------------------------------------------------------

--
-- Table structure for table `rulers`
--

CREATE TABLE IF NOT EXISTS `rulers` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ruler_reversetype`
--

CREATE TABLE IF NOT EXISTS `ruler_reversetype` (
`id` int(11) NOT NULL,
  `reverseID` int(4) DEFAULT NULL,
  `rulerID` int(4) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Reverse types to ruler link table';

-- --------------------------------------------------------

--
-- Table structure for table `savedSearches`
--

CREATE TABLE IF NOT EXISTS `savedSearches` (
`id` int(11) NOT NULL,
  `searchString` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `searchDescription` text COLLATE utf8_unicode_ci,
  `public` tinyint(1) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Saved searchs referenced to users';

-- --------------------------------------------------------

--
-- Table structure for table `scheduledMonuments`
--

CREATE TABLE IF NOT EXISTS `scheduledMonuments` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Scheduled monuments';

-- --------------------------------------------------------

--
-- Table structure for table `searches`
--

CREATE TABLE IF NOT EXISTS `searches` (
`id` int(11) NOT NULL,
  `searchString` text COLLATE utf8_unicode_ci,
  `date` datetime DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `ipaddress` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `useragent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semanticTags`
--

CREATE TABLE IF NOT EXISTS `semanticTags` (
`id` int(11) NOT NULL,
  `contentID` int(11) DEFAULT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contenttype` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `origin` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `woeid` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Opencalais tagged content';

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE IF NOT EXISTS `slides` (
`imageID` int(11) unsigned NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `institution` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
`id` int(4) NOT NULL,
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
  `alumni` enum('1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `blog_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staffregions`
--

CREATE TABLE IF NOT EXISTS `staffregions` (
`id` int(4) NOT NULL,
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
  `rssfeed` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staffroles`
--

CREATE TABLE IF NOT EXISTS `staffroles` (
`id` int(11) NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(4) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE IF NOT EXISTS `statuses` (
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
`id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `subperiods`
--

CREATE TABLE IF NOT EXISTS `subperiods` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(11) unsigned DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `subsequentActions`
--

CREATE TABLE IF NOT EXISTS `subsequentActions` (
`id` int(11) NOT NULL,
  `action` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Subsequent actions by flos';

-- --------------------------------------------------------

--
-- Table structure for table `suggestedResearch`
--

CREATE TABLE IF NOT EXISTS `suggestedResearch` (
`id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `period` int(2) DEFAULT NULL,
  `level` int(2) DEFAULT NULL,
  `taken` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(4) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` bigint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Suggested research topice from the Scheme';

-- --------------------------------------------------------

--
-- Table structure for table `summaryAudit`
--

CREATE TABLE IF NOT EXISTS `summaryAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surftreatments`
--

CREATE TABLE IF NOT EXISTS `surftreatments` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bmID` int(6) NOT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','2') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT '56',
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `systemroles`
--

CREATE TABLE IF NOT EXISTS `systemroles` (
`id` int(11) unsigned NOT NULL,
  `role` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='System roles on database';

-- --------------------------------------------------------

--
-- Table structure for table `taggedcontent`
--

CREATE TABLE IF NOT EXISTS `taggedcontent` (
`id` int(11) NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` text COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Content tags';

-- --------------------------------------------------------

--
-- Table structure for table `tempfindspots`
--

CREATE TABLE IF NOT EXISTS `tempfindspots` (
`id` int(6) NOT NULL,
  `knownas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gridref` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `easting` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `northing` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smr_ref` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `findspot_desc` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `terminalreason`
--

CREATE TABLE IF NOT EXISTS `terminalreason` (
`id` int(11) unsigned NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Reasons for the terminal date in coin hoards';

-- --------------------------------------------------------

--
-- Table structure for table `thes_chronuk2`
--

CREATE TABLE IF NOT EXISTS `thes_chronuk2` (
`id` int(11) NOT NULL,
  `termID` int(11) NOT NULL DEFAULT '0',
  `partof` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treasureActions`
--

CREATE TABLE IF NOT EXISTS `treasureActions` (
`id` int(11) NOT NULL,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `actionID` int(2) NOT NULL,
  `actionTaken` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Actions associated with Treasure case';

-- --------------------------------------------------------

--
-- Table structure for table `treasureActionTypes`
--

CREATE TABLE IF NOT EXISTS `treasureActionTypes` (
`id` int(3) NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Actions that can be used in Treasure management';

-- --------------------------------------------------------

--
-- Table structure for table `treasureAssignations`
--

CREATE TABLE IF NOT EXISTS `treasureAssignations` (
`id` int(11) NOT NULL,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `curatorID` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `chaseDate` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Assignations for Treasure cases';

-- --------------------------------------------------------

--
-- Table structure for table `treasureStatus`
--

CREATE TABLE IF NOT EXISTS `treasureStatus` (
`id` tinyint(2) NOT NULL,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Status of Treasure case';

-- --------------------------------------------------------

--
-- Table structure for table `treasureStatusTypes`
--

CREATE TABLE IF NOT EXISTS `treasureStatusTypes` (
`id` int(3) NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure management status list';

-- --------------------------------------------------------

--
-- Table structure for table `treasureValuations`
--

CREATE TABLE IF NOT EXISTS `treasureValuations` (
`id` int(11) NOT NULL,
  `treasureID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `valuerID` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `value` double unsigned DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `dateOfValuation` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Valuations for Treasure cases';

-- --------------------------------------------------------

--
-- Table structure for table `tvcDates`
--

CREATE TABLE IF NOT EXISTS `tvcDates` (
`id` int(11) NOT NULL,
  `secuid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `location` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure Valuation Committe dates';

-- --------------------------------------------------------

--
-- Table structure for table `tvcDatesToCases`
--

CREATE TABLE IF NOT EXISTS `tvcDatesToCases` (
`id` int(11) NOT NULL,
  `treasureID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tvcID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure Valuation committe to object ';

-- --------------------------------------------------------

--
-- Table structure for table `userOnlineAccounts`
--

CREATE TABLE IF NOT EXISTS `userOnlineAccounts` (
`id` int(11) NOT NULL,
  `account` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accountName` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='online accounts for users for foaf';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usersAudit`
--

CREATE TABLE IF NOT EXISTS `usersAudit` (
`id` int(11) unsigned NOT NULL,
  `recordID` int(11) DEFAULT NULL,
  `entityID` int(11) DEFAULT NULL,
  `editID` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fieldName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beforeValue` mediumtext COLLATE utf8_unicode_ci,
  `afterValue` mediumtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usersEducation`
--

CREATE TABLE IF NOT EXISTS `usersEducation` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users education for cross link and foaf';

-- --------------------------------------------------------

--
-- Table structure for table `usersInterests`
--

CREATE TABLE IF NOT EXISTS `usersInterests` (
`id` int(11) NOT NULL,
  `interest` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users interests for cross linking and foad';

-- --------------------------------------------------------

--
-- Table structure for table `vacancies`
--

CREATE TABLE IF NOT EXISTS `vacancies` (
`id` int(2) NOT NULL,
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
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vanarsdelltypes`
--

CREATE TABLE IF NOT EXISTS `vanarsdelltypes` (
`id` int(11) NOT NULL,
  `type` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE IF NOT EXISTS `volunteers` (
`id` int(11) NOT NULL,
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
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Volunteer opportunities with the scheme';

-- --------------------------------------------------------

--
-- Table structure for table `weartypes`
--

CREATE TABLE IF NOT EXISTS `weartypes` (
`id` int(11) unsigned NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webServices`
--

CREATE TABLE IF NOT EXISTS `webServices` (
`id` int(3) NOT NULL,
  `service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serviceUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Web services for social networking';

-- --------------------------------------------------------

--
-- Table structure for table `workflowstages`
--

CREATE TABLE IF NOT EXISTS `workflowstages` (
`id` int(2) NOT NULL,
  `workflowstage` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termdesc` text COLLATE utf8_unicode_ci,
  `valid` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abbreviations`
--
ALTER TABLE `abbreviations`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `abcNumbers`
--
ALTER TABLE `abcNumbers`
 ADD PRIMARY KEY (`id`), ADD KEY `term` (`term`);

--
-- Indexes for table `accreditedMuseums`
--
ALTER TABLE `accreditedMuseums`
 ADD PRIMARY KEY (`id`), ADD KEY `accreditedNumber` (`accreditedNumber`), ADD KEY `woeid` (`woeid`), ADD KEY `geohash` (`geohash`);

--
-- Indexes for table `accreditedRegions`
--
ALTER TABLE `accreditedRegions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accreditedStatus`
--
ALTER TABLE `accreditedStatus`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agreedTreasureValuations`
--
ALTER TABLE `agreedTreasureValuations`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allentypes`
--
ALTER TABLE `allentypes`
 ADD PRIMARY KEY (`id`), ADD KEY `type` (`type`), ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `approveReject`
--
ALTER TABLE `approveReject`
 ADD PRIMARY KEY (`id`), ADD KEY `status` (`status`);

--
-- Indexes for table `archaeology`
--
ALTER TABLE `archaeology`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archaeologyAudit`
--
ALTER TABLE `archaeologyAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `coinID` (`recordID`), ADD KEY `findID` (`entityID`);

--
-- Indexes for table `archfeature`
--
ALTER TABLE `archfeature`
 ADD PRIMARY KEY (`id`), ADD KEY `feature` (`feature`);

--
-- Indexes for table `archsiteclass`
--
ALTER TABLE `archsiteclass`
 ADD PRIMARY KEY (`id`), ADD KEY `siteclass` (`siteclass`);

--
-- Indexes for table `archsitetype`
--
ALTER TABLE `archsitetype`
 ADD PRIMARY KEY (`id`), ADD KEY `sitetype` (`sitetype`);

--
-- Indexes for table `bibliography`
--
ALTER TABLE `bibliography`
 ADD PRIMARY KEY (`id`), ADD KEY `pubID` (`pubID`), ADD KEY `findID` (`findID`), ADD KEY `secuid` (`secuid`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categoriescoins`
--
ALTER TABLE `categoriescoins`
 ADD PRIMARY KEY (`id`), ADD KEY `periodID` (`periodID`), ADD KEY `valid` (`valid`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`);

--
-- Indexes for table `cciVa`
--
ALTER TABLE `cciVa`
 ADD PRIMARY KEY (`id`), ADD KEY `cciNumber` (`cciNumber`);

--
-- Indexes for table `certaintytypes`
--
ALTER TABLE `certaintytypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classesJettonGroups`
--
ALTER TABLE `classesJettonGroups`
 ADD PRIMARY KEY (`id`), ADD KEY `classID` (`classID`), ADD KEY `groupID` (`groupID`);

--
-- Indexes for table `coinclassifications`
--
ALTER TABLE `coinclassifications`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coincountry_origin`
--
ALTER TABLE `coincountry_origin`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coins`
--
ALTER TABLE `coins`
 ADD PRIMARY KEY (`id`), ADD KEY `tribe` (`tribe`), ADD KEY `revtypeID` (`revtypeID`), ADD KEY `denomination` (`denomination`), ADD KEY `ruler_id` (`ruler_id`), ADD KEY `ruler2_id` (`ruler2_id`), ADD KEY `reeceID` (`reeceID`), ADD KEY `die_axis_measurement` (`die_axis_measurement`), ADD KEY `categoryID` (`categoryID`), ADD KEY `greekstateID` (`greekstateID`), ADD KEY `geographyID` (`geographyID`), ADD KEY `allen_type` (`allen_type`), ADD KEY `mack_type` (`mack_type`), ADD KEY `bmc_type` (`bmc_type`), ADD KEY `rudd_type` (`rudd_type`), ADD KEY `va_type` (`va_type`), ADD KEY `typeID` (`typeID`), ADD KEY `findID` (`findID`), ADD KEY `mint_id` (`mint_id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `moneyer` (`moneyer`), ADD KEY `status` (`status`), ADD KEY `cciNumber` (`cciNumber`), ADD KEY `institution` (`institution`), ADD KEY `pleiadesID` (`pleiadesID`), ADD FULLTEXT KEY `reverse_description` (`reverse_description`), ADD FULLTEXT KEY `obverse_inscription` (`obverse_inscription`), ADD FULLTEXT KEY `obverse_description` (`obverse_description`), ADD FULLTEXT KEY `reverse_inscription` (`reverse_inscription`);

--
-- Indexes for table `coinsAudit`
--
ALTER TABLE `coinsAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `coinID` (`recordID`), ADD KEY `findID` (`entityID`);

--
-- Indexes for table `coinsummary`
--
ALTER TABLE `coinsummary`
 ADD PRIMARY KEY (`id`), ADD KEY `hoardID` (`hoardID`), ADD KEY `broadperiod` (`broadperiod`), ADD KEY `denomination` (`denomination`), ADD KEY `numdate1` (`numdate1`), ADD KEY `numdate2` (`numdate2`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`);

--
-- Indexes for table `coins_denomxruler`
--
ALTER TABLE `coins_denomxruler`
 ADD PRIMARY KEY (`ID`), ADD KEY `denomID` (`denomID`), ADD KEY `rulerID` (`rulerID`), ADD KEY `periodID` (`periodID`);

--
-- Indexes for table `coins_rulers`
--
ALTER TABLE `coins_rulers`
 ADD PRIMARY KEY (`id`), ADD KEY `place` (`place`);

--
-- Indexes for table `coinxclass`
--
ALTER TABLE `coinxclass`
 ADD PRIMARY KEY (`id`), ADD KEY `findID` (`findID`), ADD KEY `classID` (`classID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`), ADD KEY `comment_approved` (`comment_approved`), ADD KEY `commentStatus` (`commentStatus`), ADD KEY `createdBy` (`createdBy`), ADD KEY `contentID` (`contentID`);

--
-- Indexes for table `completeness`
--
ALTER TABLE `completeness`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
 ADD PRIMARY KEY (`id`), ADD KEY `frontPage` (`frontPage`), ADD KEY `author` (`author`), ADD KEY `publishState` (`publishState`), ADD KEY `slug` (`slug`), ADD KEY `section` (`section`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`);

--
-- Indexes for table `contentAudit`
--
ALTER TABLE `contentAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`);

--
-- Indexes for table `copyCoin`
--
ALTER TABLE `copyCoin`
 ADD PRIMARY KEY (`id`), ADD KEY `userID` (`userID`);

--
-- Indexes for table `copyFind`
--
ALTER TABLE `copyFind`
 ADD PRIMARY KEY (`id`), ADD KEY `userID` (`userID`), ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `copyFindSpot`
--
ALTER TABLE `copyFindSpot`
 ADD PRIMARY KEY (`id`), ADD KEY `userID` (`userID`);

--
-- Indexes for table `copyHoards`
--
ALTER TABLE `copyHoards`
 ADD PRIMARY KEY (`id`), ADD KEY `userID` (`userID`), ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `copyrights`
--
ALTER TABLE `copyrights`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coroners`
--
ALTER TABLE `coroners`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `regionID` (`regionID`), ADD KEY `woeid` (`woeid`);

--
-- Indexes for table `counties`
--
ALTER TABLE `counties`
 ADD PRIMARY KEY (`ID`), ADD KEY `county` (`county`), ADD KEY `valid` (`valid`), ADD KEY `regionID` (`regionID`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
 ADD PRIMARY KEY (`iso`), ADD KEY `printable_name` (`printable_name`);

--
-- Indexes for table `countyToFlo`
--
ALTER TABLE `countyToFlo`
 ADD PRIMARY KEY (`id`), ADD KEY `institutionID` (`institutionID`), ADD KEY `countyID` (`countyID`);

--
-- Indexes for table `crimeTypes`
--
ALTER TABLE `crimeTypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cultures`
--
ALTER TABLE `cultures`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dataquality`
--
ALTER TABLE `dataquality`
 ADD PRIMARY KEY (`id`), ADD KEY `rating` (`rating`);

--
-- Indexes for table `datequalifiers`
--
ALTER TABLE `datequalifiers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `decmethods`
--
ALTER TABLE `decmethods`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `decstyles`
--
ALTER TABLE `decstyles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `denominations`
--
ALTER TABLE `denominations`
 ADD PRIMARY KEY (`id`), ADD KEY `denomination` (`denomination`), ADD KEY `period` (`period`), ADD KEY `valid` (`valid`);

--
-- Indexes for table `denominations_rulers`
--
ALTER TABLE `denominations_rulers`
 ADD PRIMARY KEY (`id`), ADD KEY `ruler_id` (`ruler_id`), ADD KEY `denomination_id` (`denomination_id`), ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `dieaxes`
--
ALTER TABLE `dieaxes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discmethods`
--
ALTER TABLE `discmethods`
 ADD PRIMARY KEY (`id`), ADD KEY `method` (`method`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dynasties`
--
ALTER TABLE `dynasties`
 ADD PRIMARY KEY (`id`), ADD KEY `wikipedia` (`wikipedia`);

--
-- Indexes for table `edm`
--
ALTER TABLE `edm`
 ADD PRIMARY KEY (`id`), ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `ehObjects`
--
ALTER TABLE `ehObjects`
 ADD PRIMARY KEY (`subject`), ADD KEY `label` (`label`);

--
-- Indexes for table `emperors`
--
ALTER TABLE `emperors`
 ADD PRIMARY KEY (`id`), ADD KEY `pasID` (`pasID`), ADD KEY `date_from` (`date_from`), ADD KEY `reeceID` (`reeceID`), ADD KEY `dynasty` (`dynasty`), ADD KEY `viaf` (`viaf`), ADD KEY `nomismaID` (`nomismaID`);

--
-- Indexes for table `errorreports`
--
ALTER TABLE `errorreports`
 ADD PRIMARY KEY (`id`), ADD KEY `comment_approved` (`comment_approved`), ADD KEY `comment_findID` (`comment_findID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`id`), ADD KEY `organisation` (`organisation`), ADD KEY `createdBy` (`createdBy`), ADD KEY `eventRegion` (`eventRegion`), ADD KEY `eventStartDate` (`eventStartDate`), ADD KEY `eventEndDate` (`eventEndDate`), ADD KEY `createdBy_2` (`createdBy`), ADD KEY `eventType` (`eventType`);

--
-- Indexes for table `eventtypes`
--
ALTER TABLE `eventtypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `findofnotereasons`
--
ALTER TABLE `findofnotereasons`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finds`
--
ALTER TABLE `finds`
 ADD PRIMARY KEY (`id`), ADD KEY `objecttype` (`objecttype`), ADD KEY `objdate1period` (`objdate1period`), ADD KEY `objdate2period` (`objdate2period`), ADD KEY `old_findID` (`old_findID`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `createdBy` (`createdBy`), ADD KEY `rallyID` (`rallyID`), ADD KEY `treasureID` (`treasureID`), ADD KEY `finderID` (`finderID`), ADD KEY `finder2ID` (`finder2ID`), ADD KEY `findofnotereason` (`findofnotereason`), ADD KEY `recorderID` (`recorderID`), ADD KEY `identifier1ID` (`identifier1ID`), ADD KEY `broadperiod` (`broadperiod`), ADD KEY `manmethod` (`manmethod`), ADD KEY `decmethod` (`decmethod`), ADD KEY `surftreat` (`surftreat`), ADD KEY `material1` (`material1`), ADD KEY `material2` (`material2`), ADD KEY `preservation` (`preservation`), ADD KEY `secuid` (`secuid`), ADD KEY `quantity` (`quantity`), ADD KEY `other_ref` (`other_ref`), ADD KEY `findofnote` (`findofnote`), ADD KEY `secwfstage` (`secwfstage`), ADD KEY `created` (`created`), ADD KEY `identifier2ID` (`identifier2ID`), ADD KEY `completeness` (`completeness`), ADD KEY `discmethod` (`discmethod`), ADD KEY `institution` (`institution`), ADD KEY `dbpediaSlug` (`dbpediaSlug`), ADD KEY `objdate1subperiod` (`objdate1subperiod`), ADD KEY `objdate2subperiod` (`objdate2subperiod`), ADD FULLTEXT KEY `description` (`description`), ADD FULLTEXT KEY `classification` (`classification`);

--
-- Indexes for table `findsAudit`
--
ALTER TABLE `findsAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `findID` (`recordID`), ADD KEY `entityID` (`entityID`);

--
-- Indexes for table `findspots`
--
ALTER TABLE `findspots`
 ADD PRIMARY KEY (`id`), ADD KEY `parish` (`parish`), ADD KEY `declong` (`declong`), ADD KEY `declat` (`declat`), ADD KEY `county` (`county`), ADD KEY `district` (`district`), ADD KEY `findID` (`findID`), ADD KEY `gridref` (`gridref`), ADD KEY `knownas` (`knownas`), ADD KEY `fourFigure` (`fourFigure`), ADD KEY `country` (`country`), ADD KEY `secuid` (`secuid`), ADD KEY `woeid` (`woeid`), ADD KEY `landusevalue` (`landusevalue`), ADD KEY `landusecode` (`landusecode`), ADD KEY `createdBy` (`createdBy`), ADD KEY `countyID` (`countyID`), ADD KEY `parishID` (`parishID`);

--
-- Indexes for table `findspotsAudit`
--
ALTER TABLE `findspotsAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `findspotID` (`entityID`), ADD KEY `findID` (`recordID`);

--
-- Indexes for table `findspotsBackup`
--
ALTER TABLE `findspotsBackup`
 ADD PRIMARY KEY (`id`), ADD KEY `parish` (`parish`), ADD KEY `declong` (`declong`), ADD KEY `declat` (`declat`), ADD KEY `county` (`county`), ADD KEY `district` (`district`), ADD KEY `findID` (`findID`), ADD KEY `gridref` (`gridref`), ADD KEY `knownas` (`knownas`), ADD KEY `fourFigure` (`fourFigure`), ADD KEY `country` (`country`), ADD KEY `secuid` (`secuid`), ADD KEY `woeid` (`woeid`), ADD KEY `landusevalue` (`landusevalue`), ADD KEY `landusecode` (`landusecode`), ADD KEY `createdBy` (`createdBy`), ADD KEY `countyID` (`countyID`);

--
-- Indexes for table `finds_images`
--
ALTER TABLE `finds_images`
 ADD PRIMARY KEY (`id`), ADD KEY `image_id` (`image_id`), ADD KEY `find_id` (`find_id`);

--
-- Indexes for table `finds_publications`
--
ALTER TABLE `finds_publications`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `IDX_secuid` (`secuid`), ADD KEY `IDX_findID` (`find_id`);

--
-- Indexes for table `findxfind`
--
ALTER TABLE `findxfind`
 ADD PRIMARY KEY (`id`), ADD KEY `find1ID` (`find1ID`), ADD KEY `find2ID` (`find2ID`);

--
-- Indexes for table `geographyironage`
--
ALTER TABLE `geographyironage`
 ADD PRIMARY KEY (`id`), ADD KEY `valid` (`valid`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `bmID` (`bmID`);

--
-- Indexes for table `geoplanetadjacent`
--
ALTER TABLE `geoplanetadjacent`
 ADD KEY `PLACE_WOE_ID` (`PLACE_WOE_ID`), ADD KEY `NEIGHBOUR_WOE_ID` (`NEIGHBOUR_WOE_ID`);

--
-- Indexes for table `geoplanetplaces`
--
ALTER TABLE `geoplanetplaces`
 ADD PRIMARY KEY (`WOE_ID`);

--
-- Indexes for table `greekstates`
--
ALTER TABLE `greekstates`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gridrefsources`
--
ALTER TABLE `gridrefsources`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupsJettonsTypes`
--
ALTER TABLE `groupsJettonsTypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help`
--
ALTER TABLE `help`
 ADD PRIMARY KEY (`id`), ADD KEY `frontPage` (`frontPage`);

--
-- Indexes for table `heritagecrime`
--
ALTER TABLE `heritagecrime`
 ADD PRIMARY KEY (`id`), ADD KEY `crimeType` (`crimeType`), ADD KEY `samID` (`samID`);

--
-- Indexes for table `hers`
--
ALTER TABLE `hers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hitlog`
--
ALTER TABLE `hitlog`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hoards`
--
ALTER TABLE `hoards`
 ADD PRIMARY KEY (`id`), ADD KEY `hoardID` (`hoardID`), ADD KEY `secuid` (`secuid`), ADD KEY `terminalyear1` (`terminalyear1`), ADD KEY `terminalyear2` (`terminalyear2`), ADD KEY `terminalreason` (`terminalreason`), ADD KEY `broadperiod` (`broadperiod`), ADD KEY `secwfstage` (`secwfstage`), ADD KEY `findofnote` (`findofnote`), ADD KEY `findofnotereason` (`findofnotereason`), ADD KEY `treasureID` (`treasureID`), ADD KEY `recorderID` (`recorderID`), ADD KEY `identifier1ID` (`identifier1ID`), ADD KEY `identifier2ID` (`identifier2ID`), ADD KEY `finderID` (`finderID`), ADD KEY `finder2ID` (`finder2ID`), ADD KEY `discmethod` (`discmethod`), ADD KEY `rallyID` (`rallyID`), ADD KEY `legacyID` (`legacyID`), ADD KEY `other_ref` (`other_ref`), ADD KEY `created` (`created`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `institution` (`institution`), ADD FULLTEXT KEY `description` (`description`);

--
-- Indexes for table `hoardsAudit`
--
ALTER TABLE `hoardsAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `findID` (`recordID`), ADD KEY `entityID` (`entityID`);

--
-- Indexes for table `hoards_finders`
--
ALTER TABLE `hoards_finders`
 ADD PRIMARY KEY (`id`), ADD KEY `hoardID` (`hoardID`), ADD KEY `finderID` (`finderID`);

--
-- Indexes for table `hoards_materials`
--
ALTER TABLE `hoards_materials`
 ADD PRIMARY KEY (`id`), ADD KEY `hoardID` (`hoardID`), ADD KEY `materialID` (`materialID`);

--
-- Indexes for table `imagetypes`
--
ALTER TABLE `imagetypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
 ADD PRIMARY KEY (`id`), ADD KEY `institution` (`institution`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `valid` (`valid`);

--
-- Indexes for table `instLogos`
--
ALTER TABLE `instLogos`
 ADD PRIMARY KEY (`id`), ADD KEY `instID` (`instID`);

--
-- Indexes for table `ironagedenomxregion`
--
ALTER TABLE `ironagedenomxregion`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ironageregionstribes`
--
ALTER TABLE `ironageregionstribes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ironagerulerxregion`
--
ALTER TABLE `ironagerulerxregion`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ironagetribes`
--
ALTER TABLE `ironagetribes`
 ADD PRIMARY KEY (`id`), ADD KEY `bmTribeID` (`bmTribeID`);

--
-- Indexes for table `issuers`
--
ALTER TABLE `issuers`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
 ADD PRIMARY KEY (`id`), ADD KEY `status` (`status`);

--
-- Indexes for table `issueStatuses`
--
ALTER TABLE `issueStatuses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jettonClasses`
--
ALTER TABLE `jettonClasses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jettonGroup`
--
ALTER TABLE `jettonGroup`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jettonTypes`
--
ALTER TABLE `jettonTypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landscapetopography`
--
ALTER TABLE `landscapetopography`
 ADD PRIMARY KEY (`id`), ADD KEY `feature` (`feature`);

--
-- Indexes for table `landuses`
--
ALTER TABLE `landuses`
 ADD PRIMARY KEY (`id`), ADD KEY `term` (`term`), ADD KEY `belongsto` (`belongsto`), ADD KEY `valid` (`valid`);

--
-- Indexes for table `licenseType`
--
ALTER TABLE `licenseType`
 ADD PRIMARY KEY (`id`), ADD KEY `flickrID` (`flickrID`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loginRedirect`
--
ALTER TABLE `loginRedirect`
 ADD PRIMARY KEY (`id`), ADD KEY `uri` (`uri`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
 ADD PRIMARY KEY (`id`), ADD KEY `loginDate` (`loginDate`), ADD KEY `username` (`username`);

--
-- Indexes for table `macktypes`
--
ALTER TABLE `macktypes`
 ADD PRIMARY KEY (`id`), ADD KEY `type` (`type`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`);

--
-- Indexes for table `mailinglist`
--
ALTER TABLE `mailinglist`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufactures`
--
ALTER TABLE `manufactures`
 ADD PRIMARY KEY (`id`), ADD KEY `term` (`term`), ADD KEY `bmID` (`bmID`);

--
-- Indexes for table `maporigins`
--
ALTER TABLE `maporigins`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `valid` (`valid`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `bmID` (`bmID`);

--
-- Indexes for table `mda_obj_prefs`
--
ALTER TABLE `mda_obj_prefs`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `mda_obj_rels`
--
ALTER TABLE `mda_obj_rels`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `mda_obj_uses`
--
ALTER TABLE `mda_obj_uses`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `medievalcategories`
--
ALTER TABLE `medievalcategories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medievaltypes`
--
ALTER TABLE `medievaltypes`
 ADD PRIMARY KEY (`id`), ADD KEY `rulerID` (`rulerID`), ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `mints`
--
ALTER TABLE `mints`
 ADD PRIMARY KEY (`id`), ADD KEY `mint_name` (`mint_name`), ADD KEY `valid` (`valid`), ADD KEY `period` (`period`), ADD KEY `pleiadesID` (`pleiadesID`), ADD KEY `woeid` (`woeid`), ADD KEY `geonamesID` (`geonamesID`), ADD KEY `nomismaID` (`nomismaID`);

--
-- Indexes for table `mints_rulers`
--
ALTER TABLE `mints_rulers`
 ADD PRIMARY KEY (`id`), ADD KEY `mint_id` (`mint_id`), ADD KEY `ruler_id` (`ruler_id`);

--
-- Indexes for table `mint_reversetype`
--
ALTER TABLE `mint_reversetype`
 ADD PRIMARY KEY (`id`), ADD KEY `mintID` (`mintID`), ADD KEY `reverseID` (`reverseID`);

--
-- Indexes for table `monarchs`
--
ALTER TABLE `monarchs`
 ADD PRIMARY KEY (`id`), ADD KEY `dbaseID` (`dbaseID`), ADD KEY `createdby` (`createdby`), ADD KEY `publishState` (`publishState`);

--
-- Indexes for table `moneyers`
--
ALTER TABLE `moneyers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `myresearch`
--
ALTER TABLE `myresearch`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `secuid` (`secuid`), ADD KEY `public` (`public`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`id`), ADD KEY `woeid` (`woeid`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `created` (`created`), ADD KEY `golive` (`golive`);

--
-- Indexes for table `oai_pmh_repository_tokens`
--
ALTER TABLE `oai_pmh_repository_tokens`
 ADD PRIMARY KEY (`id`), ADD KEY `expiration` (`expiration`);

--
-- Indexes for table `oauthTokens`
--
ALTER TABLE `oauthTokens`
 ADD PRIMARY KEY (`id`), ADD KEY `expires` (`expires`), ADD KEY `service` (`service`);

--
-- Indexes for table `objectterms`
--
ALTER TABLE `objectterms`
 ADD PRIMARY KEY (`id`), ADD KEY `term` (`term`), ADD KEY `indexTerm` (`indexTerm`), ADD KEY `ehID` (`ehID`);

--
-- Indexes for table `oldrulers`
--
ALTER TABLE `oldrulers`
 ADD PRIMARY KEY (`id`), ADD KEY `issuer` (`issuer`), ADD KEY `country` (`country`), ADD KEY `display` (`display`), ADD KEY `date1` (`date1`), ADD KEY `date2` (`date2`), ADD KEY `valid` (`valid`);

--
-- Indexes for table `organisations`
--
ALTER TABLE `organisations`
 ADD PRIMARY KEY (`id`), ADD KEY `woeid` (`woeid`), ADD KEY `secuid` (`secuid`);

--
-- Indexes for table `organisationsAudit`
--
ALTER TABLE `organisationsAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`);

--
-- Indexes for table `osCounties`
--
ALTER TABLE `osCounties`
 ADD PRIMARY KEY (`id`), ADD KEY `osID` (`osID`), ADD KEY `regionID` (`regionID`);

--
-- Indexes for table `osdata`
--
ALTER TABLE `osdata`
 ADD PRIMARY KEY (`id`), ADD KEY `latitude` (`latitude`), ADD KEY `longitude` (`longitude`), ADD KEY `f_code` (`f_code`);

--
-- Indexes for table `osDistricts`
--
ALTER TABLE `osDistricts`
 ADD PRIMARY KEY (`id`), ADD KEY `countyID` (`countyID`), ADD KEY `regionID` (`regionID`), ADD KEY `osID` (`osID`);

--
-- Indexes for table `osParishes`
--
ALTER TABLE `osParishes`
 ADD PRIMARY KEY (`id`), ADD KEY `odID` (`osID`), ADD KEY `districtID` (`districtID`), ADD KEY `countyID` (`countyID`), ADD KEY `regionID` (`regionID`), ADD KEY `label` (`label`), ADD KEY `osID` (`osID`);

--
-- Indexes for table `osRegions`
--
ALTER TABLE `osRegions`
 ADD PRIMARY KEY (`id`), ADD KEY `osID` (`osID`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
 ADD PRIMARY KEY (`id`), ADD KEY `primary_activity` (`primary_activity`), ADD KEY `woeid` (`woeid`), ADD KEY `secuid` (`secuid`), ADD KEY `dbaseID` (`dbaseID`), ADD KEY `organisationID` (`organisationID`), ADD KEY `fullname` (`fullname`), ADD KEY `canRecord` (`canRecord`);

--
-- Indexes for table `peopleAudit`
--
ALTER TABLE `peopleAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`);

--
-- Indexes for table `peopletypes`
--
ALTER TABLE `peopletypes`
 ADD KEY `ID` (`id`);

--
-- Indexes for table `periods`
--
ALTER TABLE `periods`
 ADD PRIMARY KEY (`id`), ADD KEY `valid` (`valid`), ADD KEY `ehTerm` (`ehTerm`), ADD KEY `bmID` (`bmID`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `IDX_placeID` (`placeID`), ADD KEY `parish` (`parish`), ADD KEY `county` (`county`), ADD KEY `district` (`district`);

--
-- Indexes for table `places2`
--
ALTER TABLE `places2`
 ADD PRIMARY KEY (`id`), ADD KEY `county` (`county`);

--
-- Indexes for table `preservations`
--
ALTER TABLE `preservations`
 ADD PRIMARY KEY (`id`), ADD KEY `valid` (`valid`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `bmID` (`bmID`);

--
-- Indexes for table `primaryactivities`
--
ALTER TABLE `primaryactivities`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projecttypes`
--
ALTER TABLE `projecttypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `publications`
--
ALTER TABLE `publications`
 ADD PRIMARY KEY (`id`), ADD KEY `in_publication` (`in_publication`), ADD KEY `publication_type` (`publication_type`), ADD KEY `secuid` (`secuid`), ADD KEY `biab` (`biab`), ADD KEY `doi` (`doi`), ADD FULLTEXT KEY `title` (`title`), ADD FULLTEXT KEY `authors` (`authors`);

--
-- Indexes for table `publicationtypes`
--
ALTER TABLE `publicationtypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `status` (`status`), ADD KEY `expire` (`expire`);

--
-- Indexes for table `rallies`
--
ALTER TABLE `rallies`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `date_from` (`date_from`);

--
-- Indexes for table `rallyXflo`
--
ALTER TABLE `rallyXflo`
 ADD PRIMARY KEY (`id`), ADD KEY `rallyID` (`rallyID`), ADD KEY `staffID` (`staffID`);

--
-- Indexes for table `recmethods`
--
ALTER TABLE `recmethods`
 ADD PRIMARY KEY (`id`), ADD KEY `method` (`method`);

--
-- Indexes for table `reeceperiods`
--
ALTER TABLE `reeceperiods`
 ADD PRIMARY KEY (`id`), ADD KEY `valid` (`valid`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `period_name` (`period_name`), ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `reeceperiods_rulers`
--
ALTER TABLE `reeceperiods_rulers`
 ADD PRIMARY KEY (`id`), ADD KEY `ruler_id` (`ruler_id`), ADD KEY `reeceperiod_id` (`reeceperiod_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
 ADD PRIMARY KEY (`id`), ADD KEY `region` (`region`), ADD KEY `valid` (`valid`);

--
-- Indexes for table `reliability`
--
ALTER TABLE `reliability`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
 ADD PRIMARY KEY (`id`), ADD KEY `messageID` (`messageID`);

--
-- Indexes for table `researchprojects`
--
ALTER TABLE `researchprojects`
 ADD PRIMARY KEY (`id`), ADD KEY `level` (`level`), ADD KEY `valid` (`valid`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`);

--
-- Indexes for table `reverses`
--
ALTER TABLE `reverses`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `revtypes`
--
ALTER TABLE `revtypes`
 ADD PRIMARY KEY (`id`), ADD KEY `common` (`common`), ADD KEY `reeceID` (`reeceID`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
 ADD PRIMARY KEY (`id`), ADD KEY `role` (`role`);

--
-- Indexes for table `romandenoms`
--
ALTER TABLE `romandenoms`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `romanmints`
--
ALTER TABLE `romanmints`
 ADD PRIMARY KEY (`id`), ADD KEY `pasID` (`pasID`), ADD KEY `bmID` (`bmID`), ADD KEY `dbpediaID` (`dbpediaID`);

--
-- Indexes for table `rulerImages`
--
ALTER TABLE `rulerImages`
 ADD PRIMARY KEY (`id`), ADD KEY `rulerID` (`rulerID`);

--
-- Indexes for table `rulers`
--
ALTER TABLE `rulers`
 ADD PRIMARY KEY (`id`), ADD KEY `issuer` (`issuer`), ADD KEY `country` (`country`), ADD KEY `display` (`display`), ADD KEY `date1` (`date1`), ADD KEY `date2` (`date2`), ADD KEY `valid` (`valid`);

--
-- Indexes for table `ruler_reversetype`
--
ALTER TABLE `ruler_reversetype`
 ADD PRIMARY KEY (`id`), ADD KEY `rulerID` (`rulerID`), ADD KEY `reverseID` (`reverseID`);

--
-- Indexes for table `savedSearches`
--
ALTER TABLE `savedSearches`
 ADD PRIMARY KEY (`id`), ADD KEY `userID` (`userID`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `public` (`public`);

--
-- Indexes for table `scheduledMonuments`
--
ALTER TABLE `scheduledMonuments`
 ADD PRIMARY KEY (`id`), ADD KEY `gridref` (`gridref`), ADD KEY `fourFigure` (`fourFigure`), ADD KEY `lat` (`lat`), ADD KEY `lon` (`lon`);

--
-- Indexes for table `searches`
--
ALTER TABLE `searches`
 ADD PRIMARY KEY (`id`), ADD KEY `userid` (`userid`);

--
-- Indexes for table `semanticTags`
--
ALTER TABLE `semanticTags`
 ADD PRIMARY KEY (`id`), ADD KEY `woeid` (`woeid`), ADD KEY `contenttype` (`contenttype`), ADD KEY `origin` (`origin`), ADD KEY `contentID` (`contentID`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
 ADD PRIMARY KEY (`imageID`), ADD KEY `imagecreator` (`imagecreator`), ADD KEY `county` (`county`), ADD KEY `filename` (`filename`), ADD KEY `secuid` (`secuid`), ADD KEY `createdBy` (`createdBy`), ADD KEY `period` (`period`), ADD KEY `ccLicense` (`ccLicense`), ADD KEY `institution` (`institution`), ADD FULLTEXT KEY `label` (`label`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
 ADD PRIMARY KEY (`id`), ADD KEY `dbaseID` (`dbaseID`);

--
-- Indexes for table `staffregions`
--
ALTER TABLE `staffregions`
 ADD PRIMARY KEY (`id`), ADD KEY `prefix` (`prefix`), ADD KEY `regionID` (`regionID`);

--
-- Indexes for table `staffroles`
--
ALTER TABLE `staffroles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subperiods`
--
ALTER TABLE `subperiods`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subsequentActions`
--
ALTER TABLE `subsequentActions`
 ADD PRIMARY KEY (`id`), ADD KEY `action` (`action`);

--
-- Indexes for table `suggestedResearch`
--
ALTER TABLE `suggestedResearch`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `summaryAudit`
--
ALTER TABLE `summaryAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `coinID` (`recordID`), ADD KEY `findID` (`entityID`);

--
-- Indexes for table `surftreatments`
--
ALTER TABLE `surftreatments`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `valid` (`valid`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `bmID` (`bmID`);

--
-- Indexes for table `systemroles`
--
ALTER TABLE `systemroles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taggedcontent`
--
ALTER TABLE `taggedcontent`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `tag` (`tag`);

--
-- Indexes for table `tempfindspots`
--
ALTER TABLE `tempfindspots`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminalreason`
--
ALTER TABLE `terminalreason`
 ADD PRIMARY KEY (`id`), ADD KEY `reason` (`reason`);

--
-- Indexes for table `thes_chronuk2`
--
ALTER TABLE `thes_chronuk2`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treasureActions`
--
ALTER TABLE `treasureActions`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`);

--
-- Indexes for table `treasureActionTypes`
--
ALTER TABLE `treasureActionTypes`
 ADD PRIMARY KEY (`id`), ADD KEY `valid` (`valid`);

--
-- Indexes for table `treasureAssignations`
--
ALTER TABLE `treasureAssignations`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treasureStatus`
--
ALTER TABLE `treasureStatus`
 ADD PRIMARY KEY (`id`), ADD KEY `treasureID` (`treasureID`);

--
-- Indexes for table `treasureStatusTypes`
--
ALTER TABLE `treasureStatusTypes`
 ADD PRIMARY KEY (`id`), ADD KEY `valid` (`valid`);

--
-- Indexes for table `treasureValuations`
--
ALTER TABLE `treasureValuations`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tvcDates`
--
ALTER TABLE `tvcDates`
 ADD PRIMARY KEY (`id`), ADD KEY `secuid` (`secuid`);

--
-- Indexes for table `tvcDatesToCases`
--
ALTER TABLE `tvcDatesToCases`
 ADD PRIMARY KEY (`id`), ADD KEY `treasureID` (`treasureID`);

--
-- Indexes for table `userOnlineAccounts`
--
ALTER TABLE `userOnlineAccounts`
 ADD PRIMARY KEY (`id`), ADD KEY `public` (`public`), ADD KEY `userID` (`userID`), ADD KEY `accountName` (`accountName`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD KEY `role` (`role`), ADD KEY `institution` (`institution`), ADD KEY `lastLogin` (`lastLogin`), ADD KEY `visits` (`visits`), ADD KEY `email` (`email`), ADD KEY `higherLevel` (`higherLevel`), ADD KEY `canRecord` (`canRecord`), ADD KEY `peopleID` (`peopleID`);

--
-- Indexes for table `usersAudit`
--
ALTER TABLE `usersAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`);

--
-- Indexes for table `usersEducation`
--
ALTER TABLE `usersEducation`
 ADD PRIMARY KEY (`id`), ADD KEY `school` (`school`);

--
-- Indexes for table `usersInterests`
--
ALTER TABLE `usersInterests`
 ADD PRIMARY KEY (`id`), ADD KEY `interest` (`interest`);

--
-- Indexes for table `vacancies`
--
ALTER TABLE `vacancies`
 ADD PRIMARY KEY (`id`), ADD KEY `live` (`live`), ADD KEY `expire` (`expire`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`), ADD KEY `status` (`status`), ADD KEY `regionID` (`regionID`);

--
-- Indexes for table `vanarsdelltypes`
--
ALTER TABLE `vanarsdelltypes`
 ADD PRIMARY KEY (`id`), ADD KEY `type` (`type`), ADD KEY `createdBy` (`createdBy`), ADD KEY `updatedBy` (`updatedBy`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weartypes`
--
ALTER TABLE `weartypes`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `valid` (`valid`), ADD KEY `createdBy_2` (`createdBy`);

--
-- Indexes for table `webServices`
--
ALTER TABLE `webServices`
 ADD PRIMARY KEY (`id`), ADD KEY `service` (`service`);

--
-- Indexes for table `workflowstages`
--
ALTER TABLE `workflowstages`
 ADD PRIMARY KEY (`id`), ADD KEY `workflowstage` (`workflowstage`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abbreviations`
--
ALTER TABLE `abbreviations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `abcNumbers`
--
ALTER TABLE `abcNumbers`
MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `accreditedMuseums`
--
ALTER TABLE `accreditedMuseums`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `accreditedRegions`
--
ALTER TABLE `accreditedRegions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `accreditedStatus`
--
ALTER TABLE `accreditedStatus`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `agreedTreasureValuations`
--
ALTER TABLE `agreedTreasureValuations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `allentypes`
--
ALTER TABLE `allentypes`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `approveReject`
--
ALTER TABLE `approveReject`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archaeology`
--
ALTER TABLE `archaeology`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archaeologyAudit`
--
ALTER TABLE `archaeologyAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archfeature`
--
ALTER TABLE `archfeature`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archsiteclass`
--
ALTER TABLE `archsiteclass`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archsitetype`
--
ALTER TABLE `archsitetype`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bibliography`
--
ALTER TABLE `bibliography`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categoriescoins`
--
ALTER TABLE `categoriescoins`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cciVa`
--
ALTER TABLE `cciVa`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `certaintytypes`
--
ALTER TABLE `certaintytypes`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `classesJettonGroups`
--
ALTER TABLE `classesJettonGroups`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coinclassifications`
--
ALTER TABLE `coinclassifications`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coincountry_origin`
--
ALTER TABLE `coincountry_origin`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coins`
--
ALTER TABLE `coins`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coinsAudit`
--
ALTER TABLE `coinsAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coinsummary`
--
ALTER TABLE `coinsummary`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coins_denomxruler`
--
ALTER TABLE `coins_denomxruler`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coins_rulers`
--
ALTER TABLE `coins_rulers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coinxclass`
--
ALTER TABLE `coinxclass`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `completeness`
--
ALTER TABLE `completeness`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contentAudit`
--
ALTER TABLE `contentAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `copyCoin`
--
ALTER TABLE `copyCoin`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `copyFind`
--
ALTER TABLE `copyFind`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `copyFindSpot`
--
ALTER TABLE `copyFindSpot`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `copyHoards`
--
ALTER TABLE `copyHoards`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `copyrights`
--
ALTER TABLE `copyrights`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `coroners`
--
ALTER TABLE `coroners`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `counties`
--
ALTER TABLE `counties`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countyToFlo`
--
ALTER TABLE `countyToFlo`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `crimeTypes`
--
ALTER TABLE `crimeTypes`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cultures`
--
ALTER TABLE `cultures`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dataquality`
--
ALTER TABLE `dataquality`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `datequalifiers`
--
ALTER TABLE `datequalifiers`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `decmethods`
--
ALTER TABLE `decmethods`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `decstyles`
--
ALTER TABLE `decstyles`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `denominations`
--
ALTER TABLE `denominations`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `denominations_rulers`
--
ALTER TABLE `denominations_rulers`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dieaxes`
--
ALTER TABLE `dieaxes`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `discmethods`
--
ALTER TABLE `discmethods`
MODIFY `id` int(50) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dynasties`
--
ALTER TABLE `dynasties`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emperors`
--
ALTER TABLE `emperors`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `errorreports`
--
ALTER TABLE `errorreports`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `eventtypes`
--
ALTER TABLE `eventtypes`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `findofnotereasons`
--
ALTER TABLE `findofnotereasons`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `finds`
--
ALTER TABLE `finds`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `findsAudit`
--
ALTER TABLE `findsAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `findspots`
--
ALTER TABLE `findspots`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `findspotsAudit`
--
ALTER TABLE `findspotsAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `findspotsBackup`
--
ALTER TABLE `findspotsBackup`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `finds_images`
--
ALTER TABLE `finds_images`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `finds_publications`
--
ALTER TABLE `finds_publications`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `findxfind`
--
ALTER TABLE `findxfind`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `geographyironage`
--
ALTER TABLE `geographyironage`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `greekstates`
--
ALTER TABLE `greekstates`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gridrefsources`
--
ALTER TABLE `gridrefsources`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groupsJettonsTypes`
--
ALTER TABLE `groupsJettonsTypes`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `help`
--
ALTER TABLE `help`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `heritagecrime`
--
ALTER TABLE `heritagecrime`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hers`
--
ALTER TABLE `hers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hitlog`
--
ALTER TABLE `hitlog`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hoards`
--
ALTER TABLE `hoards`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hoardsAudit`
--
ALTER TABLE `hoardsAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hoards_finders`
--
ALTER TABLE `hoards_finders`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hoards_materials`
--
ALTER TABLE `hoards_materials`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `imagetypes`
--
ALTER TABLE `imagetypes`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `institutions`
--
ALTER TABLE `institutions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instLogos`
--
ALTER TABLE `instLogos`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ironagedenomxregion`
--
ALTER TABLE `ironagedenomxregion`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ironagerulerxregion`
--
ALTER TABLE `ironagerulerxregion`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ironagetribes`
--
ALTER TABLE `ironagetribes`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issuers`
--
ALTER TABLE `issuers`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issueStatuses`
--
ALTER TABLE `issueStatuses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jettonClasses`
--
ALTER TABLE `jettonClasses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jettonGroup`
--
ALTER TABLE `jettonGroup`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jettonTypes`
--
ALTER TABLE `jettonTypes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `landscapetopography`
--
ALTER TABLE `landscapetopography`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `landuses`
--
ALTER TABLE `landuses`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `licenseType`
--
ALTER TABLE `licenseType`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loginRedirect`
--
ALTER TABLE `loginRedirect`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `macktypes`
--
ALTER TABLE `macktypes`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mailinglist`
--
ALTER TABLE `mailinglist`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `manufactures`
--
ALTER TABLE `manufactures`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `maporigins`
--
ALTER TABLE `maporigins`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mda_obj_prefs`
--
ALTER TABLE `mda_obj_prefs`
MODIFY `ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mda_obj_rels`
--
ALTER TABLE `mda_obj_rels`
MODIFY `ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mda_obj_uses`
--
ALTER TABLE `mda_obj_uses`
MODIFY `ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medievalcategories`
--
ALTER TABLE `medievalcategories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medievaltypes`
--
ALTER TABLE `medievaltypes`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mints`
--
ALTER TABLE `mints`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mints_rulers`
--
ALTER TABLE `mints_rulers`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mint_reversetype`
--
ALTER TABLE `mint_reversetype`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `monarchs`
--
ALTER TABLE `monarchs`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `moneyers`
--
ALTER TABLE `moneyers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `myresearch`
--
ALTER TABLE `myresearch`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oai_pmh_repository_tokens`
--
ALTER TABLE `oai_pmh_repository_tokens`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oauthTokens`
--
ALTER TABLE `oauthTokens`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `objectterms`
--
ALTER TABLE `objectterms`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oldrulers`
--
ALTER TABLE `oldrulers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `organisations`
--
ALTER TABLE `organisations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `organisationsAudit`
--
ALTER TABLE `organisationsAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `osCounties`
--
ALTER TABLE `osCounties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `osDistricts`
--
ALTER TABLE `osDistricts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `osParishes`
--
ALTER TABLE `osParishes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `osRegions`
--
ALTER TABLE `osRegions`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `peopleAudit`
--
ALTER TABLE `peopleAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `peopletypes`
--
ALTER TABLE `peopletypes`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
MODIFY `ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `places2`
--
ALTER TABLE `places2`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `preservations`
--
ALTER TABLE `preservations`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `primaryactivities`
--
ALTER TABLE `primaryactivities`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `projecttypes`
--
ALTER TABLE `projecttypes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `publications`
--
ALTER TABLE `publications`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `publicationtypes`
--
ALTER TABLE `publicationtypes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rallies`
--
ALTER TABLE `rallies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rallyXflo`
--
ALTER TABLE `rallyXflo`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recmethods`
--
ALTER TABLE `recmethods`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reeceperiods`
--
ALTER TABLE `reeceperiods`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reeceperiods_rulers`
--
ALTER TABLE `reeceperiods_rulers`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reliability`
--
ALTER TABLE `reliability`
MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `researchprojects`
--
ALTER TABLE `researchprojects`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reverses`
--
ALTER TABLE `reverses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `revtypes`
--
ALTER TABLE `revtypes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` int(2) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `romandenoms`
--
ALTER TABLE `romandenoms`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `romanmints`
--
ALTER TABLE `romanmints`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rulerImages`
--
ALTER TABLE `rulerImages`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rulers`
--
ALTER TABLE `rulers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ruler_reversetype`
--
ALTER TABLE `ruler_reversetype`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `savedSearches`
--
ALTER TABLE `savedSearches`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scheduledMonuments`
--
ALTER TABLE `scheduledMonuments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `searches`
--
ALTER TABLE `searches`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `semanticTags`
--
ALTER TABLE `semanticTags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
MODIFY `imageID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `staffregions`
--
ALTER TABLE `staffregions`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `staffroles`
--
ALTER TABLE `staffroles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subperiods`
--
ALTER TABLE `subperiods`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subsequentActions`
--
ALTER TABLE `subsequentActions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suggestedResearch`
--
ALTER TABLE `suggestedResearch`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `summaryAudit`
--
ALTER TABLE `summaryAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `surftreatments`
--
ALTER TABLE `surftreatments`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `systemroles`
--
ALTER TABLE `systemroles`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `taggedcontent`
--
ALTER TABLE `taggedcontent`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tempfindspots`
--
ALTER TABLE `tempfindspots`
MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `terminalreason`
--
ALTER TABLE `terminalreason`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `thes_chronuk2`
--
ALTER TABLE `thes_chronuk2`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `treasureActions`
--
ALTER TABLE `treasureActions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `treasureActionTypes`
--
ALTER TABLE `treasureActionTypes`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `treasureAssignations`
--
ALTER TABLE `treasureAssignations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `treasureStatus`
--
ALTER TABLE `treasureStatus`
MODIFY `id` tinyint(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `treasureStatusTypes`
--
ALTER TABLE `treasureStatusTypes`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `treasureValuations`
--
ALTER TABLE `treasureValuations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tvcDates`
--
ALTER TABLE `tvcDates`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tvcDatesToCases`
--
ALTER TABLE `tvcDatesToCases`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `userOnlineAccounts`
--
ALTER TABLE `userOnlineAccounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usersAudit`
--
ALTER TABLE `usersAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usersEducation`
--
ALTER TABLE `usersEducation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usersInterests`
--
ALTER TABLE `usersInterests`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vacancies`
--
ALTER TABLE `vacancies`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vanarsdelltypes`
--
ALTER TABLE `vanarsdelltypes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `weartypes`
--
ALTER TABLE `weartypes`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `webServices`
--
ALTER TABLE `webServices`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `workflowstages`
--
ALTER TABLE `workflowstages`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
