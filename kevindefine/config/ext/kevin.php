<?php

//db define
$prefix = 'kv_soft_';
define('TABLE_KEVIN_SOFT_LIST',          '`' . $prefix . 'list`');
define('TABLE_KEVIN_SOFT_FILE',          '`' . $prefix . 'file`');
define('TABLE_KEVIN_SOFT_VERSION',          '`' . $prefix . 'version`');
define('TABLE_KEVIN_SOFT_GROUPVERSION',          '`' . $prefix . 'groupversion`');
define('TABLE_KEVIN_SOFT_MODULE',          '`' . $prefix . 'module`');

$config->objectTables['kevinsoft']        = TABLE_KEVIN_SOFT_LIST;
$config->objectTables['kevinsoftfile']  = TABLE_KEVIN_SOFT_FILE;
$config->objectTables['kevinsoftversion']    = TABLE_KEVIN_SOFT_VERSION;
$config->objectTables['kevinsoftgroup']    = TABLE_KEVIN_SOFT_GROUPVERSION;
$config->objectTables['kevinsoftmodule']    = TABLE_KEVIN_SOFT_MODULE;

//db define
define('TABLE_KEVINKENDOUI', '`' . $config->db->prefix . 'kevinkendoui`'); 

$config->objectTables['kevinkendoui'] = TABLE_KEVINKENDOUI;


$config->kevindevice_prefix = 'kevindevice_';
//db define
define('TABLE_KEVINDEVICE_DEVLIST', '`' . $config->kevindevice_prefix . 'devlist`');
define('TABLE_KEVINDEVICE_GROUP', '`' . $config->kevindevice_prefix . 'group`');
define('TABLE_KEVINDEVICE_MAINTAINLIST', '`' . $config->kevindevice_prefix . 'maintainlist`');
define('TABLE_KEVINDEVICE_SPOTCHKLIST', '`' . $config->kevindevice_prefix . 'spotchklist`');
define('TABLE_KEVINDEVICE_SENDOUTLIST', '`' . $config->kevindevice_prefix . 'sendoutlist`');

$config->objectTables['kevindevice_devlist']      = TABLE_KEVINDEVICE_DEVLIST;
$config->objectTables['kevindevice_group']     = TABLE_KEVINDEVICE_GROUP;
$config->objectTables['kevindevice_maintainlist'] = TABLE_KEVINDEVICE_MAINTAINLIST;
$config->objectTables['kevindevice_sendoutlist'] = TABLE_KEVINDEVICE_SENDOUTLIST;



$config->kevinchart_prefix = $config->db->prefix . 'kevin_';
//db appdaily
define('TABLE_KEVIN_CHARTEXAMPLE', '`' . $config->kevinchart_prefix . 'chartexample`');
$config->objectTables['kevin_chartexample']       = TABLE_KEVIN_CHARTEXAMPLE;

//db define
define('TABLE_KEVINCALENDAR',          '`' . $config->db->prefix . 'kevincalendar`');

$config->objectTables['kevincalendar']        = TABLE_KEVINCALENDAR;

//kevinstore db define
define('TABLE_KEVINSTROE_ITEM', '`kv_store_item`');
define('TABLE_KEVINSTROE_GROUP', '`kv_store_group`');
define('TABLE_KEVINSTROE_ROW', '`kv_store_row`');

$config->objectTables['kevinstore_item']      = TABLE_KEVINSTROE_ITEM;
$config->objectTables['kevinstore_group']     = TABLE_KEVINSTROE_GROUP;
$config->objectTables['kevinstore_row']      = TABLE_KEVINSTROE_ROW;

//db define
define('TABLE_KEVIN_SVN_REPOSITORIES', '`kv_svn_repositories`');
define('TABLE_KEVIN_SVN_AUTHZ', '`kv_svn_authz`');
define('TABLE_KEVIN_SVN_USER', '`kv_svn_user`');

$config->objectTables['kevinsvn_repositories']	 = TABLE_KEVIN_SVN_REPOSITORIES;
$config->objectTables['kevinsvn_kevinsvnauthz']	 = TABLE_KEVIN_SVN_AUTHZ;
$config->objectTables['kevinsvn_kevinsvnuser']	 = TABLE_KEVIN_SVN_USER;

//kevinstore db define
define('TABLE_KEVINCLASS_ITEM',          '`kv_class_item`');

$config->objectTables['kevinclass_item']        = TABLE_KEVINCLASS_ITEM;

$prefix = 'kv_user_';
define('TABLE_KEVIN_USER_CLASS',          '`' . $prefix . 'class`');
define('TABLE_KEVIN_USER_RECORD',          '`' . $prefix . 'record`');

$config->objectTables['kevinuser_class']      = TABLE_KEVIN_USER_CLASS;
$config->objectTables['kevinuser_record']     = TABLE_KEVIN_USER_RECORD;


//db
define('TABLE_DEFAULTPASSWORD',     '`' . $config->db->prefix  . 'defaultpassword`');
$config->objectTables['defaultpassword']        = TABLE_DEFAULTPASSWORD;
//db
define('TABLE_KEVIN_LDAPUSER',     '`kv_ldapuser`');
$config->objectTables['kv_ldapuser']        = TABLE_KEVIN_LDAPUSER;

//db TABLE_KEVINERRCODE
define('TABLE_KEVINERRCODE',          '`kv_errcode`');
$config->objectTables['kevinerrcode']        = TABLE_KEVINERRCODE;