<?php
/**
 * The view file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinplan
 * @link        http://www.zentao.net
 */
?>
<?php include '../../kevinhours/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php
//头部的处理函数
//project default set
if (!$projectItem) die(json_encode("project Item is not set!"));

$memberItem				 = new stdclass();
$memberItem->startDate	 = $projectItem->startDate;
$memberItem->endDate	 = $projectItem->endDate;
$memberItem->dept		 = $projectItem->dept;
$memberItem->notes		 = '';
$memberItem->member		 = $this->app->user->account;
//计算默认
$hoursTotalItem			 = $this->dao->select("sum( hours) as hoursPlan from kv_plan_member"
		. " where project = $projectItem->id and deleted = '0'")->fetch();

if ($hoursTotalItem) $memberItem->hours	 = $projectItem->hoursPlan - $hoursTotalItem->hoursPlan;
else $memberItem->hours	 = $projectItem->hoursPlan;
if ($memberItem->hours <= 0) $memberItem->hours	 = 200;
?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='plan id'><?php echo $lang->kevinplan->project . " > " . $lang->kevinplan->membercreate . " > ";?> <strong><?php echo $project;?></strong></span>
	</div>
</div>
<div class='main'>

	<form class='form' method='post' enctype='multipart/form-data'  target='hiddenwin' id='dataform'>
		<table class='table table-form table-borderless' width = '100%' cellpadding="5"> 
            <tr>
                <th class='w-name nobr'><?php echo $lang->kevinplan->deptCharge?></th>
                <td class='w-auto'><?php echo html::select('dept', $deptlist, $memberItem->dept, "class='form-control chosen'")?></td>
				<th class='w-name nobr'><?php echo $lang->kevinplan->member;?></th>
				<td class='w-auto'><?php echo html::select('member', $userlist, $memberItem->member, "class='form-control chosen'");?></td>
			</tr>	
			<tr>
				<th class='nobr'><?php echo $lang->kevinplan->startDate;?></th>
				<td><?php echo html::input('startDate', $memberItem->startDate, "class='form-control form-date' ");?></td>
				<th class='nobr'><?php echo $lang->kevinplan->endDate;?></th>
				<td><?php echo html::input('endDate', $memberItem->endDate, "class='form-control form-date' ");?></td>
            </tr>
			<tr>
				<th class='nobr'><?php echo $lang->kevinplan->hours;?></th>
				<td><?php echo html::input('hours', $memberItem->hours, "class='form-control' placeholder='work hours (unit hour).Like: 160. '");?></td>
			</tr> 
            <tr>	
				<th class='nobr'><?php echo $lang->kevinplan->workcontent;?></th>
                <td colspan='3'><?php echo html::input('notes', $memberItem->notes, "class='form-control autosize'  placeholder='work content (unit hour).Like: Light design.' ");?></td>
            </tr>
			<tr>
				<td colspan='4'  class="text-center"><?php echo html::submitButton($lang->save);?></td>
            </tr>
		</table>
	</form>

	<fieldset >
		<legend ><?php echo $lang->kevinplan->project . $lang->kevinplan->basicInfo;?></legend>
		<table class='table table-data  table-condensed table-borderless'>			
			<tr>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->projectCode;?></strong></th>
				<td><?php
					if ($projectItem->projectCode == 0) echo '';
					echo $projectItem->projectCode;
					?></td>
				<th class='w-10px'><strong><?php echo '统计：'?></strong></th>
				<td><?php
					if ($projectItem->projectCode != 0) echo html::a($this->createLink('kevinhours', 'project', "id={$projectItem->projectCode}&type=thisYear", '', true)
							, '<i class="icon icon-bar-chart"></i> ' . $lang->kevinplan->hours, '', "data-toggle='modal' data-type='iframe' data-icon='check'");
					?></td>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->project;?></strong></th>
				<td><?php echo $projectItem->name;?></td>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->startDate;?></strong></th>
				<td><?php echo $projectItem->startDate;?></td>
			</tr>	
			<tr>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->hours;?></strong></th>
				<td><?php echo $projectItem->hoursPlan;?></td>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->dept;?></strong></th>
				<td><?php echo isset($deptlist[$projectItem->dept]) ? $deptlist[$projectItem->dept] : '';?></td>

				<th class='w-10px'><strong><?php echo $lang->kevinplan->charger;?></strong></th>
				<td><?php echo isset($userlist[$projectItem->charger]) ? $userlist[$projectItem->charger] : '';?></td>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->endDate;?></strong></th>
				<td><?php echo $projectItem->endDate;?></td>
			</tr>
		</table>
	</fieldset>
</div>

