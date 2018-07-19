<?php

$config->kevindevice              = new stdclass();

$config->kevindevice->nameRequire  = new stdclass();
$config->kevindevice->nameRequire->requiredFields  = 'name';

$config->kevindevice->devedit  = new stdclass();
$config->kevindevice->devedit->requiredFields  = 'name,type,status';
$config->kevindevice->devcreate  = new stdclass();
$config->kevindevice->devcreate->requiredFields  = 'name';
$config->kevindevice->groupcreate  = new stdclass();
$config->kevindevice->groupcreate->requiredFields= 'name,type';
$config->kevindevice->maintainedit  = new stdclass();
$config->kevindevice->maintainedit->requiredFields  = 'time';
$config->kevindevice->maintaincreate  = new stdclass();
$config->kevindevice->maintaincreate->requiredFields  = 'time';
$config->kevindevice->spotchkcreate  = new stdclass();
$config->kevindevice->spotchkcreate->requiredFields	  = 'name';
$config->kevindevice->spotchkedit	 = new stdclass();
$config->kevindevice->spotchkedit->requiredFields	  = 'name';
$config->kevindevice->sendoutcreate  = new stdclass();
$config->kevindevice->sendoutcreate->requiredFields	  = 'time';
$config->kevindevice->sendoutedit	 = new stdclass();
$config->kevindevice->sendoutedit->requiredFields	  = 'time';

$config->kevindevice->times        = new stdclass();
$config->kevindevice->times->begin = 0;
$config->kevindevice->times->end   = 24;
$config->kevindevice->times->delta = 10;

$config->kevindevice->confirmDelete = true;

$config->kevindevice->editor = new stdclass();
//$config->kevindevice->editor->devcreate   = array('id' => 'desc', 'tools' => 'simpleTools');
//$config->kevindevice->editor->devedit     = array('id' => 'desc', 'tools' => 'simpleTools');


//devexport field
$config->kevindevice->titles=array('id','name','group','type','status','user','charge','dept','tcpip','manageip','count','cpuID','deviceSN','version','monitorSN','monitorVersion','assetNumber','vidioCard','discCapacity','memoryCapacity','system','mac','purpose','loginaddr','join','dieDate','repairstart','repairend','desc');