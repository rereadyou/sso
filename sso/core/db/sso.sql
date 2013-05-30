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
 DROP TABLE IF EXISTS `user`;
 CREATE TABLE IF NOT EXISTS `user`
 (
     `id` bigint(10) NOT NULL AUTO_INCREMENT,
     `email` varchar(96) NOT NULL,
     `name` varchar(32) NOT NULL,
     `password` char(32) NOT NULL,
     `isadmin` ENUM('0', '1') NOT NULL,
     PRIMARY KEY(`id`)
 )ENGINE=MYISAM DEFAULT CHARSET=UTF8;

 -- -----------------------------------------------
 -- table app 
 -- -----------------------------------------------
 DROP TABLE IF EXISTS `app`;
 CREATE TABLE IF NOT EXISTS `app`
 (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `contactor` bigint(10) NOT NULL,
    `name` varchar(100) NOT NULL,
    `domain` varchar(200) NOT NULL,
    `ip` varchar(32) NULL,
    `login_page` varchar(32) NOT NULL,
    `home_page` varchar(32) NOT NULL COMMENT 'url when login done',
    PRIMARY KEY(`id`),
    FOREIGN KEY(`contactor`) REFERENCES `user`
    ON UPDATE CASCADE
    ON DELETE SET NULL
 )ENGINE=MYISAM DEFAULT CHARSET=UTF8;

 -- -----------------------------------------------
 -- table login_info 
 -- -----------------------------------------------
 DROP TABLE IF EXISTS `login_info`;
 /*
 CREATE TABLE IF NOT EXISTS `login_info`
 (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `uid` bigint(10) NOT NULL, `sessid` varchar(40) NOT NULL UNIQUE,
    `ticket` varchar(32) NOT NULL,
    `entry` varchar(24) NOT NULL,
    `ip` varchar(15) NOT NULL,
    `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `try_times` tinyint NULL DEFAULT 1,
    `client` text NOT NULL COMMENT 'user client info, e.g. browser type',
    `referer` varchar(200) NULL COMMENT 'user previous page',
    PRIMARY KEY(`id`),
    FOREIGN KEY(`uid`) REFERENCES `user`
    ON UPDATE CASCADE
    ON DELETE SET NULL,
    FOREIGN KEY(`sessid`) REFERENCES `login_session`
    ON UPDATE CASCADE
    ON DELETE SET NULL
 )ENGINE=MYISAM DEFAULT CHARSET=UTF8;
 */
 CREATE TABLE IF NOT EXISTS `login_info`
 (
    `id` bigint(10) NOT NULL AUTO_INCREMENT,
    `uid` bigint(10) NOT NULL,
    `sessid` varchar(40) NOT NULL UNIQUE,
    `history` text NOT NULL,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`uid`) REFERENCES `user`
    ON UPDATE CASCADE
    ON DELETE SET NULL,
    FOREIGN KEY(`sessid`) REFERENCES `login_session`
    ON UPDATE CASCADE
    ON DELETE SET NULL
 )ENGINE=MYISAM DEFAULT CHARSET=UTF8;
 -- -----------------------------------------------
 -- table login_session 
 -- -----------------------------------------------
 DROP TABLE IF EXISTS `login_session`;
 CREATE TABLE IF NOT EXISTS `login_session`
 (
    `id` bigint(10) NOT NULL AUTO_INCREMENT,
    `uid` bigint(10) NOT NULL UNIQUE,
    `sessid` varchar(40) NOT NULL UNIQUE,
    `sessinfo` text NOT NULL,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`uid`) REFERENCES `user`
    ON UPDATE CASCADE
    ON DELETE SET NULL
 )ENGINE=MYISAM DEFAULT CHARSET=UTF8;


 -- -----------------------------------------------
 -- login record 
 -- -----------------------------------------------
 DROP TABLE IF EXISTS `login_history`;
 CREATE TABLE IF NOT EXISTS `login_history`
 (
    `id` bigint(10) NOT NULL AUTO_INCREMENT,
    `uid` bigint(10) NOT NULL,
    `appid` bigint(10) NOT NULL,
    `ip` varchar(32) NOT NULL,
    `browser` varchar(100) NOT NULL,
    `sessid` varchar(40) NOT NULL UNIQUE,
    `ticket` varchar(40) NOT NULL,
    `referrer` varchar(100),
    `trytimes` smallint DEFAULT 1,
    `servertime` int(10) NOT NULL,
    `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`uid`) REFERENCES `user`
    ON UPDATE CASCADE
    ON DELETE SET NULL,
    FOREIGN KEY(`appid`) REFERENCES `app`
    ON UPDATE CASCADE
    ON DELETE SET NULL
 )ENGINE=MYISAM DEFAULT CHARSET=UTF8;

 -- -----------------------------------------------
 -- default test data 
 -- -----------------------------------------------
 INSERT INTO `user`(`email`, `name`,`password`, `isadmin`) 
 VALUES('rereadyou@gmail.com', 'rereadyou', 'rereadyou', '1'),
 ('bo_zhang@allyes.com', 'zhangbo', 'zhangbo', '0');

 INSERT INTO `app`(`contactor`, `name`, `domain`, `login_page`, `home_page`) VALUES 
 (1, 'sso', 'sso.allyes.me', 'authentication/login', 'admin/index'),
 (1, 'agear', 'ssodemo.agear.net', 'login', 'home'),
 (2, 'iuv', 'ssodemo.iuv.me', 'login', 'home');

