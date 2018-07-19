<?php
$config->kevinsvn              = new stdclass();
$config->kevinsvn->AuthzType = 1; // 0: local authz. 1: windows authz
define('DEFAULT_PATH','D:/xampp/sliksvn/createrep/');
define('GLOBAL_CONF_PATH','VisualSVN-GlobalWinAuthz.ini');
define('CONF_PATH','/conf/VisualSVN-WinAuthz.ini');
define('PARSE_PATH','/db/revs/0/');
$config->kevinsvn->CMD_PATH = 'C:/Program Files (x86)/VisualSVN Server/bin/';
$config->kevinsvn->REP_PATH = 'D:/Repositories/';
$config->kevinsvn->ServerLinkPrifix = 'https://127.0.0.1/svn/';

$config->kevinsvn->accreateno=6;
$config->kevinsvn->createrepath['0']='0';//name of copy to create empty rep
$config->kevinsvn->createrepath['1']='1';//name of copy to create single pro rep

$config->kevinsvn->dirnamechk=array('\\','/',':','*','?','"','<','>','|');
/* Include the custom config file. my.php*/
$configRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$myConfig   = $configRoot . 'my.php';
if(file_exists($myConfig)) include $myConfig;