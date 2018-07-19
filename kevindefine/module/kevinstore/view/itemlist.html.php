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

<?php include 'kevinstorebar.html.php'; ?>
<?php
$vars = "group=$group&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
?>

<div class='side'>
	<a class='side-handle' data-id='companyTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']); ?> <strong>
					<?php common::printLink('kevinstore', 'itemlist', "", $lang->kevinstore->group); ?></strong></div>
			<div class='panel-body'>
				<ul class="tree treeview">
					<?php foreach ($groups as $group):
						?>
						<li ><?php common::printLink('kevinstore', 'itemlist', "group=$group->id", $group->name); ?>        </li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class='main' style="overflow:auto; ">

	<?php if ($groupItem) : ?>
		<div><?php echo $lang->kevinstore->group . " > " . $groupItem->name . " > " . $lang->kevinstore->itemlist ?> : </div>
		<?php
	endif;
	?>
	<table class='table table-condensed table-hover tablesorter ' id='storeList'>
		<thead>
			<tr class='colhead'>
				<th class='w-id'><?php common::printorderlink('id', $orderBy, $vars, $lang->idAB); ?></th>
				<th class='w-auto'><?php common::printOrderLink('number', $orderBy, $vars, $lang->kevinstore->number); ?></th>
				<th class='w-auto'><?php common::printOrderLink('name', $orderBy, $vars, $lang->kevinstore->name); ?></th>
				<th class='w-auto'><?php common::printOrderLink('group', $orderBy, $vars, $lang->kevinstore->group); ?></th>
				<th class='w-auto'><?php common::printOrderLink('subType', $orderBy, $vars, $lang->kevinstore->subType); ?></th>
				<th class='w-80px'><?php echo $lang->actions; ?></th>
			</tr>
		</thead>
		<tbody>

			<?php
			$canBatchEdit		 = false; //common::hasPriv('user', 'batchEdit');
			$canManageContacts	 = false; //common::hasPriv('user', 'manageContacts');
			$canitemdelete		 = common::hasPriv('kevinstore', 'itemdelete');
			$canUserEdit		 = common::hasPriv('kevinstore', 'itemedit');
			?>
			<?php
			foreach ($itemList as $item):
				?>
				<tr class='text-center'>
					<td>
						<?php
						if ($canBatchEdit or $canManageContacts) echo "<input type='checkbox' name='itemList[]' value='$item->name'> ";
						printf('%03d', $item->id);
						?>
					</td>
					<td><?php echo $item->number; ?></td>
					<td><?php echo $item->name; ?></td>
					<td><?php echo $item->group; ?></td>
					<td><?php echo $item->subType; ?></td>
					<td class='text-left'>
						<?php
						commonModel::printIcon('kevinstore', 'itemview', "number=$item->number", '', 'list', 'search', '', 'iframe', true);
						if ($canUserEdit) commonModel::printIcon('kevinstore', 'itemedit', "number=$item->number", '', 'list', 'pencil', '', 'iframe', true);

						//if ($canitemdelete) commonModel::printIcon('kevinstore', 'itemdelete', "number=$item->number", '', 'list', 'remove', '', 'iframe', true, "data-width='550'");
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
