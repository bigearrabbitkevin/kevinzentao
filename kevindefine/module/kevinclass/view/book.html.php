<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/sparkline.html.php';?>
<?php css::import($defaultTheme . 'index.css', $config->version);?>
<div class='row'>
	<div class='col-md-3'>
		<nav class='booksNav'>
			<ul class='nav nav-primary nav-stacked'>
				<li class='nav-heading'><strong><?php echo $lang->kevinclass->book;?></strong></li>
				<?php
				if (!empty($books)) {
					foreach ($books as $menu) {
						echo '<li' . (($menu->title == $nodeItem->title) ? " class='active'" : '') . '>' . html::a(inlink('book', "bookID=$menu->id"), '<i class="icon-book"></i> &nbsp;' . $menu->titleCN) . '</li>';
					}
				}
				?>
			</ul>
		</nav>
	</div>
	<div class='col-md-9'>
		<div class='panel panel-block'>
			<?php if (!empty($nodeItem)) :?>
				<div class='panel-heading'>
					<strong class='title'><?php echo $lang->kevinclass->book . " > " . $nodeItem->titleCN . "(" . $nodeItem->titleCN . ") > " . $lang->kevinclass->sheet?> : </strong>
				</div>
			<?php endif;?>
			<div class='panel-body'>
				<div  class='books'>
					<table width = '100%'>
						<thead>
							<tr class='colhead'>
								<th class='w-id'><?php echo $lang->idAB;?></th>
								<th class='w-100px'><?php echo $lang->actions;?></th>
								<th class='w-auto'><?php echo $lang->kevinclass->code;?></th>
								<th class='w-auto'><?php echo $lang->kevinclass->titleCN;?></th>
								<th class='w-user'><?php echo  $lang->kevinclass->author;?></th>
								<th class='w-150px'><?php echo  $lang->kevinclass->addedDate;?></th>
							</tr>
						</thead>
						<?php
						$canBatchEdit		 = false; //common::hasPriv('user', 'batchEdit');
						$canManageContacts	 = false; //common::hasPriv('user', 'manageContacts');
						$canrowdelete		 = common::hasPriv('kevinclass', 'rowdelete');
						$canUserEdit		 = common::hasPriv('kevinclass', 'rowedit');
						$serial = 1;
						//var_dump($filterList);
						foreach ($itemList as $item):
							?>
							<tr class='text-center'>
								<td class='text-left'>
									<?php echo $serial; $serial+=1;?>
								</td>	
								<td class='text-left'>
									<?php
									commonModel::printIcon('kevinclass', 'book', "id=$item->id", '', 'list', 'list');
									commonModel::printIcon('kevinclass', 'view', "id=$item->id", '', 'list', 'search', '', 'iframe', true);
									if ($canUserEdit) commonModel::printIcon('kevinclass', 'edit', "id=$item->id", '', 'list', 'pencil', '', 'iframe', true);
									if ($canrowdelete) commonModel::printIcon('kevinclass', 'rowdelete', "id=$item->id", '', 'list', 'remove', '', 'iframe', true, "data-width='550'");
									?>
								</td>					
								<td class="text-left"><?php echo $item->code;?></td>
								<td class="text-left"><?php echo $item->titleCN;?></td>
								<td><?php echo $item->author;?></td>
								<td><?php echo $item->addedDate;?></td>
							</tr>
						<?php endforeach;?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>  
<?php include '../../kevincom/view/footer.html.php';?>

