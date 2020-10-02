DROP TABLE IF EXISTS `#__resultsdb_races`;
DROP TABLE IF EXISTS `#__resultsdb_runners`;
DROP TABLE IF EXISTS `#__resultsdb_results`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_resultsdb.%');