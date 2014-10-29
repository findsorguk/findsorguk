RENAME TABLE `antiquitiesTwo`.`opencalais` TO `antiquitiesTwo`.`semanticTags`;
ALTER TABLE `semanticTags` CHANGE `latitude` `latitude` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `semanticTags` CHANGE `longitude` `longitude` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `semanticTags` CHANGE `creator` `createdBy` INT(11) NULL DEFAULT NULL;
UPDATE `semanticTags` SET latitude = NULL, longitude = NULL WHERE `latitude` = 0;
UPDATE `semanticTags` SET origin = NULL WHERE `origin` = '';
UPDATE `semanticTags` SET woeid = NULL WHERE woeid = '';