CREATE TABLE IF NOT EXISTS `#__resultsdb_races` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`description` VARCHAR(255)  NOT NULL ,
`additional_info` VARCHAR(255)  NOT NULL ,
`date` DATETIME NOT NULL ,
`runners` INT NOT NULL ,
`distance` FLOAT NOT NULL ,
`ascent` INT NOT NULL ,
`race_terrain` VARCHAR(255)  NOT NULL ,
`comment` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__resultsdb_runners` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`urn` INT NOT NULL ,
`firstname` VARCHAR(255)  NOT NULL ,
`surname` VARCHAR(255)  NOT NULL ,
`gender` VARCHAR(255)  NOT NULL ,
`claim` VARCHAR(255)  NOT NULL ,
`dob` DATE NOT NULL ,
`membership` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`comment` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__resultsdb_results` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`raceid` INT NOT NULL ,
`date` DATETIME NOT NULL ,
`runner` TEXT NOT NULL ,
`position` INT NOT NULL ,
`time` VARCHAR(255)  NOT NULL ,
`agegrade` FLOAT NOT NULL ,
`pb` VARCHAR(2)  NOT NULL ,
`agecat` VARCHAR(255)  NOT NULL ,
`catposition` VARCHAR(255)  NOT NULL ,
`comment` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Race','com_resultsdb.race','{"special":{"dbtable":"#__resultsdb_races","key":"id","type":"Race","prefix":"ResultsdbTable"}}', '{"formFile":"administrator\/components\/com_resultsdb\/models\/forms\/race.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_resultsdb.race')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Runner','com_resultsdb.runner','{"special":{"dbtable":"#__resultsdb_runners","key":"id","type":"Runner","prefix":"ResultsdbTable"}}', '{"formFile":"administrator\/components\/com_resultsdb\/models\/forms\/runner.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_resultsdb.runner')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Result','com_resultsdb.result','{"special":{"dbtable":"#__resultsdb_results","key":"id","type":"Result","prefix":"ResultsdbTable"}}', '{"formFile":"administrator\/components\/com_resultsdb\/models\/forms\/result.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"raceid","targetTable":"#__resultsdb_races","targetColumn":"id","displayColumn":"description"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_resultsdb.result')
) LIMIT 1;
