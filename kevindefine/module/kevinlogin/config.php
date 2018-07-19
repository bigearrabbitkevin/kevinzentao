<?php
$config->kevinlogin = new stdclass();

$config->kevinlogin->updateLdapusers = new stdclass();
$config->kevinlogin->updateLdapusers->requiredFields = 'domain,remote,local';//录入时必须的项

$config->kevinlogin->domainIP = ""; //set domain ip, if empty ,get from account tom@kevin.com,=>kevin.com
$config->kevinlogin->guestList = array();
// Include the custom config file. my.php
$myConfig   = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'my.php';
if(file_exists($myConfig)) include $myConfig;
