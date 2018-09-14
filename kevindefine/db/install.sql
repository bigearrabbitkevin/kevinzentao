# Kevin
# For db Change, please open :kevindefine.update.sql

# Structure for table "zt_kevincalendar" 
#

CREATE TABLE IF NOT EXISTS `zt_kevincalendar` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `calendar` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` enum('nor','hol','law') NOT NULL DEFAULT 'nor',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `desc` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

# Date: 2015-7-8
# update to custom for zentao 

#
# Structure for table "zt_hourscashcode"
#
CREATE TABLE IF NOT EXISTS `zt_hourscashcode` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `dept` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `deptdispatch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `account` char(30) NOT NULL DEFAULT '',
  `year` int(4) NOT NULL DEFAULT '0',
  `month` int(2) NOT NULL DEFAULT '0',
  `cashCode` varchar(45) NOT NULL DEFAULT '',
  `hours` float(11,3) NOT NULL DEFAULT '0.000',
  `amountto` float(11,3) NOT NULL DEFAULT '0.000',
  `total` float(11,3) NOT NULL DEFAULT '0.000',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `lastEditedBy` varchar(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`account`,`year`,`month`,`cashCode`)
) ENGINE=MyISAM AUTO_INCREMENT=841 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "zt_kevinclockact"
#
CREATE TABLE IF NOT EXISTS `zt_kevinclockact` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `time` smallint(4) unsigned zerofill NOT NULL,
  `action` enum('in','out') NOT NULL DEFAULT 'in',
  `desc` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


#------kevinplan-------------------------------------------------------------------------------


#
# Structure for table "kv_plan_member"
#

CREATE TABLE IF NOT EXISTS `kv_plan_member` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'project Plan ID',
  `status` enum('draft','planned','planned','developing','pause','cancel','closed') NOT NULL DEFAULT 'draft',
  `dept` int(11) NOT NULL DEFAULT '0',
  `member` char(30) NOT NULL DEFAULT '',
  `notes` varchar(50) NOT NULL DEFAULT '',
  `hours` int(11) NOT NULL DEFAULT '0',
  `hoursCost` int(11) NOT NULL DEFAULT '0',
  `IsFinished` tinyint(3) NOT NULL DEFAULT '0',
  `startDate` date NOT NULL DEFAULT '0000-00-00',
  `endDate` date NOT NULL DEFAULT '0000-00-00',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `addedBy` varchar(30) NOT NULL DEFAULT '' COMMENT 'author',
  `addedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date of create',
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '' COMMENT 'last edit by',
  `lastEditedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'time of last edit',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_plan_list"
#

CREATE TABLE IF NOT EXISTS `kv_plan_list` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '',
  `charger` char(30) NOT NULL DEFAULT '',
  `members` text NOT NULL,
  `planYear` int(11) NOT NULL DEFAULT '2016',
  `chargerName` char(30) NOT NULL DEFAULT '',
  `dept` int(11) NOT NULL DEFAULT '0',
  `hoursPlan` int(11) NOT NULL DEFAULT '2000' COMMENT 'hours Plan',
  `IsFinished` tinyint(3) NOT NULL DEFAULT '0',
  `startDate` date NOT NULL DEFAULT '0000-00-00',
  `endDate` date NOT NULL DEFAULT '0000-00-00',
  `status` enum('draft','planned','planned','developing','pause','cancel','closed') NOT NULL DEFAULT 'draft',
  `lock` tinyint(3) NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `addedBy` varchar(30) NOT NULL DEFAULT '' COMMENT 'author',
  `addedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date of create',
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '' COMMENT 'last edit by',
  `lastEditedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'time of last edit',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

#
# Structure for table "kv_plan_project"
#

CREATE TABLE IF NOT EXISTS `kv_plan_project` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `plan` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `planYear` int(11) NOT NULL DEFAULT '2017',
  `projectCode` int(11) NOT NULL DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `charger` char(30) NOT NULL DEFAULT '' COMMENT 'First Charger',
  `pri` enum('0','1','2','3','4','d1','d2','d3','d4','k1','k2','k3','k4','m1','m2','m3','m4') NOT NULL DEFAULT '0',
  `charger2` char(30) NOT NULL DEFAULT '' COMMENT 'person in charge2',
  `dept` int(11) NOT NULL DEFAULT '0',
  `hoursPlan` int(11) NOT NULL DEFAULT '500' COMMENT 'hours Plan',
  `hoursCost` int(11) NOT NULL DEFAULT '0',
  `IsFinished` tinyint(3) NOT NULL DEFAULT '0',
  `startDate` date NOT NULL DEFAULT '0000-00-00',
  `endDate` date NOT NULL DEFAULT '0000-00-00',
  `status` enum('draft','planned','finished','developing','pause','cancel','closed') NOT NULL DEFAULT 'draft',
  `lock` tinyint(3) NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `addedBy` varchar(30) NOT NULL DEFAULT '' COMMENT 'author',
  `addedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date of create',
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '' COMMENT 'last edit by',
  `lastEditedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'time of last edit',
  `notes` varchar(255) NOT NULL DEFAULT '',
  `classPro` int(11) NOT NULL DEFAULT '0' COMMENT 'project class',
  `dateBuild` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date build',
  `dateDR2` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date DR2',
  `dateDR3` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date DR3',
  `dateDR4` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date DR4',
  `dateRelease` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date Release',
  `ProNew` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

#
# Structure for table "kv_plan_projectgroup"
#

CREATE TABLE IF NOT EXISTS `kv_plan_projectgroup` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `plan` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'project Plan ID',
  `addedBy` varchar(30) NOT NULL DEFAULT '' COMMENT 'author',
  `addedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date of create',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kv_plan_projectgroup_unique` (`plan`,`project`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;



#----------kevin_char---------------------------------------------------------------------------------------

#
# Structure for table "zt_kevin_chartexample"
#

CREATE TABLE IF NOT EXISTS `zt_kevin_chartexample` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `start` int(6) unsigned NOT NULL DEFAULT '0',
  `total` int(6) unsigned NOT NULL DEFAULT '0',
  `monitor` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


#----------kevinsoft---------------------------------------------------------------------------------------

#
# Structure for table "kv_soft_file"
#

CREATE TABLE IF NOT EXISTS `kv_soft_file` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pathname` char(50) NOT NULL,
  `title` char(90) NOT NULL,
  `extension` char(30) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `addedBy` char(30) NOT NULL DEFAULT '',
  `addedDate` datetime NOT NULL,
  `downloads` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `extra` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_soft_groupversion"
#

CREATE TABLE IF NOT EXISTS `kv_soft_groupversion` (
  `groupversion` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  UNIQUE KEY `kv_update_versionfiles_unique` (`groupversion`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_soft_list"
#

CREATE TABLE IF NOT EXISTS `kv_soft_list` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `IID` char(50) NOT NULL DEFAULT '',
  `name` char(50) NOT NULL DEFAULT '',
  `valid` enum('0','1') NOT NULL DEFAULT '1',
  `type` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0:���������1�������',
  `addedBy` char(30) NOT NULL DEFAULT '',
  `addedDate` datetime NOT NULL,
  `lastEditedBy` varchar(29) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kv_update_soft_IID` (`IID`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_soft_module"
#

CREATE TABLE IF NOT EXISTS `kv_soft_module` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `device` mediumint(9) NOT NULL DEFAULT '0',
  `software` mediumint(9) NOT NULL DEFAULT '0',
  `type` enum('float','fix') NOT NULL DEFAULT 'fix',
  `module` varchar(50) NOT NULL DEFAULT '',
  `notes` varchar(100) NOT NULL DEFAULT '',
  `count` mediumint(9) NOT NULL DEFAULT '1',
  `startDate` date NOT NULL DEFAULT '0000-00-00',
  `endDate` date NOT NULL DEFAULT '0000-00-00',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

#
# Structure for table "kv_soft_version"
#

CREATE TABLE IF NOT EXISTS `kv_soft_version` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `soft` mediumint(9) NOT NULL DEFAULT '0',
  `version` char(50) NOT NULL DEFAULT '',
  `downloads` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `valid` enum('0','1') NOT NULL DEFAULT '1',
  `md5` char(32) NOT NULL DEFAULT '',
  `replaceType` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0:ȫĿ¼�滻��1�������ļ��滻',
  `addedBy` char(50) NOT NULL DEFAULT '',
  `addedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastEditedBy` varchar(29) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `type` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0:ȫĿ¼�滻��1�������ļ��滻',
  `name` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kv_update_version_nameversion` (`soft`,`version`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#------kevindevice-------------------------------------
# Author: Kevin Yang
# Date: 2016-12-28 

#
# Structure for table "kevindevice_devlist"
#

CREATE TABLE IF NOT EXISTS `kevindevice_devlist` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '',
  `label` varchar(100) DEFAULT NULL,
  `group` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` enum('thinclient','station','pc','vxstation') NOT NULL DEFAULT 'thinclient',
  `status` enum('normal','discard','wrong','unknown') NOT NULL DEFAULT 'normal',
  `dept` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `charge` char(30) NOT NULL DEFAULT '',
  `user` char(30) NOT NULL DEFAULT '',
  `version` char(50) NOT NULL DEFAULT '',
  `join` date NOT NULL DEFAULT '0000-00-00',
  `description` text NOT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  `tcpip` char(50) NOT NULL DEFAULT '',
  `cpuID` char(50) NOT NULL DEFAULT '',
  `deviceSN` char(50) NOT NULL DEFAULT '',
  `monitorSN` varchar(50) NOT NULL DEFAULT '',
  `monitorVersion` varchar(50) NOT NULL DEFAULT '',
  `assetNumber` varchar(50) NOT NULL DEFAULT '',
  `vidioCard` varchar(50) NOT NULL DEFAULT '',
  `discCapacity` varchar(50) NOT NULL DEFAULT '',
  `memoryCapacity` varchar(50) NOT NULL DEFAULT '',
  `system` varchar(50) NOT NULL DEFAULT '',
  `mac` varchar(50) NOT NULL DEFAULT '',
  `purpose` varchar(100) NOT NULL DEFAULT '',
  `displayName` varchar(50) NOT NULL DEFAULT '',
  `dieDate` date NOT NULL DEFAULT '0000-00-00',
  `manageip` char(50) NOT NULL DEFAULT '',
  `count` int(11) NOT NULL DEFAULT '1',
  `loginaddr` varchar(255) NOT NULL DEFAULT '',
  `repairstart` date NOT NULL DEFAULT '0000-00-00',
  `repairend` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `dept` (`dept`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kevindevice_group"
#

CREATE TABLE IF NOT EXISTS `kevindevice_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  `type` enum('station','laptop','server','other','discard') NOT NULL DEFAULT 'station',
  `desc` char(255) NOT NULL DEFAULT '',
  `createdate` date NOT NULL DEFAULT '0000-00-00',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kevindevice_maintainlist"
#

CREATE TABLE IF NOT EXISTS `kevindevice_maintainlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` date NOT NULL DEFAULT '0000-00-00',
  `platform` int(11) NOT NULL DEFAULT '0',
  `log` int(11) NOT NULL DEFAULT '0',
  `sendout` int(11) NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

#
# Structure for table "kevindevice_spotchklist"
#

CREATE TABLE IF NOT EXISTS `kevindevice_spotchklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

#
# Structure for table "kevindevice_sendoutlist". 2017-7-26
#

CREATE TABLE IF NOT EXISTS `kevindevice_sendoutlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` date NOT NULL DEFAULT '0000-00-00',
  `sendout` int(11) unsigned NOT NULL DEFAULT '0',
  `total` int(11) unsigned NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#-------------------------------------------

#
# Structure for table "zt_kevinkendoui"
#

CREATE TABLE IF NOT EXISTS `zt_kevinkendoui` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` char(20) DEFAULT NULL,
  `computer` char(50) DEFAULT NULL,
  `activetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


#
# Structure for table "kv_hoursb_monthcount"
#

#
# Structure for table "kv_hoursb_monthcount"
#

CREATE TABLE IF NOT EXISTS `kv_hoursb_monthcount` (
  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `ClassDept` mediumint(11) NOT NULL DEFAULT '0',
  `YearMonth` mediumint(6) NOT NULL DEFAULT '0',
  `account` varchar(30) NOT NULL DEFAULT '',
  `dept` mediumint(11) NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  `ChuQin` double NOT NULL DEFAULT '0',
  `GongChu` double NOT NULL DEFAULT '0',
  `TiaoXiu` double NOT NULL DEFAULT '0',
  `ShangJia` double NOT NULL DEFAULT '0',
  `ChanJia` double NOT NULL DEFAULT '0',
  `BingJia` double NOT NULL DEFAULT '0',
  `ShiJia` double NOT NULL DEFAULT '0',
  `NianJia` double NOT NULL DEFAULT '0',
  `GuoJia` double NOT NULL DEFAULT '0',
  `ShuangXiu` double NOT NULL DEFAULT '0',
  `PingShi` double NOT NULL DEFAULT '0',
  `ZheHe` double NOT NULL DEFAULT '0',
  `ZhongBan` double NOT NULL DEFAULT '0',
  `YeBan` double NOT NULL DEFAULT '0',
  `DaYeBan` double NOT NULL DEFAULT '0',
  `Hours` double NOT NULL DEFAULT '0',
  `Days` double NOT NULL DEFAULT '0',
  `YuShu` double NOT NULL DEFAULT '0',
  `ZheHeDays` double NOT NULL DEFAULT '0',
  `BI` double NOT NULL DEFAULT '0',
  `status` enum('draft','check','public','cancel') NOT NULL DEFAULT 'draft',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kv_hoursb_monthcount_monthaccount` (`YearMonth`,`account`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

#
# Structure for table "kv_hoursb_monthcount_input"
#

CREATE TABLE IF NOT EXISTS `kv_hoursb_monthcount_input` (
  `id` mediumint(1) NOT NULL AUTO_INCREMENT,
  `ClassDept` mediumint(1) NOT NULL DEFAULT '0',
  `YearMonth` mediumint(6) NOT NULL DEFAULT '0',
  `code` char(10) NOT NULL DEFAULT '',
  `code2` char(10) NOT NULL DEFAULT '',
  `account` varchar(30) NOT NULL DEFAULT '',
  `dept` mediumint(1) NOT NULL DEFAULT '0',
  `FinishInput` tinyint(3) NOT NULL DEFAULT '0',
  `D1` varchar(30) NOT NULL DEFAULT '',
  `D2` varchar(30) NOT NULL DEFAULT '',
  `D3` varchar(30) NOT NULL DEFAULT '',
  `D4` varchar(30) NOT NULL DEFAULT '',
  `D5` varchar(30) NOT NULL DEFAULT '',
  `D6` varchar(30) NOT NULL DEFAULT '',
  `D7` varchar(30) NOT NULL DEFAULT '',
  `D8` varchar(30) NOT NULL DEFAULT '',
  `D9` varchar(30) NOT NULL DEFAULT '',
  `D10` varchar(30) NOT NULL DEFAULT '',
  `D11` varchar(30) NOT NULL DEFAULT '',
  `D12` varchar(30) NOT NULL DEFAULT '',
  `D13` varchar(30) NOT NULL DEFAULT '',
  `D14` varchar(30) NOT NULL DEFAULT '',
  `D15` varchar(30) NOT NULL DEFAULT '',
  `D16` varchar(30) NOT NULL DEFAULT '',
  `D17` varchar(30) NOT NULL DEFAULT '',
  `D18` varchar(30) NOT NULL DEFAULT '',
  `D19` varchar(30) NOT NULL DEFAULT '',
  `D20` varchar(30) NOT NULL DEFAULT '',
  `D21` varchar(30) NOT NULL DEFAULT '',
  `D22` varchar(30) NOT NULL DEFAULT '',
  `D23` varchar(30) NOT NULL DEFAULT '',
  `D24` varchar(30) NOT NULL DEFAULT '',
  `D25` varchar(30) NOT NULL DEFAULT '',
  `D26` varchar(30) NOT NULL DEFAULT '',
  `D27` varchar(30) NOT NULL DEFAULT '',
  `D28` varchar(30) NOT NULL DEFAULT '',
  `D29` varchar(30) NOT NULL DEFAULT '',
  `D30` varchar(30) NOT NULL DEFAULT '',
  `D31` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


#
# Structure for table "kv_svn_authz"
#

CREATE TABLE IF NOT EXISTS `kv_svn_authz` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `rep` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `folder` char(255) NOT NULL DEFAULT '/',
  `user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authz` enum('','r','w','rw') NOT NULL DEFAULT 'r',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rep+folder+user` (`rep`,`folder`,`user`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_svn_repositories"
#

CREATE TABLE IF NOT EXISTS `kv_svn_repositories` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dept` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `charger` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `class` char(10) NOT NULL DEFAULT '',
  `disable` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_svn_user"
#

CREATE TABLE IF NOT EXISTS `kv_svn_user` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `dept` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `account` char(30) NOT NULL DEFAULT '',
  `svnaccount` char(30) NOT NULL DEFAULT '',
  `windowsID` char(50) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `disable` enum('0','1') NOT NULL DEFAULT '0',
  `type` enum('normal','admin') NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`id`),
  KEY `svnaccount` (`svnaccount`),
  KEY `windowsID` (`windowsID`),
  KEY `account` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


#
# Structure for table "kv_class_item" 2017-10-31
#

CREATE TABLE `kv_class_item` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(30) NOT NULL DEFAULT '',
  `title` char(30) NOT NULL DEFAULT '',
  `titleCN` varchar(30) NOT NULL DEFAULT '',
  `type` enum('book','chapter','article') NOT NULL DEFAULT 'chapter',
  `subtype` int(11) unsigned NOT NULL DEFAULT '0',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `author` varchar(60) NOT NULL,
  `editor` varchar(60) NOT NULL,
  `addedDate` datetime NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `tempSouceID` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parentIID_Unique` (`parent`,`code`),
  KEY `order` (`order`),
  KEY `parent` (`parent`),
  KEY `path` (`path`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_user_class" 2017-12-19
#

CREATE TABLE IF NOT EXISTS `kv_user_class` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL DEFAULT '',
  `classify1` varchar(50) NOT NULL DEFAULT '',
  `classify2` varchar(50) NOT NULL DEFAULT '',
  `classify3` varchar(50) NOT NULL DEFAULT '',
  `classify4` varchar(50) NOT NULL DEFAULT '',
  `classname` varchar(100) NOT NULL DEFAULT '',
  `payrate` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hourFee` decimal(6,2) NOT NULL DEFAULT '0.00',
  `conversionFee` decimal(8,2) NOT NULL DEFAULT '0.00',
  `start` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `jobRequirements` varchar(255) NOT NULL DEFAULT '',
  `monthFee` decimal(8,2) NOT NULL DEFAULT '0.00',
  `remarks` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `locked` enum('draft','lock') NOT NULL DEFAULT 'draft',
  PRIMARY KEY (`id`),
  UNIQUE KEY `classname` (`role`,`classify1`,`classify2`,`classify3`,`classify4`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_user_record" 2017-12-19
#

CREATE TABLE IF NOT EXISTS `kv_user_record` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL DEFAULT '',
  `class` mediumint(9) NOT NULL DEFAULT '0',
  `start` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `locked` enum('draft','lock') NOT NULL DEFAULT 'draft',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`,`start`,`end`,`class`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

#2017-12-5 move in kevinlogin table

#
# Structure for table "zt_defaultpassword"
#

CREATE TABLE IF NOT EXISTS `zt_defaultpassword` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `password` char(32) NOT NULL DEFAULT '',
  `source` char(32) NOT NULL DEFAULT '' COMMENT 'source for password',
  PRIMARY KEY (`id`),
  UNIQUE KEY `password` (`password`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#
# Structure for table "kv_ldapuser"
#

CREATE TABLE IF NOT EXISTS `kv_ldapuser` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(100) DEFAULT NULL,
  `remote` char(30) DEFAULT NULL,
  `local` char(30) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `kv_lapuser_domain_remote` (`domain`,`remote`),
  KEY `kv_lapuser_remote` (`remote`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
