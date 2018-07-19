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
<?php include '../../kevincom/view/header.html.php';?>
<div id='featurebar'>
    <div class='heading'><?php echo html::icon($lang->icons['group']);?> <?php echo $title;?></div>
    <div class='actions'>
		<?php
		if (common::hasPriv('kevinuser', 'deptcreate')) echo html::a($this->createLink('kevinuser', 'deptcreate', "", '', true)
				, "<i class='icon-plus'></i>" . $lang->kevinuser->deptcreate, '', "data-toggle='modal' data-type='iframe' data-icon='check'");
		?>
    </div>
</div>
<div class='side'>
    <a class='side-handle' data-id='gradeTree'><i class='icon-caret-left'></i></a>
    <div class='side-body'>
        <div class='panel panel-sm'>
            <div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?> <strong>
					<?php common::printLink('kevinuser', 'deptlist', "", $lang->kevinuser->deptFilter);?></strong>
            </div>
            <div style="min-height:220px">
				<?php include 'deptfilter.html.php';?>
            </div>
            <div style="height:100px">
            </div>
        </div>
    </div>
</div>
<div class='main'>
	<?php if(!empty($path)): ?>
	<fieldset style="min-height:100px;padding-top:0px;padding-bottom:0px;">
		<legend><h4><?php echo $lang->kevinuser->deptinfo?></h4></legend>
		<table class="table-borderless" cellspacing="0" cellpadding="0"> 
			<tbody>
				<tr>
					<th class="text-left w-120px"><?php echo $lang->kevinuser->deptName?></th>
					<td id="materialfilter" class="w-400px"><?php echo $deptName?></td>
					<th class="text-left w-120px"><?php echo $lang->kevinuser->deptParent?></th>
					<td id="materialfilter" class="w-400px"><?php echo $deptParent;?></td>
					<th class="text-left w-120px"><?php echo $lang->kevinuser->deptgroup?></th>
					<td id="materialfilter" class="w-400px"><?php  echo $deptGroup;	?></tr>	
			</tbody></table>
	</fieldset>
	<?php endif;?>
    <form method='post' id='deptForm'>
        <table class='table table-condensed  table-hover table-striped tablesorter ' id='KevinValueList'>
            <thead>
                <tr class='text-center' height=35px>
					<?php $vars = "path=$path&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
                    <th class='text-center w-auto'><?php common::printOrderLink('id', $orderBy, $vars, $lang->kevinuser->id);?></th>
                    <th class='text-center w-auto'><?php echo $lang->kevinuser->oprater;?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('name', $orderBy, $vars, $lang->kevinuser->deptName);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('parent', $orderBy, $vars, $lang->kevinuser->deptParent);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('path', $orderBy, $vars, $lang->kevinuser->path);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('order', $orderBy, $vars, $lang->kevinuser->order);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('manager', $orderBy, $vars, $lang->kevinuser->manager);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('email', $orderBy, $vars, $lang->kevinuser->email);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('code', $orderBy, $vars, $lang->kevinuser->code);?></th>
					<?php if(isset($group)):?>
					 <th class='text-center w-auto'><?php echo $lang->kevinuser->deptgroup;?></th>
					 <?php	endif;?>
                </tr>
            </thead>
			<?php
			foreach ($deptList as $item):
				?>
				<tr>
					<td class='text-center'><input name="deptIDList[]" value="<?php echo $item->id;?>" type="checkbox"> <?php printf('%03d', $item->id);?></td>
					<td class='text-center'>
						<?php
						if (common::hasPriv('kevinuser', 'deptview')) echo html::a($this->createLink('kevinuser', 'deptview', "id=$item->id", '', true), "<i class='icon-search'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='search'");
						if (common::hasPriv('kevinuser', 'deptedit')) echo html::a($this->createLink('kevinuser', 'deptedit', "id=$item->id", '', true), "<i class='icon-pencil'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='pencil'");
						if (common::hasPriv('kevinuser', 'deptdelete')) echo html::a($this->createLink('kevinuser', 'deptdelete', "id=$item->id", '', true), "<i class='icon-remove'></i>"
								, 'hiddenwin', "data-icon='remove'");
						if (common::hasPriv('kevinuser', 'deptcreate')) echo html::a($this->createLink('kevinuser', 'deptcreate', "id=$item->id", '', true), "<i class='icon-copy'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='copy'");
						?>
					</td>
					<td class='text-center'><?php echo html::a($this->createLink('kevinuser', 'deptlist', 'path=' . $item->id), $item->name);?></td>
					<td class='text-center'><?php echo html::a($this->createLink('kevinuser', 'deptlist', 'path=' . $item->parent),!empty($item->parentName) ? $item->parentName : $lang->kevinuser->topParent);?>(<?php echo $item->parent;?>)</td>
					<td class='text-center'><?php echo $item->path;?></td>
					<td class='text-center'><?php echo $item->order;?></td>
					<td class='text-center'><?php echo !empty($item->realname)?$item->realname:$item->manager;?></td>
					<td class='text-center'><?php echo $item->email;?></td>
					<td class='text-center'><?php echo $item->code;?></td>
					<?php if(isset($group)):?>
					<td class='text-center'>
						<?php
						if(!empty($item->group)){
							$groupArray = explode(',', trim($item->group, ','));
							foreach ($groupArray as $group)
								echo $groups[$group].',';
						}
						?>
					</td>
					<?php	endif;?>
				</tr>
<?php endforeach;?>
            <tfoot>
                <tr>
			<?php if (!isset($columns)) $columns		 = ($this->cookie->windowWidth > $this->config->wideSize ? 16 : 16);?>
                    <td colspan='<?php echo $columns;?>'>
                        <div class='table-actions clearfix'>
					<?php
					$canBatchDelete	 = common::hasPriv('kevinuser', 'deptBatchDelete');
					$canBatchEdit	 = common::hasPriv('kevinuser', 'deptBatchEdit');

					if (count($deptList)) {
						echo html::selectButton();
						echo "<div class='btn-group'>";
						$actionLink	 = $this->createLink('kevinuser', 'deptBatchDelete');
						$misc		 = $canBatchDelete ? "onclick=\"setFormAction('$actionLink','hiddenwin',this)\"" : "disabled='disabled'";
						echo html::commonButton($lang->kevinuser->batchdelete, $misc);
						$actionLink	 = $this->createLink('kevinuser', 'deptBatchEdit');
						$misc		 = $canBatchEdit ? "onclick=\"setFormAction('$actionLink',null,this)\"" : "disabled='disabled'";
						echo html::commonButton($lang->kevinuser->batchedit, $misc);
						echo "</div>";
					}
					?>
                        </div>
							<?php $pager->show();?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>