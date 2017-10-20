-- phpMyAdmin SQL
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Server version: 5.0.67
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `armadealo_vendor`
--

DROP TABLE IF EXISTS `offers2augments`;
DROP TABLE IF EXISTS `offers2stores`;
DROP TABLE IF EXISTS `stores`;
DROP TABLE IF EXISTS `vendors`;
DROP TABLE IF EXISTS `clients`;
DROP TABLE IF EXISTS `types`;
DROP TABLE IF EXISTS `offers`;
DROP TABLE IF EXISTS `country`;
DROP TABLE IF EXISTS `state`;
DROP TABLE IF EXISTS `augments`;
DROP TABLE IF EXISTS `reports`;
DROP TABLE IF EXISTS `payments`;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `cid` 				mediumint unsigned NOT NULL auto_increment,
  `disable`				tinyint(1) unsigned NOT NULL default '0',
  `ggsnsip`				char(16) collate utf8_unicode_ci NOT NULL default '',
  `created` 			timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='iphone client user track' AUTO_INCREMENT=12345678 ;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE IF NOT EXISTS `vendors` (
  `vid`					smallint unsigned NOT NULL auto_increment,
  `disable`				tinyint(1) unsigned NOT NULL default '0',
  `created`  			timestamp NOT NULL default CURRENT_TIMESTAMP,
  `updated`				timestamp NOT NULL default '0000-00-00 00:00:00',
  `username`			char(48) collate utf8_unicode_ci NOT NULL default '',
  `password`			char(32) collate utf8_unicode_ci NOT NULL default '',
  `emailadd`			char(48) collate utf8_unicode_ci NOT NULL default '',
  `ipatsignup`			char(16) collate utf8_unicode_ci NOT NULL default '',
  `vendor_name`			char(32) collate utf8_unicode_ci NOT NULL default '',
  `contact_name`		char(32) collate utf8_unicode_ci NOT NULL default '',
  `contact_phone`		char(16) collate utf8_unicode_ci NOT NULL default '',
  `primary_country`		char(4) collate utf8_unicode_ci NOT NULL default 'USA',
  `primary_address`		char(48) collate utf8_unicode_ci NOT NULL default '',  
  `primary_state`		char(4) collate utf8_unicode_ci NOT NULL default '',
  `primary_city`		char(32) collate utf8_unicode_ci NOT NULL default '',
  `primary_zip`			char(8) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`vid`),
  UNIQUE INDEX `vendors_ui_1` (`emailadd`),
  UNIQUE INDEX `vendors_ui_2` (`username`,`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='vendor contact and login data' AUTO_INCREMENT=12345 ;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE IF NOT EXISTS `stores` (
  `sid` 				mediumint unsigned NOT NULL auto_increment,
  `vid`					smallint unsigned NOT NULL,
  `disable`				tinyint(1) unsigned NOT NULL default '0',
  `store_lat` 			int signed NOT NULL default '0',
  `store_lon` 			int signed NOT NULL default '0',
  `store_open`			time NOT NULL default '00:00:00',
  `store_close`			time NOT NULL default '23:59:00',
  `store_title`			char(32) collate utf8_unicode_ci NOT NULL default '',
  `store_phone`			char(16) collate utf8_unicode_ci NOT NULL default '',
  `store_country`		char(4) collate utf8_unicode_ci NOT NULL default 'US',
  `store_address`		char(48) collate utf8_unicode_ci NOT NULL default '',  
  `store_state`			char(4) collate utf8_unicode_ci NOT NULL default '',
  `store_city`			char(32) collate utf8_unicode_ci NOT NULL default '',
  `store_zip`			char(8) collate utf8_unicode_ci NOT NULL default '',
  `direction`			char(64) collate utf8_unicode_ci NOT NULL default '',
  `updated`				timestamp NOT NULL default '0000-00-00 00:00:00',
  `created`  			timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY (`sid`),
  CONSTRAINT `stores_fk` FOREIGN KEY (`vid`) REFERENCES `vendors` (`vid`),
  UNIQUE INDEX `stores_ui` (`store_title`,`store_country`,`store_address`,`store_state`,`store_city`,`store_zip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='list of courses related to their departments' AUTO_INCREMENT=12345678 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `code` 				char(4) NOT NULL default '',
  `name`	 			char(24) collate utf8_unicode_ci NOT NULL default '',
  `disable` 			tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `country_ui` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='defines system tool links for display';

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `states` (
  `code` 				char(4) NOT NULL default '',
  `name`	 			char(48) collate utf8_unicode_ci NOT NULL default '',
  `country`				char(4) NOT NULL default '',
  `disable` 			tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `state_ui` (`code`,`country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='defines system tool links for display';

-- --------------------------------------------------------

--
-- Table structure for table `augments`
--

CREATE TABLE IF NOT EXISTS `augments` (
  `aid` 				tinyint unsigned NOT NULL auto_increment,
  `disable` 			tinyint(1) unsigned NOT NULL default '0',
  `affects` 			char(16) collate utf8_unicode_ci NOT NULL default '',
  `display` 			char(128) collate utf8_unicode_ci NOT NULL default '',
  `cost`	 			decimal(2,2) unsigned NOT NULL default '0',
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='defines system tool links for display' AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `tid` 				tinyint unsigned NOT NULL auto_increment,
  `disable`				tinyint(1) unsigned NOT NULL default '0',
  `offer_type` 			char(32) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY (`tid`),
  UNIQUE INDEX `types_ui` (`offer_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='defines system tool links for display' AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE IF NOT EXISTS `offers` (
  `oid` 				mediumint unsigned NOT NULL auto_increment,
  `vid`					smallint unsigned NOT NULL,
  `tid`					tinyint unsigned NOT NULL default '123',
  `disable`				tinyint(1) unsigned NOT NULL default '0',
  `expires`				date NOT NULL default '0000-00-00',
  `offer_code`			char(16) collate utf8_unicode_ci NOT NULL default '',
  `offer_head`			char(48) collate utf8_unicode_ci NOT NULL default '',
  `offer_body`			char(128) collate utf8_unicode_ci NOT NULL default '',
  `offer_link`			char(128) collate utf8_unicode_ci NOT NULL default '',
  `updated`				timestamp NOT NULL default '0000-00-00 00:00:00',
  `created`  			timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY (`oid`),
  CONSTRAINT `offers_fk_1` FOREIGN KEY (`vid`) REFERENCES `vendors` (`vid`),
  CONSTRAINT `offers_fk_2` FOREIGN KEY (`tid`) REFERENCES `types` (`tid`),
  UNIQUE INDEX `offers_ui` (`vid`,`offer_code`,`expires`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='defines system tool links for display' AUTO_INCREMENT=12345678 ;

-- --------------------------------------------------------

--
-- Table structure for table `offers2stores`
--

CREATE TABLE IF NOT EXISTS `offers2stores` (
  `oid` 				mediumint unsigned NOT NULL,
  `sid` 				mediumint unsigned NOT NULL,
  `disable`				tinyint(1) unsigned NOT NULL default '0',
  CONSTRAINT `offers2stores_fk_1` FOREIGN KEY (`oid`) REFERENCES `offers` (`oid`),
  CONSTRAINT `offers2stores_fk_2` FOREIGN KEY (`sid`) REFERENCES `stores` (`sid`),
  UNIQUE KEY `offers2stores_ui` (`oid`,`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='defines system tool links for display';

-- --------------------------------------------------------

--
-- Table structure for table `offers2stores`
--

CREATE TABLE IF NOT EXISTS `offers2augments` (
  `oid` 				mediumint unsigned NOT NULL,
  `aid` 				tinyint unsigned NOT NULL,
  `value` 				char(16) collate utf8_unicode_ci NOT NULL default '',
  `disable`				tinyint(1) unsigned NOT NULL default '0',
  CONSTRAINT `offers2augments_fk_1` FOREIGN KEY (`oid`) REFERENCES `offers` (`oid`),
  CONSTRAINT `offers2augments_fk_2` FOREIGN KEY (`aid`) REFERENCES `augments` (`aid`),
  UNIQUE KEY `offers2augments_ui` (`oid`,`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='defines system tool links for display';

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `cid` 				mediumint unsigned NOT NULL,
  `oid` 				mediumint unsigned NOT NULL,
  `sid` 				mediumint unsigned NOT NULL,
  `feed_lat` 			int signed NOT NULL default '0',
  `feed_lon` 			int signed NOT NULL default '0',
  `feed_tid` 			tinyint unsigned NOT NULL default '123',
  `feed_get`			timestamp NOT NULL default '0000-00-00 00:00:00',
  `feed_put`  			timestamp NOT NULL default CURRENT_TIMESTAMP,
  CONSTRAINT `reports_fk_1` FOREIGN KEY (`cid`) REFERENCES `clients` (`cid`),
  CONSTRAINT `reports_fk_2` FOREIGN KEY (`oid`) REFERENCES `offers` (`oid`),
  CONSTRAINT `reports_fk_3` FOREIGN KEY (`sid`) REFERENCES `stores` (`sid`),
  UNIQUE KEY `reports_ui` (`cid`,`oid`,`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='list of available semesters for this institution';

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `pid`					mediumint unsigned NOT NULL auto_increment,
  `oid`					mediumint unsigned NOT NULL,
  `status`				tinyint(1) signed NOT NULL default '0',
  `recurs`				tinyint(1) unsigned NOT NULL default '0',
  `stores`				smallint unsigned NOT NULL default '1',
  `billing_name`		char(32) collate utf8_unicode_ci NOT NULL default '',
  `billing_country`		char(4) collate utf8_unicode_ci NOT NULL default 'USA',
  `billing_address`		char(48) collate utf8_unicode_ci NOT NULL default '',  
  `billing_state`		char(4) collate utf8_unicode_ci NOT NULL default '',
  `billing_city`		char(32) collate utf8_unicode_ci NOT NULL default '',
  `billing_zip`			char(8) collate utf8_unicode_ci NOT NULL default '',
  `ipaddress`			char(16) collate utf8_unicode_ci NOT NULL default '',
  `card_num`			char(32) collate utf8_unicode_ci NOT NULL default '',
  `card_cvc`			char(4) collate utf8_unicode_ci NOT NULL default '',
  `card_exp`			date NOT NULL default '0000-00-00',
  `expires`				timestamp NOT NULL default '0000-00-00 00:00:00',
  `receipt`				timestamp NOT NULL default '0000-00-00 00:00:00',
  `created`  			timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`),
  CONSTRAINT `payments_fk_1` FOREIGN KEY (`oid`) REFERENCES `offers` (`oid`),
  UNIQUE INDEX `payments_ui` (`oid`,`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='list of available terms in a semester for this institution' AUTO_INCREMENT=12345678;

-- --------------------------------------------------------

INSERT INTO `augments` (`aid`,`affects`,`display`,`cost`) VALUES (123, 'offer_head', 'Bring more attention to your offer by making the title a custom color:', 0.10);
INSERT INTO `augments` (`aid`,`affects`,`display`,`cost`) VALUES (124, 'offer_body', 'Add a second custom color to background the offer details, for more of a design effect:', 0.15);
INSERT INTO `augments` (`aid`,`affects`,`display`,`cost`) VALUES (125, 'offer_body', 'Make your offer really stand-out with bold text for the offer information:', 0.20);
INSERT INTO `augments` (`aid`,`disable`,`affects`,`display`,`cost`) VALUES (126, 1, 'offer_body', 'Add a product or store photo to your offer campaign:', 0.25);

INSERT INTO `types` (`tid`,`offer_type`) VALUES (123, "ALL OFFERS!");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (124, "Apparrel (any)");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (125, "Apparrel/accessories");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (126, "Apparrel/clothing");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (127, "Apparrel/shoes");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (128, "Automotive (any)");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (129, "Automotive/parts");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (130, "Automotive/tires");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (131, "Automotive/service");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (132, "Automotive/sales");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (133, "Electronics (any)");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (134, "Electronics/desktops");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (135, "Electronics/consoles");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (136, "Electronics/games");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (137, "Electronics/televisions");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (138, "Food (any)");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (139, "Food/dine-in");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (140, "Food/take-out");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (141, "GAS!");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (142, "Groceries/traditional");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (143, "Groceries/organic");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (144, "Toys & Gifts");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (145, "Hobbies & Crafts");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (146, "Sporting Goods");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (147, "Bath & Body");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (148, "Beauty & Health");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (149, "Office (any)");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (150, "Office/supplies");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (151, "Office/equipment");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (152, "Printing & Copy");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (153, "Food/delivery");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (154, "Groceries (any)");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (155, "Books & Magazines");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (156, "Music & Movies");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (157, "Electronics/laptops");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (158, "Electronics/printers");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (159, "Electronics/monitors");
INSERT INTO `types` (`tid`,`offer_type`) VALUES (160, "Electronics/software");



INSERT INTO `countries` (`code`,`name`,`disable`) VALUES ("US","United States",0);
INSERT INTO `countries` (`code`,`name`,`disable`) VALUES ("CA","Canada",1);
INSERT INTO `countries` (`code`,`name`,`disable`) VALUES ("MX","Mexico",1);



INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("AL","Alabama","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("AK","Alaska","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("AS","American Samoa","US",1);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("AZ","Arizona","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("AR","Arkansas","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("CA","California","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("CO","Colorado","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("CT","Connecticut","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("DE","Delaware","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("DC","District of Columbia","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("FM","Federated States of Micronesia","US",1);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("FL","Florida","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("GA","Georgia","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("GU","Guam","US",1);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("HI","Hawaii","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("ID","Idaho","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("IL","Illinois","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("IN","Indiana","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("IA","Iowa","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("KS","Kansas","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("KY","Kentucky","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("LA","Louisiana","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("ME","Maine","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MH","Marshall Islands","US",1);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MD","Maryland","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MA","Massachusetts","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MI","Michigan","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MN","Minnesota","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MS","Mississippi","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MO","Missouri","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MT","Montana","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("NE","Nebraska","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("NV","Nevada","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("NH","New Hampshire","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("NJ","New Jersey","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("NM","New Mexico","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("NY","New York","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("NC","North Carolina","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("ND","North Dakota","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("MP","Northern Mariana Islands","US",1);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("OH","Ohio","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("OK","Oklahoma","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("OR","Oregon","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("PW","Palau","US",1);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("PA","Pennsylvania","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("PR","Puerto Rico","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("RI","Rhode Island","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("SC","South Carolina","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("SD","South Dakota","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("TN","Tennessee","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("TX","Texas","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("UT","Utah","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("VT","Vermont","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("VI","Virgin Islands","US",1);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("VA","Virginia","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("WA","Washington","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("WV","West Virginia","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("WI","Wisconsin","US",0);
INSERT INTO `states` (`code`,`name`,`country`,`disable`) VALUES ("WY","Wyoming","US",0);
