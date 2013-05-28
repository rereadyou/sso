/**
 * SSO sql file
 */

 -- --------------------------------
 -- Create db
 -- --------------------------------

 DROP DATABASE IF EXISTS `sso`;
 CREATE DATABASE `sso`;

 -- -----------------------------------------------
 -- Select database
 -- -----------------------------------------------
 USE `sso`;

 -- --------------------------------
 -- Table structure of user
 -- --------------------------------
 DROP TABLE IF EXISTS `users`;

 CREATE TABLE IF NOT EXISTS `users`
 (
     `id` bigint(20) NOT NULL AUTO_INCREMENT,
     `name` varchar(32) NOT NULL,
     `password` char(32) NOT NULL,
     PRIMARY KEY(`id`)

 )ENGINE=MYISAM DEFAULT CHARSET=UTF8;

 INSERT INTO `users`(`name`,`password`) 
 VALUES('rereadyou', 'rereadyou'),
 ('zhangbo', 'zhangbo');