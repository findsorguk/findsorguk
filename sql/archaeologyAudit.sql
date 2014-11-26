-- phpMyAdmin SQL Dump
-- version 4.2.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2014 at 03:44 PM
-- Server version: 5.6.21
-- PHP Version: 5.4.30

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
-- Table structure for table `archaeologyAudit`
--

CREATE TABLE IF NOT EXISTS `archaeologyAudit` (
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archaeologyAudit`
--
ALTER TABLE `archaeologyAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `coinID` (`recordID`), ADD KEY `findID` (`entityID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archaeologyAudit`
--
ALTER TABLE `archaeologyAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
