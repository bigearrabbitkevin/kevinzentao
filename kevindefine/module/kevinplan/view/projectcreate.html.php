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
<?php include '../../common/view/kindeditor.html.php';?>
<?php
//project default set
if (!$planItem) die(json_encode("plan is not set!"));

$projectItem				 = new stdclass();
$projectItem->startDate		 = $planItem->startDate;
$projectItem->endDate		 = $planItem->endDate;
$projectItem->dateBuild		 = $projectItem->startDate;
$projectItem->dateDR2		 = $projectItem->endDate;
$projectItem->dateDR3		 = $projectItem->endDate;
$projectItem->dateDR4		 = $projectItem->endDate;
$projectItem->dateRelease	 = $projectItem->endDate;

//计算默认
$hoursTotalPro = $this->dao->select("sum( hoursPlan) as hoursPlan from kv_plan_project a "
		. "left join kv_plan_projectgroup b on a.id = b.project  where b.plan = $planItem->id and a.deleted = '0' and b.deleted = '0'")->fetch();

if ($hoursTotalPro) $projectItem->hoursPlan	 = $planItem->hoursPlan - $hoursTotalPro->hoursPlan;
else $projectItem->hoursPlan	 = $planItem->hoursPlan;
if ($projectItem->hoursPlan <= 0) $projectItem->hoursPlan	 = 200;
?>

<div id='titlebar'>
	<div class='heading'>
		<strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->kevinplan->projectcreate;?></strong>
	</div>
</div>
<div class="container">
	<form class='form' method='post' target='hiddenwin' id='dataform' >
		<table width = '100%' class="Date-table"'>
			<thead>
				<tr>
					<td class='text-center'><?php echo $lang->kevinplan->dateBuild?></td>
					<td class='text-center'><?php echo $lang->kevinplan->dateDR2?></td>
					<td class='text-center'><?php echo $lang->kevinplan->dateDR3?></td>
					<td class='text-center'><?php echo $lang->kevinplan->dateDR4?></td>
					<td class='text-center'><?php echo $lang->kevinplan->dateRelease?></td>
				</tr>
			</thead>
			<tr>
				<td><?php echo html::input('dateBuild', $projectItem->dateBuild, "class='form-control form-date'")?></td>
				<td><?php echo html::input('dateDR2', $projectItem->dateDR2, "class='form-control form-date'")?></td>
				<td><?php echo html::input('dateDR3', $projectItem->dateDR3, "class='form-control form-date'")?></td>
				<td><?php echo html::input('dateDR4', $projectItem->dateDR4, "class='form-control form-date'")?></td>
				<td><?php echo html::input('dateRelease', $projectItem->dateRelease, "class='form-control form-date'")?></td>
			</tr>
		</table>		
		<div class='row-table'>
			<table class='table table-form' width = '100%' cellpadding="5">	
				<tr>
					<th><?php echo $lang->kevinplan->projectCode;?></th>
                    <td ><?php echo html::input('projectCode', '', "class='form-control' placeholder='project id.Like 5229 Or Empty'");?></td>

					<th><?php echo $lang->kevinplan->planYear;?></th>
                    <td><?php echo html::input('planYear', $planItem->planYear, "class='form-control' placeholder='Input Year.Like " . date('Y') . "'");?></td>
				</tr>
                <tr>
					<th><?php echo $lang->kevinplan->planName;?></th>
                    <td id='projectname'><?php echo html::input('name', '', "class='form-control' placeholder='Input name .Like My Project Name'");?></td>
					<th><?php echo $lang->kevinplan->dept;?></th>
                    <td><?php echo html::select('dept', $deptlist, $this->app->user->dept, "class='form-control chosen'");?>	</td>
                </tr>
                <tr>
					<th><?php echo $lang->kevinplan->charger;?>1</th>
                    <td><?php echo html::select('charger', $userlist, $this->app->user->account, "class='form-control chosen'");?></td>
					<th><?php echo $lang->kevinplan->charger;?>2</th>
                    <td><?php echo html::select('charger2', $userlist, "", "class='form-control chosen'");?></td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinplan->hoursPlan;?></th>
                    <td><?php echo html::input('hoursPlan', $projectItem->hoursPlan, "class='form-control' placeholder='Input hoursPlan.Like 320'");?></td>
					<th><?php echo $lang->kevinplan->pri;?></th>
                    <td><?php echo html::select('pri', $this->lang->kevinplan->projectPriList, "k1", "class='form-control chosen'");?></td>
				</tr
				<tr>
					<th><?php echo $lang->kevinplan->startDate;?></th>
					<td><?php echo html::input('startDate', $projectItem->startDate, "class='form-control form-date' ");?></td>

					<th><?php echo $lang->kevinplan->endDate;?></th>
					<td ><?php echo html::input('endDate', $projectItem->endDate, "class='form-control form-date' ");?></td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinplan->workcontent;?></th>
					<td colspan='3' ><?php echo html::input('notes', '', " class='form-control ' ");?></td>
				</tr>
				<tr>
					<td colspan='2'><a href="javascript:getprojectname();" class="btn btn-back ">同步项目名</a></td>								
					<td colspan='2' class="text-left"><?php echo html::submitButton($lang->save);?></td>
				</tr>
			</table>
		</div>
		<fieldset >
			<legend ><?php echo $lang->kevinplan->plan . $lang->kevinplan->basicInfo;?></legend>
			<table class='table table-data  table-condensed table-borderless'>			
				<tr>
					<th class='w-10px'><strong><?php echo $lang->kevinplan->id;?></strong></th>
					<td><?php echo $planItem->id;?></td>
					<th class='w-10px'><strong><?php echo $lang->kevinplan->plan;?></strong></th>
					<td><?php echo $planItem->name;?></td>
					<th class='w-10px'><strong><?php echo $lang->kevinplan->Year;?></th>
					<td><?php echo $planItem->planYear;?></td>
					<th class='w-10px'><strong><?php echo $lang->kevinplan->startDate;?></strong></th>
					<td><?php echo $planItem->startDate;?></td>
				</tr>	
				<tr>
					<th class='w-10px'><strong><?php echo $lang->kevinplan->plan . $lang->kevinplan->hours;?></strong></th>
					<td><?php echo $planItem->hoursPlan;?></td>
					<th class='w-10px'><strong><?php echo $lang->kevinplan->dept;?></strong></th>
					<td><?php echo isset($deptlist[$planItem->dept]) ? $deptlist[$planItem->dept] : '';?></td>
					<th class='w-10px'><strong><?php echo $lang->kevinplan->charger;?></strong></th>
					<td><?php echo isset($userlist[$planItem->charger]) ? $userlist[$planItem->charger] : '';?></td>
					<th class='w-10px'><strong><?php echo $lang->kevinplan->endDate;?></strong></th>
					<td><?php echo $planItem->endDate;?></td>
				</tr>
			</table>
		</fieldset>	
	</form>
</div>
<script>
	function getprojectname() {
		var projectcode = $('#projectCode').val();
		var rawval = $('#name').val();
		link = createLink('kevinplan', 'ajaxgetprojectname', 'projectcode=' + projectcode + '&rawval=' + rawval);
		link = encodeURI(link);
		$('#projectname #projectCode').remove();
		$('#projectname').load(link, function () {
			$('#projectname').html();
		});
	}
</script>