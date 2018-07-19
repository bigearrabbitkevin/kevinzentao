<?php
/**
 * The browse user view file of kevinsvn module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinsvn
 */
?>
<?php
include '../../kevincom/view/header.html.php';
$repedit	 = common::hasPriv('kevinsvn', 'repedit');
//$reparse=common::hasPriv('kevinsvn','reparse');
//$authzparse=common::hasPriv('kevinsvn','authzparse');
$repdelete	 = common::hasPriv('kevinsvn', 'repdelete');
$isAdmin	 = ($this->kevinsvn->svnUser->type == 'admin');

function func_SearchItem($name, $title, $value, $List = "") {
	echo "<tr><th class='text-left nobr'>";
	if ($value) {
		echo "<a href=\"javascript:onRemoveSearchChoice('$name', '');\" class=\"\" title='Delete'>$title</a></th>";
	} else echo $title;
	echo "<td style='max-width:150px'>";
	if ($name == "charger") {
		echo html::select('charger', $List, $value, "class='form-control chosen'");
	} else echo html::input($name, $value, "class='form-control'");
	echo "</td></tr>";
}
?>
<div id='featurebar'>
	<ul class="nav">
		<?php
		foreach ($lang->kevinsvn->reptypes as $key => $label) {
			if ($key != 'my' && !$isAdmin) continue;
			echo "<li data-id=$key " . (($key === $type) ? "class='active'" : '') . '>' . html::a(inLink('authzlist', "type=$key"), $label, '', "") . '</li>';
		}
		?>
	</ul>
</div>

<div class='side'>
	<a class='side-handle' data-id='kevinsvnTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?> <strong><?php echo $lang->kevinsvn->filter;?></strong></div>
			<form id='searchform' method="post" class='form-condensed'  style="height:370px;">
				<?php echo html::hidden('postType', 'searchform');?>
				<table class='table table-form'>
					<?php
					func_SearchItem("name", $lang->kevinsvn->rep, $FilterList->name);
					if ($type != 'my' && $isAdmin) func_SearchItem("charger", "用户", $FilterList->charger, $this->kevinsvn->svnUsers->PairsAll);
					?>
					<tr>
						<td class='text-center' colspan="2"><?php echo html::submitButton('Search');?></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<div class='main'>
	<table class='table table-condensed table-hover table-striped tablesorter' id='authzList'>
		<thead>
			<tr class='text-left'>
				<?php $vars		 = "type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
				<th class='w-id'><?php common::printOrderLink('a.id', $orderBy, $vars, $lang->kevinsvn->id);?></th>
				<th class='w-auto'><?php common::printOrderLink('a.rep', $orderBy, $vars, $lang->kevinsvn->rep);?></th>
				<th class='w-auto'><?php common::printOrderLink('a.folder', $orderBy, $vars, $lang->kevinsvn->folder);?></th>
				<th class='w-auto'><?php common::printOrderLink('a.user', $orderBy, $vars, $lang->kevinsvn->account);?></th>
				<th class='w-auto'><?php common::printOrderLink('a.authz', $orderBy, $vars, $lang->kevinsvn->authz);?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$canedit	 = common::hasPriv('kevinsvn', 'repedit');
			$candelete	 = common::hasPriv('kevinsvn', 'repdelete');

			$users = &$this->kevinsvn->svnUsers->PairsAll;
			foreach ($authzList as $item):
				?>
				<tr class='text-left'>
					<td><?php echo $item->id;?></td>
					<td><?php
						common::printIcon('kevinsvn', 'authz', "repid=$item->rep", '', '', 'list', '', '', false);
						common::printIcon('kevinsvn', 'authz', "repid=$item->rep", '', '', 'search', '', 'iframe', true);
						echo "[$item->rep]$item->name";
						?></td>
					<td><?php echo $item->folder;?></td>
					<td ><?php echo ((isset($users[$item->user])) ? $users[$item->user] : $item->user);?></td>
					<td><?php echo $lang->kevinsvn->authzEnum[$item->authz];?></td>
				</tr>
				<?php
			endforeach;
			?>
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
<?php include '../../kevincom/view/footer.html.php';?>
