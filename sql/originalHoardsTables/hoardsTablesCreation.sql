
CREATE TABLE `hoards` (
  `id` int(11) NOT NULL auto_increment,
  `hoardID` varchar(50) collate utf8_unicode_ci default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `broadperiod` varchar(255) collate utf8_unicode_ci default NULL,
  `period1` tinyint(2) unsigned default NULL,
  `subperiod1` tinyint(1) unsigned default NULL,
  `period2` tinyint(2) unsigned default NULL,
  `subperiod2` tinyint(1) unsigned default NULL,
  `numdate1` int(11) default NULL,
  `numdate2` int(11) default NULL,
  `lastrulerID` int(10) unsigned NULL default NULL,
  `reeceID` int(10) unsigned NULL default NULL,
  `terminalyear1` int(11) default NULL,
  `terminalyear2` int(11) default NULL,
  `terminalreason` int(11) default NULL,
  `description` text collate utf8_unicode_ci,
  `notes` text collate utf8_unicode_ci,
  `secwfstage` tinyint(1) unsigned default NULL,
  `findofnote` tinyint(3) default NULL,
  `findofnotereason` tinyint(2) default NULL,
  `treasure` tinyint(1) default NULL,
  `treasureID` varchar(15) collate utf8_unicode_ci default NULL,
  `qualityrating` int(1) unsigned default NULL,
  `materials` text collate utf8_unicode_ci,
  `recorderID` varchar(50) collate utf8_unicode_ci default NULL,
  `identifier1ID` varchar(50) collate utf8_unicode_ci default NULL,
  `identifier2ID` varchar(50) collate utf8_unicode_ci default NULL,
  `finderID` varchar(50) collate utf8_unicode_ci default NULL,
  `finder2ID` varchar(50) collate utf8_unicode_ci default NULL,
  `disccircum` varchar(250) collate utf8_unicode_ci default NULL,
  `discmethod` tinyint(2) default NULL,
  `datefound1` date default NULL,
  `datefound2` date default NULL,
  `rally` tinyint(1) default NULL,
  `rallyID` int(11) default NULL,
  `legacyID` int(11) default NULL,
  `other_ref` varchar(250) collate utf8_unicode_ci default NULL,
  `smrrefno` varchar(250) collate utf8_unicode_ci default NULL,
  `musaccno` varchar(250) collate utf8_unicode_ci default NULL,
  `curr_loc` varchar(250) collate utf8_unicode_ci default NULL,
  `subs_action` varchar(250) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(10) unsigned default NULL,
  `updated` datetime default NULL,
  `updatedBy` varchar(20) collate utf8_unicode_ci default NULL,
  `institution` varchar(10) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
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
  KEY `finderID` (`finderID`),
  KEY `finder2ID` (`finder2ID`),
  KEY `discmethod` (`discmethod`),
  KEY `rallyID` (`rallyID`),
  KEY `legacyID` (`legacyID`),
  KEY `other_ref` (`other_ref`),
  KEY `created` (`created`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `institution` (`institution`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Hoard records';

CREATE TABLE `archaeology` (
  `id` int(11) NOT NULL auto_increment,
  `hoardID` varchar(50) collate utf8_unicode_ci default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `knownsite`  tinyint(1) default NULL,
  `excavated`  tinyint(1) default NULL,
  `sitecontext` smallint(6) unsigned default NULL,
  `broadperiod` varchar(255) collate utf8_unicode_ci default NULL,
  `period1` tinyint(2) unsigned default NULL,
  `subperiod1` tinyint(1) unsigned default NULL,
  `period2` tinyint(2) unsigned default NULL,
  `subperiod2` tinyint(1) unsigned default NULL,
  `sitedateyear1` int(11) default NULL,
  `sitedateyear2` int(11) default NULL,
  `sitetype` smallint(6) unsigned default NULL,
  `feature` smallint(6) unsigned default NULL,
  `featuredateyear1` int(11) default NULL,
  `featuredateyear2` int(11) default NULL,
  `landscapetopography` smallint(6) unsigned default NULL,
  `recmethod` smallint(6) unsigned default NULL,
  `yearexc1` int(11) default NULL,
  `yearexc2` int(11) default NULL,
  `description` text collate utf8_unicode_ci,
  `contextualrating` int(1) unsigned default NULL,
  `archiveloc` varchar(250) collate utf8_unicode_ci default NULL,
  `valid` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Archaeological context information for hoards';

CREATE TABLE `coinsummary` (
  `id` int(11) NOT NULL auto_increment,
  `hoardID` varchar(50) collate utf8_unicode_ci default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `quantity` smallint(6) unsigned default NULL,
  `broadperiod` varchar(255) collate utf8_unicode_ci default NULL,
  `denomination` int(4) unsigned default NULL,
  `geographyID` int(10) unsigned default NULL,
  `ruler_id` int(11) unsigned default NULL,
  `mint_id` int(11) unsigned default NULL,
  `numdate1` int(11) default NULL,
  `numdate2` int(11) default NULL,
  `created` datetime default NULL,
  `createdBy` int(10) unsigned default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(10) unsigned default NULL,
  `institution` varchar(10) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `hoardID` (`hoardID`),
  KEY `broadperiod` (`broadperiod`),
  KEY `denomination` (`denomination`),
  KEY `numdate1` (`numdate1`),
  KEY `numdate2` (`numdate2`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Coin summaries for hoards';

CREATE TABLE `terminalreason` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `reason` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `reason` (`reason`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Reasons for the terminal date in coin hoards';

CREATE TABLE `dataquality` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `rating` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `rating` (`rating`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  COMMENT='Quality of the data in hoards';

CREATE TABLE `recmethods` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `method` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `method` (`method`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Recovery methods for hoards (archaeological context information)';

CREATE TABLE `archsiteclass` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `siteclass` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `siteclass` (`siteclass`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Classes of archaeological site for hoards (archaeological context information)';

CREATE TABLE `archsitetype` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `sitetype` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `sitetype` (`sitetype`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of archaeological site for hoards (archaeological context information)';

CREATE TABLE `archfeature` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `feature` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `feature` (`feature`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Archaeological features for hoards (archaeological context information)';

CREATE TABLE `landscapetopography` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `feature` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `feature` (`feature`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Landscape and topography for hoards (archaeological context information)';

CREATE TABLE `hoards_finders` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hoardID` varchar(50) collate utf8_unicode_ci default NULL,
  `finderID` varchar(50) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `hoardID` (`hoardID`),
  KEY `finderID` (`finderID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Multiple finders per hoard record';


CREATE TABLE `hoards_materials` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hoardID` varchar(50) collate utf8_unicode_ci default NULL,
  `materialID` int(11) unsigned default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `hoardID` (`hoardID`),
  KEY `materialID` (`materialID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Multiple materials per hoard record';

-- Table structure for table `hoardsAudit`
--

CREATE TABLE IF NOT EXISTS `hoardsAudit` (
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

--
-- Indexes for table `hoardsAudit`
--
ALTER TABLE `hoardsAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `findID` (`recordID`), ADD KEY `entityID` (`entityID`);

--
-- AUTO_INCREMENT for table `hoardsAudit`
--
ALTER TABLE `hoardsAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
ALTER TABLE `hoards` CHANGE `lastrulerID` `lastrulerID` INT(10) UNSIGNED NULL DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `summaryAudit` (
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

ALTER TABLE `summaryAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `coinID` (`recordID`), ADD KEY `findID` (`entityID`);

ALTER TABLE `summaryAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;

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

ALTER TABLE `archaeologyAudit`
 ADD PRIMARY KEY (`id`), ADD KEY `createdBy` (`createdBy`), ADD KEY `editID` (`editID`), ADD KEY `coinID` (`recordID`), ADD KEY `findID` (`entityID`);

ALTER TABLE `archaeologyAudit`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;