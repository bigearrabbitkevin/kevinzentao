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
<div id='featurebar'>
	<div class='actions'>
		<?php
		echo html::a($this->createLink('kevinplan', 'plancreate', "", '', true)
			, "<i class='icon-plus'></i>" . $lang->kevinplan->plancreate, '', "data-toggle='modal' data-type='iframe' data-icon='check'");
		?>
	</div>
</div>

<div class='side' id='treebox'>
	<a class='side-handle' data-id='kevinplanTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm' style="height: 500px;">
			<?php if (common::hasPriv('kevinplan', 'planFilter')):?>      
				<div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']);?> <button id='planFilter' class='btn' type='button' value='2' onclick='onButtonFilter()'><?php echo $lang->kevinplan->planFilter;?></button></div>
				<form id='searchform' method="post" class='form-condensed'>
					<?php echo html::hidden('kevinformtype', "planFilter");?>
					<table class='table table-form'>
						<tr>
							<th class='text-left nobr'><?php echo $lang->kevinplan->planName;?></th>
							<td class='w-auto'><?php echo html::input('name', $Filter->name, 'class=form-control');?></td>
						</tr>
						<?php if (common::hasPriv('kevinplan', 'browseDeleted')):?>      
							<tr>
								<th class='text-left nobr'><?php echo $lang->kevinplan->deleted;?></th>
								<td class='w-auto'><?php echo html::select('deleted', $lang->kevinplan->deletedList, $Filter->deleted, 'class=form-control');?></td>
							</tr>
						<?php endif;?>
						<tr><td class='text-right' colspan="4"><?php echo html::submitButton('搜索');?></td></tr>
					</table>
				</form>	
			<?php endif;?>
		</div>
	</div>
</div>
<div class='main'>
	<table class='table table-condensed  table-hover table-striped tablesorter '>
		<thead>
			<tr class='text-center' height=35px>
				<th class='text-center w-id'><?php echo $lang->kevinplan->id;?></th>
				<th class='text-center w-50px {sorter:false}'><?php echo $lang->actions;?></th>
				<th class='text-center w-60px'><?php echo $lang->kevinplan->Year;?></th>
				<th class='text-left w-100px'><?php echo $lang->kevinplan->dept;?></th>
				<th class='text-left w-100px'><?php echo $lang->kevinplan->charger;?></th>
				<th class='text-left w-auto' style='min-width:100px'><?php echo $lang->kevinplan->planName;?></th>
				<th class='text-right w-80px'><?php echo $lang->kevinplan->manYear;?></th>
				<th class='text-center w-date'><?php echo $lang->kevinplan->startDate;?></th>
				<th class='text-center w-date'><?php echo $lang->kevinplan->endDate;?></th>
                <th class='text-center w-60px'><?php echo $lang->kevinplan->status;?></th>
			</tr>
		</thead>
		<?php
		$totalHours = 0;
		foreach ($PlanArray as $item):
			$item->manYear	 = $item->hoursPlan / 2000;
			$totalHours += $item->hoursPlan;
			if ($item->deleted) $style			 = "background:red";
			else $style			 = "";
			$viewLink		 = $this->createLink('kevinplan', 'planview', "id=$item->id");
			$canView		 = common::hasPriv('kevinplan', 'planview');
			?>
			<tr>		
				<td class='text-center nobr' style="<?php echo $style;?>"><?php printf('%03d', $item->id);?></td>
				<td class='text-center nobr'>
					<?php
					commonModel::printIcon('kevinplan', 'planview', "id=$item->id", '', 'list', 'search', '', 'iframe', true);
					if (!$item->deleted) commonModel::printIcon('kevinplan', 'planedit', "id=" . $item->id, '', 'list', 'pencil', '', 'iframe', true);
					commonModel::printIcon('kevinplan', 'projectexport', "id=$item->id", '', 'list', 'print', '_blank', '', false);
					//public statiprintIcon($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '', $title = '')
					?>
				</td>
				<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->planYear;?></td>
				<td class='text-left nobr'  style="word-wrap:break-word;"><?php echo isset($deptlist[$item->dept]) ? $deptlist[$item->dept] : '';?></td>
				<td class='text-left nobr'  style="word-wrap:break-word;"><?php
					commonModel::printIcon('kevinplan', 'projectlist', "type=&plan=&charger=" . $item->charger, '', 'list', 'list');
					commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=&member=" . $item->charger, '', 'list', 'user');
					$showChargerName = (array_key_exists($item->charger, $userlist)) ? $userlist[$item->charger] : $item->charger;
					echo $showChargerName;
					?></td>
				<td class='text-left nobr'  style="word-wrap:break-word;"><?php
					commonModel::printIcon('kevinplan', 'projectlist', "type=&plan=$item->id", '', 'list', 'list');
					commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=$item->id", '', 'list', 'user');
					echo $item->name;
					?></td>
				<td class='text-right'  style="word-wrap:break-word;"><?php echo sprintf("%.2f", $item->manYear);?></td>
				<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->startDate;?></td>
				<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->endDate;?></td>
				<td class='text-center'  style="word-wrap:break-word;"><?php echo $lang->kevinplan->statusList[$item->status];?></td>
			</tr>
		<?php endforeach;?>
		<tfoot>
			<tr>
				<td colspan=7 align='right'>

					<?php echo "This page: " . $lang->kevinplan->hours . " = " . $totalHours . $lang->kevinplan->hoursunit . ". ( " . sprintf("%.1f", $totalHours / 2000) . $lang->kevinplan->manYear . ")";?>
					<?php $pager->show();?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
