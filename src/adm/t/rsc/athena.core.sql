DROP DATABASE IF EXISTS `athenace`;

CREATE DATABASE IF NOT EXISTS `athenace`;

USE `athenace`;

DROP TABLE IF EXISTS `owner`;
CREATE TABLE `owner` (
  `ownerid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `co_name` varchar(128) NOT NULL,
  `addsid` int(10) unsigned DEFAULT NULL,
  `colour` varchar(7) DEFAULT '#2c0673',
  `vat_no` varchar(45) DEFAULT NULL,
  `co_no` varchar(45) DEFAULT NULL,
  `athenaemail` varchar(127) DEFAULT NULL,
  `athenaemailpw` varchar(45) DEFAULT NULL,
  `emailsmtpsrv` varchar(127) DEFAULT NULL,
  `domain` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`ownerid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `staffid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fname` varchar(45) NOT NULL,
  `sname` varchar(45) DEFAULT NULL,
  `addsid` int(10) unsigned DEFAULT NULL,
  `jobtitle` varchar(128) DEFAULT NULL,
  `status` enum('active','retired','left','temp') NOT NULL,
  `lastlogin` int(10) unsigned DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`staffid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `contactsid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` enum('Mr','Ms','Mrs','Dr','Sir') DEFAULT NULL,
  `fname` varchar(45) DEFAULT NULL,
  `sname` varchar(45) DEFAULT NULL,
  `co_name` varchar(128) DEFAULT NULL,
  `role` varchar(128) DEFAULT NULL,
  `custid` int(10) unsigned DEFAULT NULL,
  `suppid` int(10) unsigned DEFAULT NULL,
  `addsid` int(10) unsigned DEFAULT '100',
  `notes` text,
  `lastlogin` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`contactsid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `custid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `co_name` varchar(128) NOT NULL,
  `contact` varchar(128) DEFAULT NULL,
  `addsid` int(10) unsigned DEFAULT NULL,
  `colour` varchar(7) DEFAULT '#2c0673',
  PRIMARY KEY (`custid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
  `suppid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `co_name` varchar(128) NOT NULL,
  `contact` varchar(128) DEFAULT NULL,
  `addsid` int(10) unsigned DEFAULT NULL,
  `colour` varchar(7) DEFAULT '#2c0673',
  PRIMARY KEY (`suppid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `log`;

DROP TABLE IF EXISTS `pwd`;
CREATE TABLE `pwd` (
  `pwdid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usr` varchar(45) NOT NULL,
  `staffid` int(10) unsigned DEFAULT NULL,
  `contactsid` int(10) unsigned DEFAULT NULL,
  `seclev` smallint(5) unsigned DEFAULT '10',
  `pw` varchar(512) NOT NULL,
  PRIMARY KEY (`pwdid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes` (
  `quotesid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staffid` int(10) unsigned DEFAULT '1',
  `custid` int(10) unsigned NOT NULL,
  `contactsid` int(10) unsigned DEFAULT NULL,
  `incept` int(10) unsigned NOT NULL,
  `agree` int(10) unsigned NOT NULL,
  `live` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `notes` text,
  `origin` varchar(10) DEFAULT NULL,
  `price` decimal(10,2),
  PRIMARY KEY (`quotesid`) USING BTREE,
  KEY `FK_quotes_1` (`custid`),
  KEY `FK_quotes_2` (`staffid`),
  KEY `FK_quotes_3` (`contactsid`),
  CONSTRAINT `FK_quotes_1` FOREIGN KEY (`custid`) REFERENCES `customer` (`custid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `invoicesid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `custid` int(10) unsigned NOT NULL,
  `contactsid` int(10) unsigned DEFAULT NULL,
  `incept` int(10) unsigned NOT NULL,
  `paid` int(10) unsigned DEFAULT '0',
  `content` text,
  `price` decimal(10,2),
  `notes` text,
  PRIMARY KEY (`invoicesid`),  
  CONSTRAINT `FK_invoices_1` FOREIGN KEY (`custid`) REFERENCES `customer` (`custid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `sitelog`;
CREATE TABLE `sitelog` (
  `sitelogid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `incept` int(10) unsigned NOT NULL,
  `staffid` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `eventsid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sitelogid`),
  KEY `FK_sitelog_1` (`staffid`),
  CONSTRAINT `FK_sitelog_1` FOREIGN KEY (`staffid`) REFERENCES `staff` (`staffid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mail`;
CREATE TABLE `mail` (
  `mailid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addto` varchar(256) DEFAULT NULL,
  `addname` varchar(256) DEFAULT NULL,
  `subject` varchar(256) DEFAULT NULL,
  `body` text,
  `sent` int(10) unsigned DEFAULT '0',
  `incept` int(10) unsigned DEFAULT NULL,
  `timesent` int(10) unsigned DEFAULT NULL,
  `docname` varchar(256) DEFAULT NULL,
  `doctitle` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`mailid`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `eventsid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`eventsid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `addsid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `add1` varchar(128) DEFAULT NULL,
  `add2` varchar(128) DEFAULT NULL,
  `add3` varchar(128) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `county` varchar(128) DEFAULT NULL,
  `country` varchar(128) DEFAULT NULL,
  `postcode` varchar(128) DEFAULT NULL,
  `tel` varchar(45) DEFAULT NULL,
  `mob` varchar(56) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `web` varchar(128) DEFAULT NULL,
  `facebook` varchar(256) DEFAULT NULL,
  `twitter` varchar(256) DEFAULT NULL,
  `linkedin` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`addsid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `events` VALUES (1,'Quote Added'),(2,'Quote Emailed to customer'),(3,'Job Created'),(4,'Invoice Created'),(5,'Quote Edited'),(6,'New Contact Added'),(7,'New External Contact Added'),(8,'Edited an External Contact'),(9,'New Job Folders Added'),(10,'New Quote Folder Added'),(11,'Quote Files Moved'),(13,'New Customer'),(14,'Invoice Emailed to customer'),(15,'New Staff Member Added'),(16,'Job Stage Change'),(17,'Job Priority Change'),(18,'SMS Received'),(19,'SMS Sent'),(20,'Request for Quote from Supplier Added'),(21,'Stock Added'),(22,'Supplier Quoted on Stock'),(23,'Stock Quote Agree'),(24,'Supplier sent a request to Quote'),(25,'Goods Deleivered and Signed for'),(26,'Athena Log in'),(27,'Staff Log in'),(28,'Customer Log in'),(29,'Supplier Log in'),(30,'Log Out'),(31,'Failed Log in'),(32,'Sent Email');

INSERT INTO `staff` VALUES (100,'System','Administrator',100,'System Administrator','active',1432735764,'');
INSERT INTO `pwd` VALUES (0,'root',100,NULL,1,'PWDHASH');
INSERT INTO `owner` VALUES (100,'Co Name',100,'#2c0673','','','','','',NULL);
INSERT INTO `address` VALUES (100,'Address Line1','','','','','','','','','','','','','','');

GRANT ALL ON athenace.* TO athena@'localhost' IDENTIFIED BY 'ATHENAMYSQLPWD';
FLUSH PRIVILEGES;
