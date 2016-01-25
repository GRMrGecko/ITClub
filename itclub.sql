/*

itclub.sql
IT Club

Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
All rights reserved.

The structure of the MySQL database.

*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `announcements`
-- ----------------------------
DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `message` blob NOT NULL,
  `sms` varchar(161) NOT NULL DEFAULT '',
  `date` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=Aria AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1 ROW_FORMAT=PAGE TRANSACTIONAL=0;

-- ----------------------------
--  Table structure for `meetings`
-- ----------------------------
DROP TABLE IF EXISTS `meetings`;
CREATE TABLE `meetings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` bigint(20) unsigned NOT NULL DEFAULT '0',
  `location` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=Aria AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1 ROW_FORMAT=PAGE TRANSACTIONAL=0;

-- ----------------------------
--  Table structure for `members`
-- ----------------------------
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `position` varchar(25) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `preferredMethod` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=Aria AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1 ROW_FORMAT=PAGE TRANSACTIONAL=0;

-- ----------------------------
--  Table structure for `rsvp`
-- ----------------------------
DROP TABLE IF EXISTS `rsvp`;
CREATE TABLE `rsvp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `meeting` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `choice` int(1) unsigned NOT NULL DEFAULT '0',
  `date` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=Aria AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1 ROW_FORMAT=PAGE TRANSACTIONAL=0;

-- ----------------------------
--  Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=Aria DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1;

-- ----------------------------
--  Table structure for `sidebar`
-- ----------------------------
DROP TABLE IF EXISTS `sidebar`;
CREATE TABLE `sidebar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=Aria AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `docid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`docid`)
) ENGINE=Aria AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 PAGE_CHECKSUM=1 ROW_FORMAT=PAGE TRANSACTIONAL=1;

SET FOREIGN_KEY_CHECKS = 1;
