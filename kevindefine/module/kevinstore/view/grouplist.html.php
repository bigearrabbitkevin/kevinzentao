<?php
/**
 * The browse group view file of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php include 'kevinstorebar.html.php'; ?>
<div class="main">
	<table align='center' class='table table-condensed table-hover table-striped  tablesorter table-fixed' id='groupList'>
		<thead>
			<tr>
				<th class='w-auto'><?php echo $lang->kevinstore->id; ?></th>
				<th class='w-auto'><?php echo $lang->kevinstore->name; ?></th>
				<th class='w-auto {sorter:false}'><?php echo $lang->actions; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 0;
			foreach ($groupList as $groupItem):
				$i++;
				?>
				<tr class='text-center'>
					<td class='strong'><?php echo $i; ?></td>
					<td class='text-center'><?php echo $groupItem->name; ?></td>
					<td class='text-center'>
						<?php
						echo " ";
						//common::printIcon('kevinstore', 'groupedit', "group=$groupItem->id", '', 'edit', 'pencil', '', 'iframe', 'yes');
						if (common::hasPriv('kevinstore', 'groupdelete')) {
							echo " ";
							//common::printIcon('kevinstore', 'groupdelete', "group=$groupItem->id", '', 'edit', 'remove', '', 'iframe', 'yes');
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
