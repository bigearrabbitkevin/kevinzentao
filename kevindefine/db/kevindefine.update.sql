#the following you can select to do
# for table `zt_project`
ALTER TABLE `zt_project` ADD `cashCode` varchar(45) NOT NULL DEFAULT '';

# for table `zt_todo`
ALTER TABLE `zt_todo` ADD `hourstype` char(10) NOT NULL DEFAULT 'nor';
ALTER TABLE `zt_todo` ADD `project` int(11) NOT NULL DEFAULT 0;
ALTER TABLE `zt_todo` ADD `minutes` int(11) unsigned NOT NULL DEFAULT 0;

# for table `zt_user`
ALTER TABLE `zt_user` ADD `code` varchar(45) NOT NULL DEFAULT '';
ALTER TABLE `zt_user` ADD `deptdispatch` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_user` ADD `ratepay` double NOT NULL DEFAULT '20';
ALTER TABLE `zt_user` ADD `domainFullAccount` char(100) DEFAULT NULL COMMENT 'such as kevin@tom.com';
ALTER TABLE `zt_user` ADD UNIQUE KEY `kevin_domainFullAccount_unique` (`domainFullAccount`);

#for table `zt_dept`
ALTER TABLE `zt_dept` ADD  `deleted` enum('0','1') NOT NULL DEFAULT '0';

#update default value
update zt_user set domainFullAccount = null where domainFullAccount = "";
update zt_user a set a.ratepay = 20 where a.ratepay = 0 ;

#remove Todo priv fro view��
#delete a.* from zt_grouppriv a where a.module = 'todo' and a.method != 'view';

-- ---------kevindevice-------------
# the following is for update from old kevin plugin. if you install the latest version at first time. do not need these
# this is update sql if you do not have these columns. 2016-7-28
ALTER TABLE `kevindevice_devlist` ADD `manageip` char(50) NOT NULL DEFAULT '';
ALTER TABLE `kevindevice_devlist` ADD `count` int(11) NOT NULL DEFAULT '1';
ALTER TABLE `kevindevice_devlist` ADD `loginaddr` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `kevindevice_devlist` ADD `repairstart` date NOT NULL DEFAULT '0000-00-00';
ALTER TABLE `kevindevice_devlist` ADD `repairend` date NOT NULL DEFAULT '0000-00-00';

# this is update sql if you do not have these columns. 2016-10-14
ALTER TABLE `kevindevice_group` CHANGE `type` `type` enum('station','laptop','server','other','discard','pc') NOT NULL DEFAULT 'station';

# Structure for table "kevindevice_maintainlist". 2017-7-25
CREATE TABLE IF NOT EXISTS `kevindevice_maintainlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` date NOT NULL DEFAULT '0000-00-00',
  `platform` int(11) NOT NULL DEFAULT '0',
  `log` int(11) NOT NULL DEFAULT '0',
  `sendout` int(11) NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

# Structure for table "kevindevice_spotchklist". 2017-7-25
CREATE TABLE IF NOT EXISTS `kevindevice_spotchklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

# Structure for table "kevindevice_sendoutlist". 2017-7-26
CREATE TABLE IF NOT EXISTS `kevindevice_sendoutlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sendout` int(11) unsigned NOT NULL DEFAULT '0',
  `total` int(11) unsigned NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ---------kevinhours-------------
# db alter for kevinhours plugin
ALTER TABLE `zt_kevinclockact` ADD `desc` text NOT NULL;
ALTER TABLE `zt_kevinclockact` MODIFY `action` enum('in','out') NOT NULL DEFAULT 'in';

ALTER TABLE `zt_hourscashcode` ADD `dept` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_hourscashcode` ADD `deptdispatch` mediumint(8) unsigned NOT NULL DEFAULT '0';
update zt_kevinclockact a set a.action = 'out' where a.action != 'in'

-- ---------kevinplan-------------
# for  2016-12-27
ALTER TABLE `kv_plan_member` CHANGE `status` `status` enum('draft','planned','planned','developing','pause','cancel','closed') NOT NULL DEFAULT 'draft';
ALTER TABLE `kv_plan_list` CHANGE `status` `status` enum('draft','planned','planned','developing','pause','cancel','closed') NOT NULL DEFAULT 'draft';
ALTER TABLE `kv_plan_project` CHANGE `status` `status` enum('draft','planned','planned','developing','pause','cancel','closed') NOT NULL DEFAULT 'draft';
-- 2017.1.5 
ALTER TABLE `kv_plan_list` ADD `members` text NOT NULL;

# update for default value
update kv_plan_member set status = 'draft' where status = 'wait' or status = '';
update kv_plan_list set status = 'draft' where status = 'wait' or status = '';
update kv_plan_project set status = 'draft' where status = 'wait' or status = '';

-- 2017-1-17 delete kv_plan_user
DROP TABLE IF EXISTS `kv_plan_user`;
-- add columns
ALTER TABLE `kv_plan_list` ADD `members` text NOT NULL;

ALTER TABLE `kv_plan_project` CHANGE `charger` `charger` char(30) NOT NULL DEFAULT '' COMMENT 'First Charger';
ALTER TABLE `kv_plan_project` ADD `charger2` char(30) NOT NULL DEFAULT '' COMMENT 'person in charge2';  
ALTER TABLE `kv_plan_project` ADD `classPro` int(11) NOT NULL DEFAULT '0' COMMENT 'project class';
ALTER TABLE `kv_plan_project` ADD `dateBuild` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date build';
ALTER TABLE `kv_plan_project` ADD `dateDR2` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date DR2';
ALTER TABLE `kv_plan_project` ADD `dateDR3` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date DR3';
ALTER TABLE `kv_plan_project` ADD `dateDR4` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date DR4';
ALTER TABLE `kv_plan_project` ADD `dateRelease` date NOT NULL DEFAULT '0000-00-00' COMMENT 'date Release';

-- default date
update kv_plan_project set dateBuild = startDate where dateBuild = '0000-00-00';
update kv_plan_project set dateDR2 = endDate where dateDR2 = '0000-00-00';
update kv_plan_project set dateDR3 = endDate where dateDR3 = '0000-00-00';
update kv_plan_project set dateDR4 = endDate where dateDR4 = '0000-00-00';
update kv_plan_project set dateRelease = endDate where dateRelease = '0000-00-00';

-- 2017-1-18
rename table kv_plan_item to kv_plan_member;
ALTER TABLE `kv_plan_member` CHANGE `projectPlanID` `project` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'project Plan ID';
ALTER TABLE `kv_plan_projectgroup` CHANGE `projectPlanID` `project` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'project Plan ID';

ALTER TABLE `kv_plan_list` CHANGE `normalHours` `hoursPlan` int(11) NOT NULL DEFAULT '1000' COMMENT 'plan hours';
ALTER TABLE `kv_plan_project` CHANGE `hoursPlan` `hoursPlan` int(11) NOT NULL DEFAULT '500' COMMENT 'plan hours';

-- 2017-1-25
ALTER TABLE `kv_plan_project` ADD  `pri` enum('0','1','2','3','4','d1','d2','d3','d4','k1','k2','k3','k4','a1','a2','a3','a4') NOT NULL DEFAULT '0'  COMMENT 'Project Pri';

-- 2017-4-6
ALTER TABLE `kv_plan_project` CHANGE `status` `status` enum('draft','planned','planned','developing','pause','cancel','closed') NOT NULL DEFAULT 'draft';

-- 2017-8-30
ALTER TABLE `kv_plan_project` ADD  `ProNew` enum('0','1') NOT NULL DEFAULT '0';

# For kevin login 1.6.1 2017-1-05

#kevinlogin

# From Ver 1.6 to 1.6.1 .  please visit zentao/kevinlogin-defaultpwd.html to update the database auto.

# ALTER TABLE `zt_defaultpassword` ADD `source` char(32) NOT NULL DEFAULT '' COMMENT 'source for password'
#

#update for kevindevice_sendoutlist 2017-12-6

ALTER TABLE `kevindevice_sendoutlist` ADD `time` date NOT NULL DEFAULT '0000-00-00';

# 2017-12-06
ALTER TABLE `zt_dept`
ADD COLUMN `email`  char(100) NOT NULL DEFAULT '' AFTER `deleted`;
ADD COLUMN `code`  char(50) NOT NULL DEFAULT '' AFTER `email`;
ADD COLUMN `group`  char(255) NOT NULL DEFAULT '' AFTER `code`;

# 2017-12-14
ALTER TABLE `kv_user_class`
ADD COLUMN `worktype`  enum('nor','out') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'nor' AFTER `classify4`;

ALTER TABLE `kv_user_class`
DROP INDEX `classname` ,
ADD UNIQUE INDEX `classname` (`role`, `classify1`, `classify2`, `classify3`, `classify4`, `worktype`) USING BTREE ;

ALTER TABLE `kv_user_record`
DROP COLUMN `worktype`;

# 2017-12-18
ALTER TABLE `kv_user_record`
MODIFY COLUMN `deleted`  enum('0','1') NULL DEFAULT '0' AFTER `end`,
MODIFY COLUMN `locked`  enum('draft','lock') NULL DEFAULT 'draft' AFTER `deleted`;

ALTER TABLE `kv_user_class`
MODIFY COLUMN `role`  varchar(50) DEFAULT ''  AFTER `id`;
MODIFY COLUMN `classify1`  varchar(50) DEFAULT ''  AFTER `role`;
MODIFY COLUMN `classify2`  varchar(50) DEFAULT ''  AFTER `classify1`;
MODIFY COLUMN `classify3`  varchar(50) DEFAULT ''  AFTER `classify2`;
MODIFY COLUMN `classify4`  varchar(50) DEFAULT ''  AFTER `classify3`;
MODIFY COLUMN `classname`  varchar(100) DEFAULT ''  AFTER `classify4`;
MODIFY COLUMN `deleted`  enum('0','1') NULL DEFAULT '0' AFTER `remarks`,
MODIFY COLUMN `locked`  enum('draft','lock') NULL DEFAULT 'draft' AFTER `deleted`;

# 2017-12-19
ALTER TABLE `kv_user_class`
MODIFY COLUMN `role`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `id`,
MODIFY COLUMN `classify1`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `role`,
MODIFY COLUMN `classify2`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `classify1`,
MODIFY COLUMN `classify3`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `classify2`,
MODIFY COLUMN `classify4`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `classify3`,
MODIFY COLUMN `classname`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `classify4`,
MODIFY COLUMN `payrate`  decimal(4,2) NOT NULL DEFAULT 0.00 AFTER `classname`,
MODIFY COLUMN `hourFee`  decimal(6,2) NOT NULL DEFAULT 0.00 AFTER `payrate`,
MODIFY COLUMN `conversionFee`  decimal(8,2) NOT NULL DEFAULT 0.00 AFTER `hourFee`,
MODIFY COLUMN `start`  date NOT NULL DEFAULT '0000-00-00' AFTER `conversionFee`,
MODIFY COLUMN `end`  date NOT NULL DEFAULT '0000-00-00' AFTER `start`,
MODIFY COLUMN `jobRequirements`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `end`,
MODIFY COLUMN `monthFee`  decimal(8,2) NOT NULL DEFAULT 0.00 AFTER `jobRequirements`,
MODIFY COLUMN `remarks`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `monthFee`,
MODIFY COLUMN `deleted`  enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' AFTER `remarks`,
MODIFY COLUMN `locked`  enum('draft','lock') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'draft' AFTER `deleted`;

ALTER TABLE `kv_user_record`
MODIFY COLUMN `account`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `id`,
MODIFY COLUMN `class`  mediumint(9) NOT NULL DEFAULT 0 AFTER `account`,
MODIFY COLUMN `start`  date NOT NULL DEFAULT '0000-00-00' AFTER `class`,
MODIFY COLUMN `end`  date NOT NULL DEFAULT '0000-00-00' AFTER `start`,
MODIFY COLUMN `deleted`  enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' AFTER `end`,
MODIFY COLUMN `locked`  enum('draft','lock') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'draft' AFTER `deleted`;