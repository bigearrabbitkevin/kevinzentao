<?php
/**
 * The browse user view file of kevindevice module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevindevice
 */
?>
<?php include '../../kevincom/view/header.html.php';?>
<?php include 'kevindevicebar.html.php';
$canedit=common::hasPriv('kevindevice', 'maintainedit');
$candelete=common::hasPriv('kevindevice', 'maintaindelete');
$cancreate=common::hasPriv('kevindevice', 'maintaincreate');?>
<div class='side'>
	<a class='side-handle' data-id='companyTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?>
			<strong><?php common::printLink('kevindevice', 'maintainlist', "", $lang->kevindevice->year);?></strong></div>
			<div class='panel-body'>
				<ul class="tree treeview">
					<?php foreach ($years as $year):
						?>
						<li><?php common::printLink('kevindevice', 'maintainlist', "year=$year", $year);?>
						<?php if($selectyear==$year)echo "<i class='icon-lightbulb'></i>";?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class='main' style="overflow:auto; ">
<table class='table table-condensed table-hover tablesorter ' id='DeviceList'>
	<thead>
		<tr class='colhead'>
			<?php $vars				 = "year=$selectyear&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
			<th><?php common::printOrderLink('time', $orderBy, $vars, $lang->kevindevice->time);?></th>
			<th><?php common::printOrderLink('platform', $orderBy, $vars, $lang->kevindevice->platform);?></th>
			<th><?php common::printOrderLink('log', $orderBy, $vars, $lang->kevindevice->log);?></th>
			<th><?php common::printOrderLink('total', $orderBy, $vars, $lang->kevindevice->total);?></th>
			<th><?php echo $lang->actions;?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($maintains as $maintain):?>
			<tr class='text-center'>
				<td><?php echo $maintain->time;?></td>
				<td><?php echo $maintain->platform;?></td>
				<td><?php echo $maintain->log;?></td>
				<td><?php echo $maintain->total;?></td>
				<td class='text-center'>
					<?php
					if ($canedit)common::printIcon('kevindevice', 'maintainedit', "maintainID=$maintain->id", '', '', 'pencil','','iframe',true);
					if ($candelete)common::printIcon('kevindevice', 'maintaindelete', "maintainID=$maintain->id", '', '', 'remove', 'hiddenwin');
					?>
				</td>
			</tr>
<?php endforeach;?>
			<tr class='text-center'>
				<td><?php echo $lang->kevindevice->subtotal;?></td>
				<td><?php echo $sumplatform;?></td>
				<td><?php echo $sumlog;?></td>
				<td><?php echo $sumtotal;?></td>
				<td></td>
			</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan='12'>
				<?php echo $pager->show();?>
			</td>
		</tr>
	</tfoot>
</table>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
