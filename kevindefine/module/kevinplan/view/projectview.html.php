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
		<span class='prefix' title='SOFT'><?php echo html::icon($lang->icons['app']);?> <strong><?php echo $projectItem->id;?></strong></span>
		<strong><?php echo $projectItem->name;?></strong>
		<?php if ($projectItem->deleted):?>
			<span class='label label-danger'><?php echo "已删除";?></span>
		<?php endif;?>
	</div>
	<div class='actions'>
	</div>
</div>
<div class='row-table'>
	<div class='col-side'>
		<div class='main main-side'>
			<fieldset>
				<legend><?php echo $lang->kevinplan->basicInfo;?></legend>
				<table class='table table-data table-condensed table-borderless'>
					<tr>
						<th class='w-100px'><strong><?php echo $lang->kevinplan->projectCode;?></strong></th>
						<td><?php echo $projectItem->projectCode;?></td>

						<th class='w-100px'><strong><?php echo $lang->kevinplan->projectName;?></strong></th>
						<td><?php echo $projectItem->name;?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->dept;?></strong></th>
						<td><?php echo $deptlist[$projectItem->dept];?></td>
						<th><strong><?php echo $lang->kevinplan->projectPri;?></strong></th>
						<td><?php echo $lang->kevinplan->projectPriList[$projectItem->pri];?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->charger;?></strong></th>
						<td><?php echo $userlist[$projectItem->charger];?></td>
						<th><strong><?php echo $lang->kevinplan->charger . '2'?></strong></th>
						<td><?php echo $userlist[$projectItem->charger2];?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->projectCode;?></strong></th>
						<td><?php echo $projectItem->projectCode;?></td>

						<th><strong><?php echo $lang->kevinplan->projectName;?></strong></th>
						<td><?php echo $projectItem->name;?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->hoursPlan;?></strong></th>
						<td><?php echo $projectItem->hoursPlan;?></td>

						<th><strong><?php echo $lang->kevinplan->hoursCost;?></strong></th>
						<td><?php echo $projectItem->hoursCost;?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->startDate;?></strong></th>
						<td><?php echo $projectItem->startDate;?></td>

						<th><strong><?php echo $lang->kevinplan->endDate;?></strong></th>
						<td><?php echo $projectItem->endDate;?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->planYear;?></strong></th>
						<td><?php echo $projectItem->planYear;?></td>

						<th><strong><?php echo $lang->kevinplan->manYear;?></strong></th>
						<td><?php echo sprintf("%.1f", $projectItem->hoursPlan / 2000 * 100) . '%';?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->status;?></strong></th>
						<td><?php echo $lang->kevinplan->statusList[$projectItem->status];?></td>

						<th><strong><?php echo $lang->kevinplan->IsFinished;?></strong></th>
						<td><?php echo $lang->kevinplan->IsFinishedList[$projectItem->IsFinished];?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->lock;?></strong></th>
						<td><?php echo $lang->kevinplan->lockList[$projectItem->lock];?></td>

						<th><strong><?php echo $lang->kevinplan->deleted;?></strong></th>
						<td><?php echo $lang->kevinplan->deletedList[$projectItem->deleted];?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->addedBy;?></strong></th>
						<td><?php echo $userlist[$projectItem->addedBy];?></td>

						<th><strong><?php echo $lang->kevinplan->addedDate;?></strong></th>
						<td><?php echo $projectItem->addedDate;?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->lastEditedBy;?></strong></th>
						<td><?php echo $userlist[$projectItem->lastEditedBy];?></td>

						<th><strong><?php echo $lang->kevinplan->lastEditedDate;?></strong></th>
						<td><?php echo $projectItem->lastEditedDate;?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->classPro?></strong></th>
						<td><?php echo $lang->kevinplan->classProList[$projectItem->classPro];?></td>
						<th><strong><?php echo $lang->kevinplan->ProNew?></strong></th>
						<td><?php echo $lang->kevinplan->ProNewList[$projectItem->ProNew];?></td>
					</tr>

					<tr>
						<th><strong><?php echo $lang->kevinplan->workcontent;?></strong></th>
						<td colspan='3' ><?php echo $projectItem->notes;?></td>
					</tr>
				</table>
				<hr/>
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
                        <td class='text-center'><?php echo $projectItem->dateBuild?></td>
                        <td class='text-center'><?php echo $projectItem->dateDR2?></td>
                        <td class='text-center'><?php echo $projectItem->dateDR3?></td>
                        <td class='text-center'><?php echo $projectItem->dateDR4?></td>
                        <td class='text-center'><?php echo $projectItem->dateRelease?></td>
                    </tr>
				</table>
			</fieldset>
			<div class='actions left'>
				<?php
				if (!$projectItem->deleted) {
					echo html::a($this->createLink('kevinplan', 'membercreate', "project={$projectItem->id}", '', true), "<i class='icon-plus'></i>" . $lang->kevinplan->membercreate, '', "class='btn' data-toggle='modal' data-type='iframe'");
					echo html::a($this->createLink('kevinplan', 'projectedit', "id={$projectItem->id}", '', true), "<i class='icon-pencil'></i>" . $lang->kevinplan->projectedit, '', "class='btn' data-toggle='modal' data-type='iframe'");
				}
				echo html::a($this->createLink('kevinplan', 'projectdelete', "project={$projectItem->id}", '', true), "<i class='icon-remove'></i>" . $lang->kevinplan->projectdelete, '', "class='btn' data-toggle='modal' data-type='iframe'");
				echo html::a($this->createLink('kevinplan', 'updateprohours', "project={$projectItem->id}", '', true), "<i class='icon-refresh'></i>" . $lang->kevinplan->updateprohours, '', "class='btn' data-toggle='modal' data-type='iframe'");
				?>
			</div>
		</div>
		<fieldset>
			<legend><?php echo $lang->kevinplan->belongto . $lang->kevinplan->plan;?></legend>
			<div><ul class="nav">
					<?php
					foreach ($planarrs as $fromplanid => $fromplan) {
						if ($fromplanid) echo '<li class = "pull-left">' . html::a($this->createLink('kevinplan', 'planview', "id=$fromplanid", '', true)
								, $fromplan, '', "data-toggle='modal' data-type='iframe'  data-icon='check'") . '</li>';
					}
					?>
				</ul></div>
		</fieldset>
		<?php include '../../common/view/action.html.php';?>
	</div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../kevincom/view/footer.html.php';?>