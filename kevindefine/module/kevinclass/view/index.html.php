<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/sparkline.html.php';?>
<?php
$node	 = ($nodeItem) ? $nodeItem->id : "";
$vars	 = "node=$node&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
?>

<div class='side' id='treebox'>
	<a class='side-handle' data-id='productTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><i class="icon-book"></i>
				<strong><?php echo html::a(inlink('index'), ' &nbsp;' . $lang->kevinclass->index);?></strong></div>
			<!--
			<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
				
				<table class='table table-form table-fixed'>
					<tr>
						<td class='text-right'><?php echo html::input('book', '', '') . html::submitButton("搜索");?></td>
					</tr>
				</table>
			</form>
   -->
			<div class='panel-body'>
				<ul class='nav nav-primary nav-stacked'>
					<?php
					foreach ($books as $menu) {
						echo '<li' . (($menu->title == $nodeItem->title) ? " class='active'" : '') . '>' . html::a(inlink('index', "node=$menu->id"), '<i class="icon-book"></i> &nbsp;' . $menu->titleCN) . '</li>';
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class='main' style="overflow:auto; ">
	<div>
		<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
			<div class="pull-left">
				<?php if (!empty($nodeItem)) :?>
					<?php echo $lang->kevinclass->index . " > " . $nodeItem->titleCN . "(" . $nodeItem->titleCN . ") > " . $lang->kevinclass->index?> : 
				<?php else :?>
					ALL
				<?php endif;?>
			</div>
			<!--
			<div  class=' pull-right'>
				<table class='table table-form'>
					<tr>
						<?php echo html::hidden('nodeID', "$node->id");?>
						<td class='text-right'><?php echo '关键词：' . html::input('keywords', $itemKeywords, 'class=input') . html::select('type', $lang->kevinclass->searchType, $itemType, 'class=input') . html::submitButton('搜索') . html::backButton();?></td>
					</tr>
				</table>
			</div>
   -->

		</form>
	</div>

	<table class='table table-condensed table-hover tablesorter ' id='nodeList'>
		<thead>
			<tr class='colhead'>
				<th class='w-id'><?php common::printorderlink('id', $orderBy, $vars, $lang->idAB);?></th>
				<th class='w-100px'><?php echo $lang->actions;?></th>
				<th class='w-auto'><?php common::printOrderLink('code', $orderBy, $vars, $lang->kevinclass->code);?></th>
				<th class='w-auto'><?php common::printOrderLink('subtype', $orderBy, $vars, $lang->kevinclass->subtype);?></th>
				<th class='w-auto text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->kevinclass->titleEN);?></th>
				<th class='w-auto text-left'><?php common::printOrderLink('titleCN', $orderBy, $vars, $lang->kevinclass->titleCN);?></th>
				<th class='w-auto'><?php common::printOrderLink('parent', $orderBy, $vars, $lang->kevinclass->parent);?></th>
				<th class='w-auto'><?php common::printOrderLink('path', $orderBy, $vars, $lang->kevinclass->path);?></th>
				<th class='w-auto'><?php common::printOrderLink('grade', $orderBy, $vars, $lang->kevinclass->grade);?></th>
				<th class='w-auto'><?php common::printOrderLink('order', $orderBy, $vars, $lang->kevinclass->order);?></th>
				<th class='w-user'><?php common::printOrderLink('author', $orderBy, $vars, $lang->kevinclass->author);?></th>
				<th class='w-150px'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->kevinclass->addedDate);?></th>
			</tr>
		</thead>
		<tbody>

			<?php
			$canBatchEdit		 = false; //common::hasPriv('user', 'batchEdit');
			$canManageContacts	 = false; //common::hasPriv('user', 'manageContacts');
			$canrowdelete		 = common::hasPriv('kevinclass', 'rowdelete');
			$canUserEdit		 = common::hasPriv('kevinclass', 'rowedit');
			//var_dump($filterList);
			foreach ($itemList as $item):
				if(array_key_exists($item->subtype, $filterList)) $subtype = $filterList[$item->subtype];
				else $subtype = $item->subtype;
				?>
				<tr class='text-center'>
					
					<td>
						<?php
						if ($canBatchEdit or $canManageContacts) echo "<input type='checkbox' name='rowList[]' value='$item->id'> ";
						if (!commonModel::printLink('kevinclass', 'index', "id=$item->id", $item->id)) printf('%03d', $item->id);   
						?>
					</td>
					<td class='text-left'>
						<?php
						commonModel::printIcon('kevinclass', 'index', "id=$item->id", '', 'list', 'list');
						commonModel::printIcon('kevinclass', 'view', "id=$item->id", '', 'list', 'search', '', 'iframe', true);
						if ($canUserEdit) commonModel::printIcon('kevinclass', 'edit', "id=$item->id", '', 'list', 'pencil', '', 'iframe', true);
						if ($canrowdelete) commonModel::printIcon('kevinclass', 'rowdelete', "id=$item->id", '', 'list', 'remove', '', 'iframe', true, "data-width='550'");
						?>
					</td>					
					<td class="text-left"><?php echo $item->code;?></td>
					<td><?php echo $subtype;?></td>
					<td class="text-left"><?php echo $item->title;?></td>
					<td class="text-left"><?php echo $item->titleCN;?></td>
					<td><?php echo $item->parent;?></td>
					<td class="text-left"><?php echo $item->path;?></td>
					<td><?php echo $item->grade;?></td>
					<td><?php echo $item->order;?></td>
					<td><?php echo $item->author;?></td>
					<td><?php echo $item->addedDate;?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='12'>
					<div class='table-actions clearfix'>
						<?php
						if ($canBatchEdit or $canManageContacts) echo "<div class='btn-group'>" . html::selectButton() . '</div>';
						if ($canBatchEdit) echo html::submitButton($lang->edit, 'onclick=batchEdit()', 'btn-default');
						if ($canManageContacts) echo html::submitButton($lang->kevinclass->contacts->manage, 'onclick=manageContacts()');
						?>
					</div>
					<?php echo $pager->show();?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<?php include '../../kevincom/view/footer.html.php';?>