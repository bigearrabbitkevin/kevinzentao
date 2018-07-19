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
$repdelete	 = common::hasPriv('kevinsvn', 'repdelete');
$repcreate	 = common::hasPriv('kevinsvn', 'repcreate');
$repsync	 = common::hasPriv('kevinsvn', 'repsync');
$authz		 = common::hasPriv('kevinsvn', 'authz');
$isAdmin	 = ($this->kevinsvn->svnUser->type == 'admin');

if ($reptype != 'my' && !$isAdmin) $reptype = "my";

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
		foreach ($lang->kevinsvn->reptypes as $type => $label) {
			if ($type != 'my' && !$isAdmin) continue;
			echo "<li data-id=$type " . (($reptype === $type) ? "class='active'" : '') . '>' . html::a(inLink('index', "reptype=$type"), $label, '', "") . '</li>';
		}
		?>
	</ul>
    <div class='actions'>
		<?php
		if ($repcreate) echo html::a(helper::createLink('kevinsvn', 'repcreate', '', '', true), "<i class='icon-plus'></i> " . $lang->kevinsvn->repcreate, '', "data-toggle='modal' data-type='iframe' class='btn'");
		if ($repsync) echo html::a(helper::createLink('kevinsvn', 'repsync', '', '', false), "<i class='icon-refresh'></i> " . $lang->kevinsvn->repsync, '', "class='btn'");
		?>
    </div>
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
					func_SearchItem("name", $lang->kevinsvn->folder, $FilterList->name);
					func_SearchItem("title", $lang->kevinsvn->title, $FilterList->title);
					func_SearchItem("project", $lang->kevinsvn->project, $FilterList->project);
					if ($reptype != 'my' && $isAdmin)  func_SearchItem("charger", "负责", $FilterList->charger, $chargers);
					?>
					<tr>
						<td class='text-center' colspan="2"><?php echo html::submitButton('Search');?></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<div class='main' style="overflow:auto; ">
	<table class='table table-condensed table-hover table-striped tablesorter' id='DeviceList'>
		<thead>
			<tr class='colhead'>
				<?php $vars		 = "reptype=$reptype&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
				<th class='w-id'><?php common::printorderlink('id', $orderBy, $vars, $lang->idAB);?></th>
				<th class='w-auto text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->kevinsvn->name);?></th>
				<th class='w-auto text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->kevinsvn->title);?></th>
				<th class='w-auto text-left'><?php common::printOrderLink('project', $orderBy, $vars, $lang->kevinsvn->project);?></th>
				<th class='w-auto text-left'><?php common::printOrderLink('dept', $orderBy, $vars, $lang->kevinsvn->dept);?></th>
				<th class='w-80px text-left'><?php common::printOrderLink('charger', $orderBy, $vars, $lang->kevinsvn->charger);?></th>
				<th class='w-100px text-left'><?php common::printOrderLink('description', $orderBy, $vars, $lang->kevinsvn->description);?></th>
				<th class='w-100px text-left'><?php common::printOrderLink('class', $orderBy, $vars, $lang->kevinsvn->class);?></th>
				<th class='w-100px'><?php echo $lang->actions;?></th>
			</tr>
		</thead>
		<tbody>

			<?php
			$canedit	 = common::hasPriv('kevinsvn', 'repedit');
			$candelete	 = common::hasPriv('kevinsvn', 'repdelete');
			?>
			<?php
			$this->kevinsvn->getAccountPairs();
			foreach ($replist as $repinfo):
				$RealName = $repinfo->realname;
				if(!$RealName && array_key_exists($repinfo->charger, $this->kevinsvn->svnUsers->PairsAll)){
					$RealName = $this->kevinsvn->svnUsers->PairsAll[$repinfo->charger];
				}
				?>
				<tr class='text-center'>
					<td <?php echo ($repinfo->disable) ? "style='background:red;'" : '';?>><?php echo $repinfo->id;?></td>
					<td class="text-left"><?php echo $repinfo->name;?></td>
					<td class="text-left"><?php echo $repinfo->title;?></td>
					<td class="text-left"><?php if($repinfo->project)echo "[$repinfo->project]$repinfo->ProjectName";?></td>
					<td class="text-left"><?php echo $repinfo->DeptName;?></td>
					<td class="text-left"><?php echo $RealName;?></td>
					<td class="text-left"><?php echo $repinfo->description;?></td>
					<td class="text-left"><?php echo $repinfo->class;?></td>
					<!--<td><?php // echo $repinfo->disable;?></td>-->
					<?php if ($repedit || $repdelete):?>
						<td class='text-left'>
							<?php
							echo ($repinfo->disable) ? "<button class='disabled btn-icon' ><i class='icon-search disabled'></i></button>" : (($authz) ? common::printIcon('kevinsvn', 'authz', "repid=$repinfo->id", '', '', 'search', '', 'iframe', true) : '');
							echo ($repinfo->disable) ? "<button class='disabled btn-icon' ><i class='icon-lock disabled'></i></button>" : (($authz) ? common::printIcon('kevinsvn', 'authz', "repid=$repinfo->id", '', '', 'lock', '', '', false) : '');
//					if($reparse)common::printIcon('kevinsvn', 'reparse', "repid=$repinfo->id", '', '', 'branch','','iframe',true);
							echo ($repinfo->disable) ? "<button class='disabled btn-icon' ><i class='icon-pencil disabled'></i></button>" : (($repedit) ? common::printIcon('kevinsvn', 'repedit', "repid=$repinfo->id", '', '', 'pencil', '', 'iframe', true) : '');
							echo ( $repinfo->name == "") ? "<button class='disabled btn-icon' ><i class='icon-remove disabled'></i></button>" : (($repdelete) ? common::printIcon('kevinsvn', 'repdelete', "repid=$repinfo->id", '', '', 'remove', '', 'iframe', true, "data-width='550'") : '');
							echo "<button type='button' class='btn-icon' onclick='copy();' id='url' value='".$this->config->kevinsvn->ServerLinkPrifix.$repinfo->name."' title='{$lang->kevinsvn->copysvn}'><i class='icon-copy'></i></button>";
							?>
						</td>
					<?php endif;?>
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
<?php include '../../kevincom/view/footer.html.php';?>
<script type="text/javascript">
function copy(){
	var cpTxt=$('#url').val();
	var clipobj=window.clipboardData;
	if(clipobj){
		clipobj.clearData();
		clipobj.setData('Text',cpTxt);
	}else if(navigator.userAgent.indexOf("Opera")!=-1){
		window.location=cpTxt;
	}else if(window.netscape){
		try{
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		}catch(e){
			alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
		}
		var clip=Components.classes['@mozilla.org/widget/clipboard;l'].createInstance(Components.interfaces.nsIClipboard);
		if(!clip)return;
		var trans=Components.classes['@mozilla.org/widget/transferable;l'].createInstance(Components.interfaces.nsITransferable);
		if(!trans)return;
		trans.addDataFlavor("text/unicode");
		var str=new Object();
		var len=new Object();
		var str=Components.classes["@mozilla.org/supports-string;l"].createInstance(Components.interfaces.nsISupportsString);
		var copytext=cpTxt;
		str.data=copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid=Components.interfaces.nsIClipboard;
		if(!clip)return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
	}
}
</script>