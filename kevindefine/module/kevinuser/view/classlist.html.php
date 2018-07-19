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
		if (common::hasPriv('kevinuser', 'classcreate')) echo html::a($this->createLink('kevinuser', 'classcreate', "", '', true)
				, "<i class='icon-plus'></i>" . $lang->kevinuser->classcreate, '', "data-toggle='modal' data-type='iframe' data-icon='check'");
		?>
    </div>
</div>
<div class='side'>
    <a class='side-handle' data-id='gradeTree'><i class='icon-caret-left'></i></a>
    <div class='side-body'>
        <div class='panel panel-sm'>
            <div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?> <strong>
					<?php common::printLink('kevinuser', 'classlist', "", $lang->kevinuser->classFilter);?></strong>
            </div>
            <div style="min-height:260px">
				<?php include 'classfilter.html.php';?>
            </div>
            <div style="height:100px">
            </div>
        </div>
    </div>
</div>
<div class='main'>
    <form method='post' id='classForm'>
        <table class='table table-condensed  table-hover table-striped tablesorter ' id='KevinValueList'>
            <thead>
                <tr class='text-center' height=35px>
					<?php $vars = "orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";?>
                    <th class='text-center w-auto'><?php common::printOrderLink('id', $orderBy, $vars, $lang->kevinuser->id);?></th>
                    <th class='text-center w-auto'><?php echo $lang->kevinuser->oprater;?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('role', $orderBy, $vars, $lang->kevinuser->role);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('classify1', $orderBy, $vars, $lang->kevinuser->classify1);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('classify2', $orderBy, $vars, $lang->kevinuser->classify2);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('classify3', $orderBy, $vars, $lang->kevinuser->classify3);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('classify4', $orderBy, $vars, $lang->kevinuser->classify4);?></th>

                    <th class='text-center w-auto'><?php common::printOrderLink('payrate', $orderBy, $vars, $lang->kevinuser->payrate);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('hourFee', $orderBy, $vars, $lang->kevinuser->hourFee);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('conversionFee', $orderBy, $vars, $lang->kevinuser->conversionFee);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('start', $orderBy, $vars, $lang->kevinuser->start);?></th>
                    <th class='text-center w-auto'><?php common::printOrderLink('end', $orderBy, $vars, $lang->kevinuser->end);?></th>

                    <th class='text-center w-auto'><?php common::printOrderLink('classname', $orderBy, $vars, $lang->kevinuser->classname);?></th>
                </tr>
            </thead>
			<?php
			foreach ($classList as $item):
				?>
				<tr>
					<td class='text-center'><input name="classIDList[]" value="<?php echo $item->id;?>" type="checkbox"> <?php printf('%03d', $item->id);?></td>
					<td class='text-center'>
						<?php
						if (common::hasPriv('kevinuser', 'classview')) echo html::a($this->createLink('kevinuser', 'classview', "id=$item->id", '', true), "<i class='icon-search'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='search'");
						if (common::hasPriv('kevinuser', 'classedit')) echo html::a($this->createLink('kevinuser', 'classedit', "id=$item->id", '', true), "<i class='icon-pencil'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='pencil'");
						if (common::hasPriv('kevinuser', 'classdelete')) echo html::a($this->createLink('kevinuser', 'classdelete', "id=$item->id", '', true), "<i class='icon-remove'></i>"
								, 'hiddenwin', "data-icon='remove'");
						if (common::hasPriv('kevinuser', 'classcreate')) echo html::a($this->createLink('kevinuser', 'classcreate', "id=$item->id", '', true), "<i class='icon-copy'></i>"
								, '', "data-toggle='modal' data-type='iframe' data-icon='copy'");
						?>
					</td>
					<td class='text-center'><?php echo $item->role;?></td>
					<td class='text-center'><?php echo $item->classify1;?></td>
					<td class='text-center'><?php echo $item->classify2;?></td>
					<td class='text-center'><?php echo $item->classify3;?></td>
					<td class='text-center'><?php echo $item->classify4;?></td>
					<td class='text-center'><?php echo $item->payrate * 100;?>%</td>
					<td class='text-center'><?php echo $item->hourFee;?></td>
					<td class='text-center'><?php echo $item->conversionFee;?></td>
					<td class='text-center'><?php echo $item->start;?></td>
					<td class='text-center'><?php echo $item->end;?></td>
					<td class='text-center'><?php echo $item->classname . "(" . $item->id . ")";?></td>
				</tr>
			<?php endforeach;?>
            <tfoot>
                <tr>
					<?php if (!isset($columns)) $columns		 = ($this->cookie->windowWidth > $this->config->wideSize ? 16 : 16);?>
                    <td colspan='<?php echo $columns;?>'>
                        <div class='table-actions clearfix'>
							<?php
							$canBatchDelete	 = common::hasPriv('kevinuser', 'classBatchDelete');
							$canBatchEdit	 = common::hasPriv('kevinuser', 'classBatchEdit');

							if (count($classList)) {
								echo html::selectButton();
								echo "<div class='btn-group'>";
								$actionLink	 = $this->createLink('kevinuser', 'classBatchDelete');
								$misc		 = $canBatchDelete ? "onclick=\"setFormAction('$actionLink','hiddenwin',this)\"" : "disabled='disabled'";
								echo html::commonButton($lang->kevinuser->batchdelete, $misc);
								$actionLink	 = $this->createLink('kevinuser', 'classBatchEdit');
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