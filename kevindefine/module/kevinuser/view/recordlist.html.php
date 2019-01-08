<?php
/**
 * The view file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinuser
 * @link        http://www.zentao.net
 */
?>
<?php include '../../kevincom/view/header.html.php';?>
<div id='featurebar'>
    <div class='heading'><?php echo html::icon($lang->icons['group']);?> <?php echo $title;?></div>
    <div class='actions'>
		<?php
		if (common::hasPriv('kevinuser', 'recordcreate')) echo html::a($this->createLink('kevinuser', 'recordcreate', "", '', true)
				, "<i class='icon-plus'></i>" . $lang->kevinuser->recordcreate, '', "data-toggle='modal' data-type='iframe' data-icon='check'");
		?>
    </div>
</div>
<div class='side'>
    <a class='side-handle' data-id='gradeTree'><i class='icon-caret-left'></i></a>
    <div class='side-body'>
        <div class='panel panel-sm'>
            <div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?> <strong>
					<?php common::printLink('kevinuser', 'recordlist', "", $lang->kevinuser->recordFilter);?></strong>
            </div>
            <div style="min-height:220px">
				<?php include 'recordfilter.html.php';?>
            </div>
            <div style="height:100px">
            </div>
        </div>
    </div>
</div>
<div class='main'>
    <form method='post' id='recordForm'>
        <table class='table table-condensed  table-hover table-striped tablesorter ' id='KevinValueList'>
            <thead>
                <tr class='text-center' height=35px>
					<?php $vars = "account=$account&dept=$dept&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
                    <th class='text-center w-auto'><?php common::printOrderLink('id', $orderBy, $vars, $lang->kevinuser->id);?></th>                   
                    <th class='text-center w-auto'><?php echo $lang->kevinuser->oprater;?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('account', $orderBy, $vars, $lang->kevinuser->account);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('dept', $orderBy, $vars, $lang->kevinuser->dept);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('start', $orderBy, $vars, $lang->kevinuser->start);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('end', $orderBy, $vars, $lang->kevinuser->end);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('class', $orderBy, $vars, $lang->kevinuser->class);?></th>
                </tr>
            </thead>
			<?php
			foreach ($recordList as $item):
				?>
				<tr>
					<td class='text-center'><input name="recordIDList[]" value="<?php echo $item->id;?>" type="checkbox"> <?php printf('%03d', $item->id);?></td>
					<td class='text-center'>
						<?php
						if (common::hasPriv('kevinuser', 'recordview')) echo html::a($this->createLink('kevinuser', 'recordview', "id=$item->id", '', true), "<i class='icon-search'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='search'");
						if (common::hasPriv('kevinuser', 'recordedit')) echo html::a($this->createLink('kevinuser', 'recordedit', "id=$item->id", '', true), "<i class='icon-pencil'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='pencil'");
						if (common::hasPriv('kevinuser', 'recorddelete')) echo html::a($this->createLink('kevinuser', 'recorddelete', "id=$item->id", '', true), "<i class='icon-remove'></i>"
								, 'hiddenwin', "data-icon='remove'");
						if (common::hasPriv('kevinuser', 'recordcreate')) echo html::a($this->createLink('kevinuser', 'recordcreate', "id=$item->id", '', true), "<i class='icon-copy'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='copy'");
						?>
					</td>
					<td class='text-center'><?php echo html::a($this->createLink('kevinuser', 'recordlist', 'account=' . $item->account, ''), $item->realname.'('.$item->account.')'	, '', "");?></td>
					<td class='text-center'><?php echo html::a($this->createLink('kevinuser', 'recordlist', 'account=&dept=' . $item->dept, ''), $item->name.'('. $item->dept.')'	, '', "");?></td>
					<td class='text-center'><?php echo $item->start;?></td>
					<td class='text-center'><?php echo $item->end;?></td>
					<td class='text-center'><?php echo $item->classname."(".$item->class.")";?></td>
				</tr>
			<?php endforeach;?>
            <tfoot>
                <tr>
					<?php if (!isset($columns)) $columns		 = ($this->cookie->windowWidth > $this->config->wideSize ? 16 : 16);?>
                    <td colspan='<?php echo $columns;?>'>
                        <div class='table-actions clearfix'>
							<?php
							$canBatchDelete	 = common::hasPriv('kevinuser', 'recordBatchDelete');
							$canBatchEdit	 = common::hasPriv('kevinuser', 'recordBatchEdit');

							if (count($recordList)) {
								echo html::selectButton();
								echo "<div class='btn-group'>";
								$actionLink	 = $this->createLink('kevinuser', 'recordBatchDelete');
								$misc		 = $canBatchDelete ? "onclick=\"setFormAction('$actionLink','hiddenwin',this)\"" : "disabled='disabled'";
								echo html::commonButton($lang->kevinuser->batchdelete, $misc);
								$actionLink	 = $this->createLink('kevinuser', 'recordBatchEdit');
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