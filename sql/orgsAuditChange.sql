ALTER TABLE `organisationsAudit` ADD `recordID` INT(11) NULL DEFAULT NULL ;
ALTER TABLE `organisationsAudit` CHANGE `orgID` `entityID` INT(11) NULL DEFAULT NULL;
ALTER TABLE `organisationsAudit` CHANGE `editID` `editID` VARCHAR(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;