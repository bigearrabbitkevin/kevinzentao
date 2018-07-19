<?php

/**
 * The config file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinplan
 * @link        http://www.zentao.net
 */
?>
<?php

$config->kevinplan = new stdclass();

$config->kevinplan->filecreate					 = new stdclass();
$config->kevinplan->filecreate->requiredFields	 = 'plan,item,title'; //录入时必须的项

$config->kevinplan->fileedit				 = new stdclass();
$config->kevinplan->fileedit->requiredFields = 'title'; //录入时必须的项

$config->kevinplan->membercreate						 = new stdclass();
$config->kevinplan->membercreate->requiredFields		 = 'member,startDate,endDate,notes,hours,dept'; //录入时必须的项
$config->kevinplan->memberedit						 = new stdclass();
$config->kevinplan->memberedit->requiredFields		 = 'member,startDate,endDate,notes,hours,dept'; //录入时必须的项
$config->kevinplan->plancreate						 = new stdclass();
$config->kevinplan->plancreate->requiredFields		 = 'name,charger'; //录入时必须的项
$config->kevinplan->groupedit						 = new stdclass();
$config->kevinplan->groupedit->requiredFields		 = 'name'; //录入时必须的项
$config->kevinplan->projectcreate					 = new stdClass();
$config->kevinplan->projectcreate->requiredFields	 = 'planYear,name,charger,startDate,endDate,hoursPlan,dept';
$config->kevinplan->projectedit						 = new stdClass();
$config->kevinplan->projectedit->requiredFields		 = 'planYear,name,charger,startDate,endDate,dept';
//
$config->kevinplan->planupdate						 = new stdclass();
$this->config->kevinplan->planupdate->requiredFields = 'name,charger';
$config->kevinplan->memberupdate						 = new stdclass();
$this->config->kevinplan->memberupdate->requiredFields = 'group,name,charger';

$config->kevinplan->editor			 = new stdclass();
$config->kevinplan->editor->memberedit = array('id' => 'comment', 'tools' => 'simpleTools');

