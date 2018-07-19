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

<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='SOFT'><?php echo html::icon($lang->icons['app']);?> <strong><?php echo $projectItem->id;?></strong></span>
		<strong><?php echo $projectItem->name;?></strong> - 
		<strong><small class='text-muted'><?php echo html::icon($lang->icons['edit']);?></small> <?php echo $lang->kevinplan->projectedit;?></strong>
	</div>
	<div class="actions">
		<span style="margin-left:50px" id="skdepttransfer_refresh" class="btn" onclick="getprojectname();">同步项目名</span>
	</div>
</div>
<div class="container">
	<form class='form' method='post' target='hiddenwin' id='dataform'>
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
		<br>
		<div class='row-table'>
			<table width = '100%' cellpadding="7">
				<tr>
					<th class='w-120px nobr'><?php echo $lang->kevinplan->projectCode;?></th>
					<td class='w-p40'><?php echo html::input('projectCode', $projectItem->projectCode, "class='form-control' placeholder='project id.Like 5229 Or Empty'");?></td>
					<th class='w-120px nobr'><?php echo $lang->kevinplan->planYear;?></th>
					<td class='w-p40'><?php echo html::input('planYear', $projectItem->planYear, "class='form-control '");?></td>
				</tr>
				<tr>
					<th class="nobr"><?php echo $lang->kevinplan->planName;?></th>
					<td id='projectname'><?php echo html::input('name', $projectItem->name, "class='form-control'");?></td>
					<th class="nobr"><?php echo $lang->kevinplan->classPro?></th>
					<td><?php echo html::select('classPro', $lang->kevinplan->classProList, $projectItem->classPro, "class='form-control'")?></td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinplan->dept?></th>
					<td><?php echo html::select('dept', $deptlist, $projectItem->dept, " class='form-control chosen' ")?></td>
					<th class='w-80px'><strong><?php echo $lang->kevinplan->projectPri;?></strong></th>
					<td><?php echo html::select('pri', $lang->kevinplan->projectPriList, $projectItem->pri, " class='form-control chosen' ")?></td>
				</tr>
				<tr>
					<th class="nobr"><?php echo $lang->kevinplan->charger;?></th>
					<td><?php echo html::select('charger', $userlist, $projectItem->charger, "class='form-control chosen'");?></td>

					<th class="nobr"><?php echo $lang->kevinplan->charger . '2';?></th>
					<td><?php echo html::select('charger2', $userlist, $projectItem->charger2, "class='form-control chosen '");?></td>
				</tr>
				<tr>
					<th class="nobr"><?php echo $lang->kevinplan->hoursPlan;?></th>
					<td><?php echo $projectItem->hoursPlan;?></td>

					<th class="nobr"><?php echo $lang->kevinplan->hoursCost;?></th>
					<td><?php echo html::input('hoursCost', $projectItem->hoursCost, "class='form-control'");?></td>
				</tr>
				<tr>
					<th class="nobr"><?php echo $lang->kevinplan->startDate;?></th>
					<td><?php echo html::input('startDate', $projectItem->startDate, "class='form-control form-date'");?></td>

					<th class="nobr"><?php echo $lang->kevinplan->endDate;?></th>
					<td><?php echo html::input('endDate', $projectItem->endDate, "class='form-control form-date'");?></td>
				</tr>
				<tr>
					<th class="nobr"><?php echo $lang->kevinplan->status;?></th>
					<td><?php echo html::select('status', $lang->kevinplan->statusList, $projectItem->status, "class='form-control'");?></td>
					<th class="nobr"><?php echo $lang->kevinplan->ProNew;?></th>
					<td><?php echo html::select('ProNew', $lang->kevinplan->ProNewList, $projectItem->ProNew, "class='form-control'");?></td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinplan->workcontent;?></th>
					<td colspan='3' ><?php echo html::input('notes', $projectItem->notes, " class='form-control autosize' ");?></td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinplan->belongto . $lang->kevinplan->plan;?></th>
					<td colspan="3"><?php echo html::select('plans[]', $planlist, $planarrs, "multiple class='form-control chosen'");?>
						只能添加，不支持删除。这里删除无效。要到计划下删除。<br> (请修改成员工时来更新项目预计工时)</td>
				</tr>
				<tr >
					<td colspan='3' class="text-center"><?php echo html::submitButton($lang->save);?></td>
					<td colspan='1' class="text-center"><span style="margin-left:50px" id="skdepttransfer_refresh" class="btn" onclick="getprojectname();">同步项目名</span></td>
				</tr>
			</table>
		</div>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
<script>
	//$("#projectCode").change( function() {
	//        link = createLink('kevinplan', 'ajaxgetprojectname', 'projectcode=' + this.value);
	//        link=encodeURI(link);
	//	$('#projectname #projectCode').remove();
	//	$('#projectname').load(link, function () {
	//		$('#projectname').html();
	//	});
	//});
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