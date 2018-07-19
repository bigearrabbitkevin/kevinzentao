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
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='SOFT'><?php echo html::icon($lang->icons['app']);?> <strong><?php echo $planItem->id;?></strong></span>
		<strong><?php echo $planItem->name;?></strong>
		<?php if ($planItem->deleted):?>
			<span class='label label-danger'><?php echo "已删除";?></span>
		<?php endif;?>
	</div>
	<div class='actions'>	</div>
</div>
<div class='main main-side'>
	<fieldset>
		<legend><?php echo $lang->kevinplan->basicInfo;?></legend>
		<table class='table table-data table-condensed table-borderless'>
			<tr>
				<th class='w-80px'><strong><?php echo $lang->kevinplan->id;?></strong></th>
				<td><?php echo $planItem->id;?></td>
				<th><strong><?php echo $lang->kevinplan->planName;?></strong></th>
				<td><?php echo $planItem->name;?></td>
			</tr>
			<tr>
				<th><strong><?php echo $lang->kevinplan->chargerName;?></strong></th>
				<td><?php echo $planItem->chargerName;?></td>
				<th><strong><?php echo $lang->kevinplan->dept;?></strong></th>
				<td><?php echo $deptlist[$planItem->dept];?></td>
			</tr>
			<tr>
				<th><strong><?php echo $lang->kevinplan->planYear;?></strong></th>
				<td><?php echo $planItem->planYear;?></td>
				<th><strong><?php echo $lang->kevinplan->hoursPlan;?></strong></th>
				<td><?php echo $planItem->hoursPlan;?></td>
			</tr>
			<tr>
				<th><strong><?php echo $lang->kevinplan->startDate;?></strong></th>
				<td><?php echo $planItem->startDate;?></td>
				<th><strong><?php echo $lang->kevinplan->endDate;?></strong></th>
				<td><?php echo $planItem->endDate;?></td>	
			</tr>
			<tr>
				<th><strong><?php echo $lang->kevinplan->addedBy;?></strong></th>
				<td><?php echo $userlist[$planItem->addedBy];?></td>
				<th><strong><?php echo $lang->kevinplan->addedDate;?></strong></th>
				<td><?php echo $planItem->addedDate;?></td>	
			</tr>
			<tr>
				<th><strong><?php echo $lang->kevinplan->lastEditedBy;?></strong></th>
				<td><?php echo $userlist[$planItem->lastEditedBy];?></td>
				<th><strong><?php echo $lang->kevinplan->lastEditedDate;?></strong></th>
				<td><?php echo $planItem->lastEditedDate;?></td>	
			</tr>
			<tr>
				<th><strong><?php echo $lang->kevinplan->status;?></strong></th>
				<td><?php echo $lang->kevinplan->statusList[$planItem->status];?></td>
				<th><strong><?php echo $lang->kevinplan->lock;?></strong></th>
				<td><?php echo $lang->kevinplan->lockList[$planItem->lock];?></td>
			</tr>
			<tr>
				<th><strong><?php echo $lang->kevinplan->IsFinished;?></strong></th>
				<td><?php echo $lang->kevinplan->IsFinishedList[$planItem->IsFinished];?></td>
				<th><strong><?php echo $lang->kevinplan->deleted;?></strong></th>
				<td><?php echo $lang->kevinplan->deletedList[$planItem->deleted];?></td>
			</tr>
		</table>
	</fieldset>
	<div class='actions left'>
		<?php
		if (!$planItem->deleted) {
			echo html::a($this->createLink('kevinplan', 'projectcreate', "plan={$planItem->id}", '', true), "<i class='icon-plus'></i>" . $lang->kevinplan->projectcreate, '', "class='btn' data-toggle='modal' data-type='iframe'");
			echo html::a($this->createLink('kevinplan', 'planedit', "id={$planItem->id}", '', false), "<i class='icon-pencil'></i>" . $lang->kevinplan->planedit, '', "class='btn' data-toggle='modal' data-type='iframe'");
			echo html::a($this->createLink('kevinplan', 'plandelete', "id={$planItem->id}", '', true), "<i class='icon-remove'></i>" . $lang->kevinplan->plandelete, '', "class='btn' data-toggle='modal' data-type='iframe'");
			echo html::a($this->createLink('kevinplan', 'updateplanhours', "id=" . $planItem->id, '', true)
				, '<i class="icon icon-refresh"> </i>' . $lang->kevinplan->updateplanhours, ''
				, "class = 'btn btn-default' title='{$lang->kevinplan->updateplanhours}' data-toggle='modal' data-type='iframe' data-icon='check'");
			echo html::a($this->createLink('kevinplan', 'planbatchcreatemember', "id={$planItem->id}", '', true), "<i class='icon-user'></i>" . $lang->kevinplan->planbatchcreatemember, '', "class='btn' data-toggle='modal' data-type='iframe'");
			echo html::a($this->createLink('kevinplan', 'projectexport', "id={$planItem->id}", '', false), "<i class='icon-print'></i>" . $lang->kevinplan->projectexport, '', "class='btn' target = '_blank'");
		}
		?>
		<?php include '../../common/view/action.html.php';?>
	</div>

</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../kevincom/view/footer.html.php';?>