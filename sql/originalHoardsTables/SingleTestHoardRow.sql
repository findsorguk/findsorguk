-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2015 at 12:35 PM
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

--
-- Dumping data for table `hoards`
--

INSERT INTO `hoards` (`id`, `hoardID`, `secuid`, `broadperiod`, `period1`, `subperiod1`, `period2`, `subperiod2`, `numdate1`, `numdate2`, `lastrulerID`, `reeceID`, `quantityCoins`, `quantityArtefacts`, `quantityContainers`, `terminalyear1`, `terminalyear2`, `terminalreason`, `description`, `notes`, `secwfstage`, `findofnote`, `findofnotereason`, `treasure`, `treasureID`, `qualityrating`, `materials`, `recorderID`, `identifier1ID`, `identifier2ID`, `disccircum`, `discmethod`, `datefound1`, `datefound2`, `rally`, `rallyID`, `legacyID`, `other_ref`, `smrrefno`, `musaccno`, `curr_loc`, `subs_action`, `created`, `createdBy`, `updated`, `updatedBy`, `institution`) VALUES
(1, 'NMS-000000', 'PAS5385ED82001FDA', 'ROMAN', 21, 1, 21, 3, 5, 30, 244, 1, 10, 1, 1, 10, 20, 1, '<p>Test banana2</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam luctus leo at nibh luctus iaculis. Nunc in massa nec diam laoreet semper. Sed sagittis eros fermentum quam congue, in scelerisque augue vulputate. Nunc ultrices hendrerit tempor. Pellentesque pretium sodales eros, nec tempor mi feugiat et. Nunc rhoncus, augue at blandit dapibus, ligula erat condimentum dolor, ac gravida metus nulla in justo. Vivamus mollis metus arcu, nec consectetur mauris placerat vitae. Fusce luctus lectus vel ligula aliquam tincidunt. Interdum et malesuada fames ac ante ipsum primis in faucibus. Vestibulum lobortis, est ut venenatis euismod, tortor diam facilisis eros, vitae rhoncus lorem sem quis nisl.</p>\r\n\r\n<p>Curabitur pellentesque placerat dui, non consequat odio blandit sed. Mauris lectus massa, suscipit sit amet posuere non, laoreet nec quam. Fusce suscipit erat quis eros commodo, eu tempor nunc ultrices. Morbi faucibus dapibus libero eget lobortis.</p>', '<p>Nunc id orci tincidunt, iaculis sem at, auctor nunc. Donec quis enim vestibulum, interdum ligula consequat, auctor nulla. Aenean non malesuada diam. Sed blandit porttitor faucibus.</p>', 4, 1, 6, 1, '2014T360', 2, 'a:3:{i:0;s:1:"7";i:1;s:1:"8";i:2;s:1:"9";}', 'PAS4BA750C30010F2', 'PAS4BFE8D660011F2', '0013F72EB3001EB0', 'Found in molehills', 1, '2014-02-01', '2014-02-28', 1, 1, 1234, 'AER455', '34567', '123.456', 'Museum', '23', '2014-05-27 14:59:00', 46, '2014-10-10 12:11:53', '4059', 'NMS');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
