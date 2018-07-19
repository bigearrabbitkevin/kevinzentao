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
<?php?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='id'><?php echo $lang->kevinplan->project . " > " . $lang->kevinplan->memberedit . " > ";?> <strong><?php echo $memberItem->id;?></strong></span>
		<?php if ($memberItem->deleted):?>
			<span class='label label-danger'><?php echo $lang->kevinplan->deleted;?></span>
		<?php endif;?>
	</div>
</div>

<div class='row-table'>
	<form class='form' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
		<table class='table table-form table-borderless' width = '100%' cellpadding="5"> 
			<tr>
				<th class='w-name nobr'><?php echo $lang->kevinplan->member;?></th>
				<td class='w-auto'><?php echo html::select('member', $userlist, $memberItem->member, "class='form-control chosen'");?></td>	
				<th class='w-name nobr'><?php echo $lang->kevinplan->dept;?></th>
				<td class='w-auto'><?php echo html::select('dept', $deptlist, $memberItem->dept, "class='form-control chosen'");?></td>
			</tr> 

			<tr>
				<th  class='w-80px'><?php echo $lang->kevinplan->hours;?></th>
				<td><?php
					echo html::input('hours', $memberItem->hours, "class='form-control'");
					;
					?></td>
				<th><?php echo $lang->kevinplan->hoursCost;?></th>
				<td><?php echo html::input('hoursCost', $memberItem->hoursCost, "class='form-control'");?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinplan->startDate;?></th>
				<td><?php echo html::input('startDate', $memberItem->startDate, "class='form-control form-date' ");?></td>
				<th><?php echo $lang->kevinplan->endDate;?></th>
				<td><?php echo html::input('endDate', $memberItem->endDate, "class='form-control form-date' ");?></td>
			</tr> 
			<tr>
				<th><?php echo $lang->kevinplan->workcontent;?></th>
				<td colspan='3'><?php echo html::input('notes', $memberItem->notes, "class='form-control autosize' ");?></td>	
			</tr>
			<tr>
				<th><?php echo $lang->kevinplan->IsFinished;?></th>
                <td><?php echo html::radio('IsFinished', $lang->kevinplan->yesOrNo, $memberItem->IsFinished, "");?></td>
				<th><?php echo $lang->kevinplan->status;?></th>
				<td><?php echo html::select('status', $lang->kevinplan->statusList, $memberItem->status, "class='form-control'");?></td>
            </tr>
			<tr>
                <td colspan='4' class="text-center"><?php echo html::submitButton($lang->save);?></td>
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
				<th class='w-10px'><strong><?php echo $lang->kevinplan->projectName;?></strong></th>
				<td><?php echo $projectItem->name;?></td>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->startDate;?></strong></th>
				<td><?php echo $projectItem->startDate;?></td>
			</tr>	
			<tr>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->hours;?></strong></th>
				<td><?php echo $projectItem->hoursPlan;?></td>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->deptCharge;?></strong></th>
				<td><?php echo isset($deptlist[$projectItem->dept]) ? $deptlist[$projectItem->dept] : '';?></td>

				<th class='w-10px'><strong><?php echo $lang->kevinplan->charger;?></strong></th>
				<td><?php echo isset($userlist[$projectItem->charger]) ? $userlist[$projectItem->charger] : '';?></td>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->endDate;?></strong></th>
				<td><?php echo $projectItem->endDate;?></td>
			</tr>
		</table>
	</fieldset>

</div>