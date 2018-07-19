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
<?php
$this->moduleName	 = "kevinplan";
$this->methodName	 = "config";
?>
<?php include '../../kevinhours/view/header.html.php'; ?>
<?php include '../../common/view/tablesorter.html.php'; 

if(!$project) {
echo 'No this projectCode';
	die();
}
//die(json_encode($project));
//die(json_encode($ProjectArray));
?>
<div id='featurebar'>
	指定项目代号<?php  if($project)echo sprintf('%03d', $project->id); ?>下的计划
	<div class='actions'>		
	</div>
</div>
<div class='main'>
	<?php if($project){ ?>
		<div >
			<fieldset>
				<legend><?php echo $lang->kevinplan->project; ?></legend>
				<table class='table table-data table-condensed table-borderless'>			
					<tr>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->plan; ?></strong></th>
						<td><?php echo sprintf('%03d', $project->id); ?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinplan->planName; ?></strong></th>
						<td><?php echo $project->name; ?></td>
						<th class='w-80px'><strong><?php echo $lang->actions; ?></strong></th>
						<td><?php 
							/*commonModel::printIcon('kevinplan', 'planview', "id=$project->id", '', 'list', 'search', '', 'iframe', true);
							if(!$project->deleted)commonModel::printIcon('kevinplan', 'planedit', "id=" . $project->id, '', 'list', 'pencil', '', 'iframe', true);
							commonModel::printIcon('kevinplan', 'updateplanhours', "id=$project->id", '', 'list', 'refresh', '', 'iframe', true);
							*/?></td>
					</tr>
				</table>
			</fieldset>
		</div>
<?php } ?>
	
    <form id = 'form1' class='form-condensed' method='post' target='hiddenwin'  >
        <table class='table table-condensed  table-hover table-striped tablesorter '>
		<thead>
			<tr class='text-center' height=35px>
				<th class='text-center w-id'><?php echo $lang->kevinplan->id; ?></th>
				<th class='text-center w-60px'><?php echo $lang->kevinplan->Year; ?></th>
				<th class='text-center w-80px'><?php echo $lang->kevinplan->dept; ?></th>
				<th class='text-center w-100px'><?php echo $lang->kevinplan->charger; ?></th>
				<th class='text-center w-id'><?php echo $lang->kevinplan->projectCode; ?></th>
				<th class='text-center w-auto' style='min-width:100px'><?php echo $lang->kevinplan->projectPlanName; ?></th>
				<th class='text-right w-60px'><?php echo $lang->kevinplan->hours; ?></th>
				<th class='text-center w-date'><?php echo $lang->kevinplan->startDate; ?></th>
				<th class='text-center w-date'><?php echo $lang->kevinplan->endDate; ?></th>
				<th class='text-center w-60px'><?php echo $lang->kevinplan->status; ?></th>
				<th class='text-center w-100px {sorter:false}'><?php echo $lang->actions; ?></th>
			</tr>
		</thead>
		<?php
		$totalHours = 0;
		foreach ($ProjectArray as $item):
			$item->manYear = $item->hoursPlan /2000;
			$totalHours += $item->hoursPlan;
			if ($item->deleted) $style		 = "background:red";
			else $style		 = "";
			$viewLink	 = $this->createLink('kevinplan', 'projectview', "id=$item->id", '', true);
			$canView	 = common::hasPriv('kevinplan', 'projectview');
			?>
			<tr>		
				<td class='text-center' style="<?php echo $style; ?>">
					<?php echo "<input type='checkbox' name='choices[]' value=$item->id> ";
					printf('%03d', $item->id); ?>
				</td>
				<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->planYear; ?></td>
				<td class='text-left'  style="word-wrap:break-word;"><?php echo $deptlist[$item->dept]; ?></td>
				<td class='text-left '  style="word-wrap:break-word;"><?php
					commonModel::printIcon('kevinplan', 'projectlist', "type=&plan=&charger=" . $item->charger, '', 'list', 'list');
					commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=&member=" . $item->charger, '', 'list', 'user');
					$showChargerName = (array_key_exists($item->charger,$userlist))?$userlist[$item->charger]:$item->charger;
					echo  $showChargerName;?></td>
				<td class='text-left' ><?php 
					if ($item->projectCode==0) echo ''; 
					else {
						echo $item->projectCode; 
					}?>	</td>
				<td class='text-left'  style="word-wrap:break-word;"><?php 
					commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=$item->id", '', 'list', 'user');
					echo $item->name; ?></td>
				<td class='text-right'  style="word-wrap:break-word;"><?php echo $item->hoursPlan; ?></td>
				<td class='text-center'><?php echo $item->startDate; ?></td>
				<td class='text-center'><?php echo $item->endDate; ?></td>
                <td class='text-center'><?php echo $lang->kevinplan->statusList[$item->status]; ?></td>
				<td class='text-center'><?php
					commonModel::printIcon('kevinhours', 'project', "id={$item->projectCode}&type=thisYear", '', 'list', 'time', '', 'iframe', true);
					commonModel::printIcon('kevinplan', 'projectview', "id=$item->id", '', 'list', 'search', '', 'iframe', true);
					if(!$item->deleted)commonModel::printIcon('kevinplan', 'projectedit', "id=$item->id", '', 'list', 'pencil', '', 'iframe', true);
					if($plan)commonModel::printIcon('kevinplan', 'projectdelete', "project=$item->id", '', 'list', 'remove', 'hiddenwin');
					?></td>
			</tr>
					<?php endforeach; ?>
		<tfoot>
		</tfoot>
	</table>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>