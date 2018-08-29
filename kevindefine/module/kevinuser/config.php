<?php

$config->kevinuser			 = new stdclass();
$config->kevinuser->create	 = new stdclass();
$config->kevinuser->edit	 = new stdclass();

$config->kevinuser->create->requiredFields	 = 'role,worktype,classify1,classify2,classify3,payrate,hourFee,conversionFee,start,end,monthFee';
$config->kevinuser->edit->requiredFields	 = 'role,worktype,classify1,classify2,classify3,payrate,hourFee,conversionFee,start,end,monthFee';

$config->kevinuser->classBatchEditFields	 = 'role,worktype,classify1,classify2,classify3,classify4,payrate,hourFee,start,end,jobRequirements,remarks';
$config->kevinuser->recordBatchEditFields	 = 'account,realname,class,worktype,start,end';
$config->kevinuser->deptBatchEditFields		 = 'name,path,group,grade,order,manager,email,code';

$config->kevinuser->lockMinutes	 = 10;
$config->kevinuser->batchEditNum	 = 5;
$config->kevinuser->batchCreate	 = 10;
$config->kevinuser->endDate	 = '2030-01-01';

//下面的要在my.php中重新指定ｉｄ相应分类的父亲的id，一般是不同的id
$config->kevinuser->classID['role']	 	 = 1; 
$config->kevinuser->classID['class1']	 = 2; 
$config->kevinuser->classID['class2']	 = 3; 
$config->kevinuser->classID['class3']	 = 4; 
$config->kevinuser->classID['worktype']	 = 5; 

/* Include the custom config file. my.php*/
$configRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$myConfig   = $configRoot . 'my.php';
if(file_exists($myConfig)) include $myConfig;
