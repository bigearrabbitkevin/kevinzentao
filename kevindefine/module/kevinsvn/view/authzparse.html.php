<?php
/**
 * The browse user view file of kevinsvn module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinsvn
 */
?>
<?php
include '../../kevincom/view/header.html.php';
include '../../common/view/tablesorter.html.php';
$repedit=common::hasPriv('kevinsvn','repedit');
$reparse=common::hasPriv('kevinsvn','reparse');
$repdelete=common::hasPriv('kevinsvn','repdelete');
$accountimport=common::hasPriv('kevinsvn','account');
?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='id'><strong><?php echo $repid;?></strong></span>
		<span class='prefix' title='id'><?php echo $lang->kevinsvn->replist . " > " . $lang->kevinsvn->authzparse . " > ";?></span>
	</div>
	<div class="actions">
		 <?php if($accountimport)echo html::a(helper::createLink('kevinsvn', 'account',"repid=$repid",'',false), "<i class='icon-upload'></i> " . $lang->kevinsvn->accountimport, '', "class='btn'");?>
	</div>
</div>
<div class='main' style="overflow:auto; ">
	<?php if ($repid&&isset($repitem)&&$repitem){//if ($RepSource != 0) {?>
	<div>
			<fieldset>
				<legend><?php echo $lang->kevinsvn->rep.'信息';?></legend>
				<table class='table table-data table-condensed table-borderless'>
					<tr>
						<th class='w-80px'><strong><?php echo $lang->kevinsvn->rep;?></strong></th>
						<td><?php echo sprintf('%03d', $repid);?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinsvn->name;?></strong></th>
						<td><?php echo $repitem->name;?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinsvn->title;?></strong></th>
						<td><?php echo $repitem->title;?></td>
						<th class='w-80px'><strong><?php echo $lang->actions;?></strong></th>
						<td><?php
							if($reparse)commonModel::printIcon('kevinsvn', 'reparse', "id=$repitem->id", '', 'list', 'branch', '', 'iframe', true);
							if ($repedit) commonModel::printIcon('kevinsvn', 'repedit', "repid=" . $repitem->id, '', 'list', 'pencil', '', 'iframe', true);
							if ($repdelete) commonModel::printIcon('kevinsvn', 'repdelete', "repid=" . $repitem->id, '', 'list', 'remove', '', 'iframe', true);
							?>
						</td>
					</tr>
					<tr>
						<th class='w-80px'><strong><?php echo $lang->kevinsvn->project;?></strong></th>
						<td><?php echo $repitem->project;?></td>
						<th class='w-100px'><strong><?php echo $lang->kevinsvn->dept;?></strong></th>
						<td><?php echo isset($depts[$repitem->dept]) ? $depts[$repitem->dept] : '';?></td>
						<th class='w-100px'><strong><?php echo $lang->kevinsvn->charger;?></strong></th>
						<td><?php echo isset($users[$repitem->charger]) ? $users[$repitem->charger] : '';?></td>
						<th class='w-100px'><strong><?php echo $lang->kevinsvn->class;?></strong></th>
						<td><?php echo $repitem->class;?></td>
					</tr>
				</table>
			</fieldset>
		</div>
	<?php }?>
	<form class='form-condensed' method='post' id="svnauthz">
	<table class='table table-hover table-striped table-bordered table-form ' id='DeviceList'>
	<thead>
		<tr class='colhead'>
			<th class='w-100px'><?php echo $lang->kevinsvn->folder;?></th>
			<th class='w-100px'><?php echo $lang->kevinsvn->account;?></th>
			<th class='w-100px'><?php echo $lang->kevinsvn->authz;?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$canedit		 =common::hasPriv('kevinsvn', 'repedit');
		$candelete		 = common::hasPriv('kevinsvn', 'repdelete');
		?>
		<?php	$i=0;$borsty="border-bottom:1px solid #DDD;border-right:1px solid #DDD;";
		$reptotal=0;
			foreach ($dirs as $diracc)$reptotal+=count($diracc);
		?>
			<?php foreach($dirs as $dir=>$accauthz): $k=0;
						foreach($accauthz as $account=>$authz): ?>
					<tr class='text-center'>
						<?php if($k===0){ ?><td style='<?php echo $borsty;?>' rowspan='<?php echo count($accauthz);?>'><?php echo $dir;?></td><?php } ?>
						<td style='<?php echo $borsty;?>'><?php echo (isset($users[$account])) ? $users[$account] : $account;?></td>
						<td style='<?php echo $borsty;?>'><?php echo (isset($lang->kevinsvn->authzEnum[$authz])) ? $lang->kevinsvn->authzEnum[$authz]: '';?></td>
						<?php $i++;$k++;?>
					</tr>
			<?php endforeach;
				endforeach;?>
	</tbody>
</table>
</form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
