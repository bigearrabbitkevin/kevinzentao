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
 *
 */
?>
<?php include '../../kevinhours/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php
$Filter					 = & $this->kevinplan->Filter;
$chargerListWithName	 = array();
$chargerListWithName[''] = "";
foreach ($chargerList as $item):
	$chargerListWithName[$item] = $userlist[$item] . "(" . $item . ")";
endforeach;
$deptlist[0] = ''; //empty

$Filter->groupcreate = $groupcreate;

$Filter->deptlist				 = $this->kevinplan->getdeptpairs(0, 'Detail');
$Filter->deletedList			 = array_merge(array("" => ""), $lang->kevinplan->deletedList); //第一个必须是空，才可以去除
//设定不需要显示的列
$Filter->ShowColList['member']	 = 0;
$Filter->ShowColList['project']	 = 0;
if ($Filter->type == 'my') $Filter->ShowColList['charger']	 = 0;

if ($groupcreate) {
	$Filter->deleted = 0;
	$Filter->ShowColList['deleted']	 = 0;
	$PlanSource						 = $Filter->plan;
} else $PlanSource = $plan;
//die(json_encode($Filter));

$browseAllPlan = (common::hasPriv('kevinplan', 'browseAllPlan')) || (common::hasPriv('kevinplan', 'browseDeptPlan'));
?>
<div id='featurebar'>
	<ul class="nav">
		<?php
		if (!$groupcreate) {
			foreach ($lang->kevinsvn->types as $key => $label) {
				if ($key == 'all' && !$browseAllPlan) continue;
				echo "<li data-id=$key " . (($key === $type) ? "class='active'" : '') . '>' . html::a(inLink('projectlist', "type=$key"), $label, '', "") . '</li>';
			}
		}
		?>
	</ul>
	<div class='heading'>
        <span class='prefix pull-left'><?php echo $this->lang->kevinplan->belongto . $this->lang->kevinplan->group . ":";?>
			<?php if ($planItem) {?><strong><?php echo $planItem->id;?></strong><?php }?></span>
		<?php if ($planItem) {?><strong class="pull-left">&nbsp<?php echo $planItem->name;?></strong><?php }?>
		<?php if ($planItem && $planItem->deleted):?>
			<span class='label label-danger pull-left'><?php echo $lang->kevinplan->deleted;?></span>
		<?php endif;?>
		<?php if ($groupcreate) echo "<span class='pull-left'>" . '&nbsp>&nbsp' . $this->lang->kevinplan->groupcreate . '</span>';?>

    </div>
	<div class='actions'>
		<?php
		if ($groupcreate) echo html::linkButton($lang->goback, $this->inlink('projectlist', "type=$type&plan=$planItem->id"));
		else if ($plan) {
			echo html::a($this->createLink('kevinplan', 'groupcreate', "plan=$PlanSource", '', false)
				, "<i class='icon-plus'></i>" . $lang->kevinplan->groupcreate, '', "class='btn'");
			echo html::a($this->createLink('kevinplan', 'projectcreate', "plan=$PlanSource", '', true)
				, "<i class='icon-plus'></i>" . $lang->kevinplan->projectcreate, '', "class='btn' data-toggle='modal' data-type='iframe' data-icon='check'");
		}
		?>
	</div>
</div>
<div class='side' >
	<a class='side-handle' data-id='kevinplanTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm' style="height: 500px;">
			<div class="panel panel-sm">
				<div class="panel-heading nobr"><i class="icon-sitemap"></i> <strong><?php echo $lang->kevinplan->planFilter;?></strong></div>
			</div>
			<div>
				<?php include 'projectfilter.html.php';?>
			</div>
		</div>
	</div>
</div>
<div class='main'>
	<?php if ($PlanSource != 0 && !$groupcreate) {?>
		<div>
			<fieldset>
				<legend><?php echo $lang->kevinplan->planInfo;?></legend>
				<table class='table table-data table-condensed table-borderless'>
					<tr>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->plan;?></strong></th>
						<td><?php echo sprintf('%03d', $planItem->id);?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->planName;?></strong></th>
						<td><?php echo $planItem->name;?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->hoursPlan;?></strong></th>
						<td><?php echo $planItem->hoursPlan . ' h /' . sprintf("%.2f", $planItem->hoursPlan / 2000) . ' ' . $lang->kevinplan->manYear;?></td>
						<th class='w-80px'><strong><?php echo $lang->actions;?></strong></th>
						<td><?php
							commonModel::printIcon('kevinplan', 'planview', "id=$planItem->id", '', 'list', 'search', '', 'iframe', true);
							if (!$planItem->deleted) commonModel::printIcon('kevinplan', 'planedit', "id=" . $planItem->id, '', 'list', 'pencil', '', 'iframe', true);
							commonModel::printIcon('kevinplan', 'updateplanhours', "id=$planItem->id", '', 'list', 'refresh', '', 'iframe', true);
							?>
						</td>
						<td colspan="2"><?php
							echo html::a($this->createLink('kevinplan', 'projectexport', "id={$planItem->id}", '', false), "<i class='icon-print'></i>" . $lang->kevinplan->projectexport, '', "class='btn' target = '_blank'");
							?>
						</td>
					</tr>
					<tr>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->dept;?></strong></th>
						<td><?php echo isset($deptlist[$planItem->dept]) ? $deptlist[$planItem->dept] : '';?></td>

						<th class='w-100px'><strong><?php echo $lang->kevinplan->charger;?></strong></th>
						<td><?php echo isset($userlist[$planItem->charger]) ? $userlist[$planItem->charger] : '';?></td>

						<th class='w-100px'><strong><?php echo $lang->kevinplan->planYear;?></strong></th>
						<td><?php echo $planItem->planYear;?></td>

						<th class='w-100px'><strong><?php echo $lang->kevinplan->startDate;?></strong></th>
						<td><?php echo $planItem->startDate;?></td>

						<th class='w-100px'><strong><?php echo $lang->kevinplan->endDate;?></strong></th>
						<td><?php echo $planItem->endDate?></td>
					</tr>
				</table>
			</fieldset>
		</div>
	<?php }?>

	<form id = 'form1' class='form-condensed' method='post' target='hiddenwin'  >
		<?php echo html::hidden('kevinformtype', "projectlist");?>

		<table class='table table-condensed  table-hover table-striped tablesorter '>
			<thead>
				<tr class='text-center' height=35px>
					<th class='text-center w-40px'><?php echo $lang->kevinplan->id;?></th>
					<th class='text-center w-80px {sorter:false}'><?php echo $lang->actions;?></th>
					<th class='text-center w-40px'><?php echo $lang->kevinplan->YearAB;?></th>
					<th class='text-center w-80px'><?php echo $lang->kevinplan->dept;?></th>
					<th class='text-center w-80px'><?php echo $lang->kevinplan->charger;?>1</th>
					<th class='text-center w-80px'><?php echo $lang->kevinplan->charger;?>2</th>
					<th class='text-center w-40px'><?php echo $lang->kevinplan->projectCode;?></th>
					<th class='text-center w-auto' style='min-width:100px'><?php echo $lang->kevinplan->projectPlanName;?></th>
					<th class='text-right w-60px'><?php echo $lang->kevinplan->hours;?></th>
					<th class='text-center w-60px nobr'><?php echo $lang->kevinplan->hoursCost;?></th>
					<th class='text-center w-60px nobr'><?php echo $lang->kevinplan->status;?></th>
					<th class='text-center w-60px nobr'><?php echo $lang->kevinplan->projectPri;?></th>
					<th class='text-center w-60px nobr'><?php echo $lang->kevinplan->classPro;?></th>
					<th class='text-center w-60px nobr'><?php echo $lang->kevinplan->ProNew;?></th>
					<th class='text-center w-date'><?php echo $lang->kevinplan->startDate;?></th>
					<th class='text-center w-date'><?php echo $lang->kevinplan->dateDR2;?></th>
					<th class='text-center w-date'><?php echo $lang->kevinplan->endDate;?></th>
				</tr>
			</thead>
			<?php
			$totalHours = 0;
			foreach ($ProjectArray as $item):
				$item->manYear	 = $item->hoursPlan / 2000;
				$totalHours += $item->hoursPlan;
				if ($item->deleted) $style			 = "background:red";
				else $style			 = "";
				$viewLink		 = $this->createLink('kevinplan', 'projectview', "id=$item->id", '', true);
				$canView		 = common::hasPriv('kevinplan', 'projectview');
				?>
				<tr>
					<td class='text-center nobr' style="<?php echo $style;?>">
						<?php
						echo "<input type='checkbox' name='choices[]' value=$item->id> ";
						printf('%03d', $item->id);
						?></td>
					<td class='text-center nobr'><?php
						commonModel::printIcon('kevinhours', 'project', "id={$item->projectCode}&type=thisYear", '', 'list', 'time', '', 'iframe', true);
						if ($this->kevinplan->isCharger[$item->id]) commonModel::printIcon('kevinplan', 'projectview', "id=$item->id", '', 'list', 'search', '', 'iframe', true);
						else echo "<i class='icon-kevinplan-memberedit icon-search disabled' style='font-size:18px;'></i>";
						if (!$item->deleted && $this->kevinplan->isCharger[$item->id]) commonModel::printIcon('kevinplan', 'projectedit', "id=$item->id", '', 'list', 'pencil', '', 'iframe', true);
						else echo "<i class='icon-kevinplan-memberedit icon-pencil disabled' style='font-size:18px;'></i>";
						if ($PlanSource && $this->kevinplan->isCharger[$item->id]) commonModel::printIcon('kevinplan', 'projectdelete', "project=$item->id&plan=$PlanSource&confirm=no", '', 'list', 'remove', 'hiddenwin');
						else echo "<i class='icon-kevinplan-memberedit icon-remove disabled' style='font-size:18px;'></i>";
						?></td>			
					<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->planYear;?></td>
					<td class='text-left nobr'><?php echo $deptlist[$item->dept];?></td>

					<td class='text-left nobr'  style="word-wrap:break-word;"><?php
						commonModel::printIcon('kevinplan', 'projectlist', "type=$type&plan=&charger=" . $item->charger, '', 'list', 'list');
						commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=&member=" . $item->charger, '', 'list', 'user');
						echo (array_key_exists($item->charger, $userlist)) ? $userlist[$item->charger] : $item->charger;
						?></td>
					<td class='text-left nobr'  style="word-wrap:break-word;"><?php
						if ($item->charger2) {
							commonModel::printIcon('kevinplan', 'projectlist', "type=$type&plan=&charger=" . $item->charger2, '', 'list', 'list');
							commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=&member=" . $item->charger2, '', 'list', 'user');
							echo (array_key_exists($item->charger2, $userlist)) ? $userlist[$item->charger2] : $item->charger2;
						}
						?></td>
					<td class='text-left' ><?php if ($item->projectCode > 0) echo $item->projectCode;?>	</td>
					<td class='text-left nobr'  style="word-wrap:break-word;"><?php
						commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=$item->id", '', 'list', 'user');
						echo $item->name;
						?></td>
					<td class='text-right'  style="word-wrap:break-word;"><?php echo $item->hoursPlan;?></td>
					<td class='text-right'  style="word-wrap:break-word;"><?php echo $item->hoursCost;?></td>
					<td class='text-center'><?php echo $lang->kevinplan->statusList[$item->status];?></td>
					<td class='text-center'><?php echo $lang->kevinplan->projectPriList[$item->pri];?></td>
					<td class='text-center'><?php echo $lang->kevinplan->classProList[$item->classPro];?></td>
					<td class='text-center'><?php echo $lang->kevinplan->ProNewList[$item->ProNew];?></td>
					<td class='text-center'><?php echo $item->startDate;?></td>
					<td class='text-center'><?php echo $item->dateDR2;?></td>
					<td class='text-center'><?php echo $item->endDate;?></td>
				</tr>
			<?php endforeach;?>
			<tfoot>
				<tr>
					<td colspan=11 align='right'>
						<div class='table-actions clearfix'>
							<?php
							echo html::selectButton();
							if ($groupcreate) echo html::submitButton($lang->save, "onclick=groupcreate('$plan')", 'btn btn-default');
							else echo html::submitButton($lang->kevinplan->updateprohours, "", 'btn btn-default');
							?>
						</div>
						<div class='actions actions-form'>
							<?php echo "This page: " . $lang->kevinplan->hours . " = " . $totalHours . $lang->kevinplan->hoursunit . ". ( " . sprintf("%.2f", $totalHours / 2000) . $lang->kevinplan->manYear . ")";?>
							<?php
							if ($groupcreate) echo html::submitButton($lang->save, "onclick=groupcreate('$plan')") . html::linkButton($lang->goback, $this->inlink('projectlist', "type=$type&plan=$planItem->id"));
							$pager->show();
							?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>