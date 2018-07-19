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
$canedit=common::hasPriv('kevindevice', 'spotchkedit');
$candelete=common::hasPriv('kevindevice', 'spotchkdelete');
$cancreate=common::hasPriv('kevindevice', 'spotchkcreate');?>
<div class='main' style="overflow:auto; ">
<table class='table table-condensed table-hover tablesorter ' id='DeviceList'>
	<thead>
		<tr class='colhead'>
			<?php $vars				 = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
			<th><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
			<th><?php common::printOrderLink('name', $orderBy, $vars, $lang->kevindevice->maker);?></th>
			<th><?php echo $lang->actions;?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($spotchks as $spotchk):?>
			<tr class='text-center'>
				<td style='<?php if($spotchk->deleted==1)echo 'background:red';?>'><?php echo $spotchk->id;?></td>
				<td><?php echo $spotchk->name;?></td>
				<td class='text-left'>
					<?php
					if ($canedit)common::printIcon('kevindevice', 'spotchkedit', "id=$spotchk->id", '', '', 'pencil','','iframe',true);
					if ($candelete)common::printIcon('kevindevice', 'spotchkdelete', "id=$spotchk->id", '', '', 'remove', 'hiddenwin');
					?>
				</td>
			</tr>
		<?php endforeach;?>
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
