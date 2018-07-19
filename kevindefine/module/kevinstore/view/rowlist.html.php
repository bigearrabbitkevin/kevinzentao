<?php
/**
 * The browse user view file of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php
$vars = "group=$group&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
?>
<div id='featurebar'>
	<div class='actions'>
		<?php
		common::printIcon('kevinstore', 'rowcreate', '', '', 'button', 'plus', '', 'iframe', true);
		?>
	</div>
</div>
<div class='side'>
	<a class='side-handle' data-id='companyTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']); ?> <strong>
					<?php common::printLink('kevinstore', 'rowlist', "", $lang->kevinstore->group); ?></strong></div>
			<div class='panel-body'>
				<ul class="tree treeview">
					<?php foreach ($groups as $group):
						?>
						<li ><?php common::printLink('kevinstore', 'rowlist', "group=$group->id", $group->name); ?>        </li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class='main' style="overflow:auto; ">

	<?php if ($groupItem) : ?>
		<div><?php echo $lang->kevinstore->group . " > " . $groupItem->name . " > " . $lang->kevinstore->rowlist ?> : </div>
		<?php
	endif;
	?>
	<table class='table table-condensed table-hover tablesorter ' id='storeList'>
		<thead>
			<tr class='colhead'>
				<th class='w-id'><?php common::printorderlink('id', $orderBy, $vars, $lang->idAB); ?></th>
				<th class='w-150px'><?php common::printOrderLink('number', $orderBy, $vars, $lang->kevinstore->number); ?></th>
				<th class='w-auto'><?php common::printOrderLink('name', $orderBy, $vars, $lang->kevinstore->name); ?></th>
				<th class='w-auto'><?php common::printOrderLink('actionType', $orderBy, $vars, $lang->kevinstore->actionType); ?></th>
				<th class='w-auto'><?php common::printOrderLink('actionTime', $orderBy, $vars, $lang->kevinstore->actionTime); ?></th>
				<th class='w-auto'><?php common::printOrderLink('count', $orderBy, $vars, $lang->kevinstore->count); ?></th>
				<th class='w-auto'><?php common::printOrderLink('actionTeam', $orderBy, $vars, $lang->kevinstore->actionTeam); ?></th>
				<th class='w-auto'><?php common::printOrderLink('usage', $orderBy, $vars, $lang->kevinstore->usage); ?></th>
				<th class='w-auto'><?php common::printOrderLink('stdPrice', $orderBy, $vars, $lang->kevinstore->stdPrice); ?></th>
				<th class='w-auto'><?php common::printOrderLink('totalPrice', $orderBy, $vars, $lang->kevinstore->totalPrice); ?></th>
				<th class='w-auto'><?php common::printOrderLink('suplier', $orderBy, $vars, $lang->kevinstore->suplier); ?></th>
				<th class='w-auto'><?php common::printOrderLink('actionMonth', $orderBy, $vars, $lang->kevinstore->actionMonth); ?></th>
				<th class='w-100px'><?php common::printOrderLink('group', $orderBy, $vars, $lang->kevinstore->group); ?></th>
				<th class='w-user'><?php common::printOrderLink('subType', $orderBy, $vars, $lang->kevinstore->subType); ?></th>
				<th class='w-80px'><?php echo $lang->actions; ?></th>
			</tr>
		</thead>
		<tbody>

			<?php
			$canBatchEdit		 = false; //common::hasPriv('user', 'batchEdit');
			$canManageContacts	 = false; //common::hasPriv('user', 'manageContacts');
			$canrowdelete		 = common::hasPriv('kevinstore', 'rowdelete');
			$canUserEdit		 = common::hasPriv('kevinstore', 'rowedit');
			?>
			<?php
			foreach ($rowList as $rowItem):
				?>
				<tr class='text-center'>
					<td>
						<?php
						if ($canBatchEdit or $canManageContacts) echo "<input type='checkbox' name='rowList[]' value='$rowItem->name'> ";
						printf('%03d', $rowItem->id);
						?>
					</td>
					<td><?php
						if (!commonModel::printLink('kevinstore', 'itemview', "number=$rowItem->number", $rowItem->number, '', 'id="editCompany" class="iframe" data-width="580"', true, true)) {
							echo $rowItem->number;
						}
						?></td>
					<td><?php echo $rowItem->name; ?></td>
					<td><?php echo $rowItem->actionType; ?></td>
					<td><?php echo $rowItem->actionTime; ?></td>
					<td><?php echo $rowItem->count; ?></td>
					<td><?php echo $rowItem->actionTeam; ?></td>
					<td><?php echo $rowItem->usage; ?></td>
					<td><?php echo $rowItem->stdPrice; ?></td>
					<td><?php echo $rowItem->totalPrice; ?></td>
					<td><?php echo $rowItem->suplier; ?></td>
					<td><?php echo $rowItem->actionMonth; ?></td>
					<td><?php echo $rowItem->group; ?></td>
					<td><?php echo $rowItem->subType; ?></td>
					<td class='text-left'>
						<?php
						commonModel::printIcon('kevinstore', 'rowview', "id=$rowItem->id", '', 'list', 'search', '', 'iframe', true);
						if ($canUserEdit) commonModel::printIcon('kevinstore', 'rowedit', "id=$rowItem->id", '', 'list', 'pencil', '', 'iframe', true);

						//if ($canrowdelete) commonModel::printIcon('kevinstore', 'rowdelete', "id=$rowItem->id", '', 'list', 'remove', '', 'iframe', true, "data-width='550'");
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='12'>
					<div class='table-actions clearfix'>
						<?php
						if ($canBatchEdit or $canManageContacts) echo "<div class='btn-group'>" . html::selectButton() . '</div>';
						if ($canBatchEdit) echo html::submitButton($lang->edit, 'onclick=batchEdit()', 'btn-default');
						if ($canManageContacts) echo html::submitButton($lang->kevinstore->contacts->manage, 'onclick=manageContacts()');
						?>
					</div>
					<?php echo $pager->show(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<?php
js::set('group', $group);
?>
<?php include '../../kevincom/view/footer.html.php'; ?>
