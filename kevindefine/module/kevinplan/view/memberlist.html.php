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
<?php include '../../common/view/tablesorter.html.php';?>
<?php
$sessionString = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
//if($groupcreate) echo '>'.'&nbsp'.$this->lang->kevinplan->groupcreate; 

$Filter								 = & $this->kevinplan->Filter;
//设定不需要显示的列
$Filter->methodName					 = 'memberlist';
$Filter->ShowColList['charger']		 = 0;
$Filter->ShowColList['projectName']	 = 0;
if ($Filter->type == 'my') $Filter->ShowColList['member']		 = 0;

$project						 = $Filter->project;
if ($project) $Filter->ShowColList['member']	 = 0;

$memberlist[$this->app->user->account]	 = $this->app->user->account;
$memberlistWithName						 = array();
$memberlistWithName['']					 = '';
$memberlistWithName['myplan']			 = $lang->kevinplan->myplan;
$memberlistWithName['myproject']		 = $lang->kevinplan->myproject;
foreach ($memberlist as $item):
	$memberlistWithName[$item] = $userlist[$item] . "(" . $item . ")";
endforeach;
$createMembePlanLink						 = html::a($this->createLink('kevinplan', 'membercreate', "project=$project", '', true)
		, "<i class='icon-plus'></i>" . $lang->kevinplan->membercreate, '', "class='btn' data-toggle='modal' data-type='iframe' data-icon='check'");
$this->kevinplan->Filter->FilterProjectList	 = &$projectlist;

$browseAllPlan = (common::hasPriv('kevinplan', 'browseAllPlan')) || (common::hasPriv('kevinplan', 'browseDeptPlan'));
?>
<div id='featurebar'>
	<ul class="nav">
		<?php
		foreach ($lang->kevinsvn->types as $key => $label) {
			if ($key != 'my' && !$browseAllPlan) continue;
			echo "<li data-id=$key " . (($key === $type) ? "class='active'" : '') . '>' . html::a(inLink('memberlist', "type=$key"), $label, '', "") . '</li>';
		}
		?>
	</ul>
	<div class='actions'>
		<?php if ($this->kevinplan->isCharger[$project]) echo $createMembePlanLink;?>
	</div>
</div>
<div class='side'>
	<a class='side-handle' data-id='kevinplanTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?> <strong>
					<?php common::printLink('kevinplan', 'memberlist', "type=&plan=&project=0", $lang->kevinplan->planFilter);?></strong>
			</div>	
			<div style="min-height:400px">
				<?php include 'projectfilter.html.php';?>
			</div>
			<div style="height:100px">	
			</div>
		</div>
	</div>
</div>
<div class='main'>
	<?php if ($project != 0):?>
		<div>
			<fieldset>
				<legend><?php echo $lang->kevinplan->projectPlanInfo . " ($projectItem->planYear) ";?></legend>
				<table class='table table-data table-condensed table-borderless'>
					<tr>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->projectPlan . "ID";?></strong></th>
						<td><?php echo sprintf('%03d', $projectItem->id);?></td>
						<th><strong><?php echo $lang->kevinplan->dept;?></strong></th>
						<td><?php echo isset($deptArray[$projectItem->dept]) ? $deptArray[$projectItem->dept] : '';?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->projectPri;?></strong></th>
						<td><?php echo $lang->kevinplan->projectPriList[$projectItem->pri];?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->project . "ID";?></strong></th>
						<td><?php
							if ($projectItem->projectCode == 0) echo '';
							echo $projectItem->projectCode;
							?></td>
						<th class='w-20px nobr'><strong><?php echo $lang->actions;?></strong></th>
						<td><?php
							if ($projectItem->projectCode != 0) commonModel::printIcon('kevinhours', 'project', "id={$projectItem->projectCode}&type=thisYear", '', 'list', 'time', '', 'iframe', true);
							commonModel::printIcon('kevinplan', 'projectview', "id=$projectItem->id", '', 'list', 'search', '', 'iframe', true);
							if (!$projectItem->deleted) commonModel::printIcon('kevinplan', 'projectedit', "id=" . $projectItem->id, '', 'list', 'pencil', '', 'iframe', true);
							commonModel::printIcon('kevinplan', 'updateprohours', "id=$projectItem->id", '', 'list', 'refresh', '', 'iframe', true);
							commonModel::printIcon('kevinplan', 'updatecosthours', "plan=$plan&proid=$projectItem->id&project=$projectItem->projectCode", '', 'list', 'exchange', '', 'iframe', true);
							?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->planName;?></strong></th>
						<td><?php echo $projectItem->name;?></td>
						<th><strong><?php echo $lang->kevinplan->charger;?>1</strong></th>
						<td><?php echo isset($userlist[$projectItem->charger]) ? $userlist[$projectItem->charger] : '';?></td>
						<th><strong><?php echo $lang->kevinplan->charger;?>2</strong></th>
						<td><?php echo isset($userlist[$projectItem->charger2]) ? $userlist[$projectItem->charger2] : '';?></td>
						<th><strong><?php echo $lang->kevinplan->status;?></strong></th>
						<td><?php echo $lang->kevinplan->statusList[$projectItem->status];?></td>
					</tr>
					<tr>
						<th><strong><?php echo $lang->kevinplan->hoursPlan;?></strong></th>
						<td><?php echo $projectItem->hoursPlan;?></td>	
						<th ><strong><?php echo $lang->kevinplan->hoursCost;?></strong></th>
						<td><?php echo $projectItem->hoursCost;?></td>
						<th></th>
						<td></td>
						<th><strong><?php echo $lang->kevinplan->startend;?></strong></th>
						<td><?php echo $projectItem->startDate . ' ~ ';?></td>
						<td colspan="2"><?php echo $projectItem->endDate;?></td>
					</tr>					
				</table>
			</fieldset>
		</div>
	<?php endif;?>
	<table class='table table-condensed  table-hover table-striped tablesorter ' id='KevinValueList'>
		<thead>
			<tr class='text-center' height=35px>
				<th class='text-center w-40px'><?php echo $lang->kevinplan->id;?></th>
				<th class='text-center w-10px'><?php echo $lang->kevinplan->YearAB;?></th>
				<th class='text-center w-80px'><?php echo $lang->kevinplan->dept;?></th>
				<th class='text-left w-user'><?php echo $lang->kevinplan->memberName;?></th>
				<?php if ($project == 0):?>
					<th class='text-left w-50px'><?php echo $lang->kevinplan->projectCode;?></th>
					<th class='text-left w-auto' style='min-width:100px'><?php echo $lang->kevinplan->projectPlanName;?></th>
				<?php endif;?>
				<th class='text-left w-auto' style='min-width:100px'><?php echo $lang->kevinplan->workcontent;?></th>
				<th class='text-right w-60px'><?php echo $lang->kevinplan->hours;?></th>
				<th class='text-right w-80px'><?php echo $lang->kevinplan->hoursCost;?></th>
				<th class='text-right w-80px'><?php echo $lang->kevinplan->manYear;?></th>
				<th class='text-center w-date'><?php echo $lang->kevinplan->startDate;?></th>
				<th class='text-center w-date'><?php echo $lang->kevinplan->endDate;?></th>
				<th class='text-center w-50px nobr'><?php echo $lang->kevinplan->status;?></th>
				<th class='text-center w-80px {sorter:false}'><?php echo $lang->actions;?></th>
			</tr>
		</thead>
		<?php
		$totalHours = 0;
		foreach ($itemArray as $item):
			$item->manYear	 = $item->hours / 2000;
			$totalHours += $item->hours;
			if ($item->deleted) $style			 = "background:red";
			else $style			 = "";
			$canView		 = common::hasPriv('kevinplan', 'memberview');
			?>
			<tr>
				<td class='text-center' style="<?php echo $style;?>"><?php printf('%03d', $item->id);?></td>
				<td class='text-center'><?php echo $item->planYear;?></td>
				<td class='text-left nobr'><?php echo $deptArray[$item->dept];?></td>
				<td class='text-left nobr'><?php
					commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=&member=$item->member", '', 'list', 'user');
					echo $userlist[$item->member];
					?></td>
				<?php if ($project == 0):?>
					<td class='text-right'><?php
						if ($item->projectCode == 0) echo '';
						else echo $item->projectCode;
						?></td>
					<td class='text-left nobr'><?php
						commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=$item->project", '', 'list', 'user');
						echo $item->name;
						?></td>
				<?php endif;?>
				<td class='text-left nobr'><?php echo $item->notes;?></td>
				<td class='text-right'><?php echo $item->hours;?></td>
				<td class='text-right'><?php echo $item->hoursCost;?></td>
				<td class='text-right'><?php echo sprintf("%.2f", $item->manYear * 100) . '%';?></td>
				<td class='text-center'><?php echo $item->startDate;?></td>
				<td class='text-center'><?php echo $item->endDate;?></td>
				<td class='text-center'><?php echo $lang->kevinplan->statusList[$item->status];?></td>
				<td class='text-center'>
					<?php
					commonModel::printIcon('kevinplan', 'memberview', "id=$item->id", '', 'list', 'search', '', 'iframe', true);
					if ($this->kevinplan->isCharger[$project]) common::printIcon('kevinplan', 'memberdelete', "id={$item->id}", '', 'list', 'remove', '', 'iframe', true); //弹出窗口，取消后可以消失

					if ($this->kevinplan->isCharger[$project] || ( isset($this->kevinplan->isMember[$item->id]) && $this->kevinplan->isMember[$item->id])) {
						common::printIcon('kevinplan', 'memberedit', "id={$item->id}", '', 'list', 'pencil', '', 'iframe', true);
					} else {
						?><i class="icon-kevinplan-memberedit icon-pencil disabled" style="font-size:18px;"></i>
					<?php }?>
				</td>
			</tr>
		<?php endforeach;?>
		<tfoot>
			<tr>
				<td colspan=12 align='center'>
					<?php echo $lang->kevinplan->hours . " = " . $totalHours . $lang->kevinplan->hoursunit . ". ( " . sprintf("%.2f", $totalHours / 2000) . $lang->kevinplan->manYear . ")";?>
					<div class='actions actions-form'>
						<?php $page->show();?>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
	<?php
	if ($this->kevinplan->isCharger[$project]) echo $createMembePlanLink;
	else echo "不是项目负责人，不能添加人员计划";
	?>
	<?php include './memberlistdel.html.php'?>
	<br>
	<fieldset>
		<legend><?php echo $lang->kevinplan->belongto . $lang->kevinplan->plan;?></legend>
		<div><ul class="nav">
				<?php
				foreach ($planarrs as $fromplanid => $fromplan) {
					if (!$fromplanid) continue;
					echo '<li class = "pull-left">' . html::a($this->createLink('kevinplan', 'projectlist', "type=all&id=$fromplanid"), $fromplan, '', "") . '</li>';
				}
				?>
			</ul></div>
	</fieldset>
</div>
<?php include '../../kevincom/view/footer.html.php';?>