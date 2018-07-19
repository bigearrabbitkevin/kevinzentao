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
include '../../common/view/tablesorter.html.php';
$repedit	 = common::hasPriv('kevinsvn', 'repedit');
//$reparse=common::hasPriv('kevinsvn','reparse');
//$authzparse=common::hasPriv('kevinsvn','authzparse');
$repdelete	 = common::hasPriv('kevinsvn', 'repdelete');
if (!$repid) {
	echo 'Please input rep id.';
	die();
}
?>
<div class='main'>
<?php if (isset($repitem) && $repitem) {//if ($RepSource != 0) {?>
		<div>
			<fieldset>
				<legend><?php echo $lang->kevinsvn->rep . '信息';?></legend>
				<table class='table table-data table-condensed table-borderless'>
					<tr>
						<th class='w-80px'><strong><?php echo $lang->kevinsvn->rep;?></strong></th>
						<td><?php echo sprintf('%03d', $repid);?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinsvn->rep?></strong></th>
						<td><?php
							$showName = ($repitem->name === "")?"(root)":$repitem->name;
							echo html::a($this->config->kevinsvn->ServerLinkPrifix . $repitem->name, $showName, '', "title='{$lang->kevinsvn->rep}' class='btn-icon' target = '_blank'");
							?></td>
						<th class='w-80px'><strong><?php echo $lang->kevinsvn->title;?></strong></th>
						<td><?php echo $repitem->title;?></td>
						<th class='w-80px'><strong><?php echo $lang->actions;?></strong></th>
						<td><?php
//							if($authzparse)commonModel::printIcon('kevinsvn', 'authzparse', "id=$repitem->id", '', 'list', 'lock', '', '', false);
//							if($reparse)commonModel::printIcon('kevinsvn', 'reparse', "id=$repitem->id", '', 'list', 'branch', '', 'iframe', true);
							if ($repedit) commonModel::printIcon('kevinsvn', 'repedit', "repid=" . $repitem->id, '', 'list', 'pencil', '', 'iframe', true);
							if ($repdelete) commonModel::printIcon('kevinsvn', 'repdelete', "repid=" . $repitem->id, '', 'list', 'remove', '', 'iframe', true);
							echo "<button type='button' class='btn-icon' onclick='copy();' id='url' value='".$config->kevinsvn->ServerLinkPrifix.$repitem->name."' title='{$lang->kevinsvn->copysvn}'><i class='icon-copy'></i></button>";
							?>
						</td>
					</tr>
					<tr>
						<th class='w-80px'><strong><?php echo $lang->kevinsvn->project;?></strong></th>
						<td><?php echo $repitem->project;?></td>

						<th class='w-100px'><strong><?php echo $lang->kevinsvn->dept;?></strong></th>
						<td><?php echo isset($depts[$repitem->dept]) ? $depts[$repitem->dept] : '';?></td>

						<th class='w-100px'><strong><?php echo $lang->kevinsvn->charger;?></strong></th>
						<td><?php echo isset($users[$repitem->charger]) ? $users[$repitem->charger] : '';?></td>

						<th class='w-100px'><strong><?php echo $lang->kevinsvn->class;?></strong></th>
						<td><?php echo $repitem->class;?></td>

					</tr>
				</table>
			</fieldset>
		</div>
		<?php
		if ($repitem->disable) {
			echo "<red>This Rep is Disabled!";
			die();
		}
	}
	?>
	<form class='form-condensed' method='post' id="svnauthz">
		<table class='table table-hover table-striped table-bordered table-form ' id='DeviceList'>
			<thead>
				<tr class='colhead'>
<?php $vars		 = "repid=$repid";?>
					<th class='w-100px'><?php echo $lang->kevinsvn->rep;?></th>
					<th class='w-100px'><?php echo $lang->kevinsvn->folder;?></th>
					<th class='w-100px'><?php echo $lang->kevinsvn->account;?></th>
					<th class='w-100px'><?php echo $lang->kevinsvn->authz;?></th>
					<th class='w-200px'><?php echo $lang->actions;?></th>
				</tr>
			</thead>
			<tbody>

				<?php
				$canedit	 = common::hasPriv('kevinsvn', 'repedit');
				$candelete	 = common::hasPriv('kevinsvn', 'repdelete');
				?>
				<?php
				$i			 = 0;
				$borsty		 = "border-bottom:1px solid #DDD;border-right:1px solid #DDD;";
				$usersAll = &$this->kevinsvn->svnUsers->PairsAll;
				foreach ($reprivs as $repname => $dirs): $j			 = 0;
					$reptotal	 = 0;
					if ($repname === '') $flag		 = 'global';
					else $flag		 = $repname;
					echo html::hidden('repname', $flag);
					foreach ($dirs as $diracc)
						$reptotal+=count($diracc);

					foreach ($dirs as $dir => $accauthz): $k = 0;
						foreach ($accauthz as $account => $authz):
							?>
							<tr class='text-center'>
			<?php if ($j === 0) {?><td style='<?php echo $borsty;?>' rowspan='<?php echo $reptotal;?>'><?php echo $repname;?></td><?php }?>
			<?php if ($k === 0) {?><td style='<?php echo $borsty;?>' rowspan='<?php echo count($reprivs[$repname][$dir]);?>'><?php echo ($dir === 'input') ? (($repname === '') ? '' : html::input('addir', '/', "class='form-control'")) : $dir;?></td><?php }?>
								<td style='<?php echo $borsty;?>'><?php echo ($account === 'select') ? html::select("addacc", $users, '', "class='form-control chosen'") : ((isset($usersAll[$account])) ? $usersAll[$account] : $account);?></td>
								<td style='<?php echo $borsty;?>'><?php echo (isset($lang->kevinsvn->authzEnum[$authz->authz])) ? $lang->kevinsvn->authzEnum[$authz->authz] : '';?></td>
								<td style="border-bottom:1px solid #DDD;">
									<?php
									echo html::radio("authzs[$flag][$dir][$account]", $lang->kevinsvn->authzEnum, $authz->authz);
									$uniquestr = $flag . $dir . $account;
									$i++;
									$j++;
									$k++;
									?>&nbsp
									<?php
									if (common::hasPriv('kevinsvn', 'authzdelete') && isset($authz->authzid)) echo common::printIcon('kevinsvn', 'authzdelete', "authzid=$authz->authzid", '', '', 'remove', '', 'iframe', true, '', '');
									//if(common::hasPriv('kevinsvn','authzedit'))common::printIcon('kevinsvn', 'authzedit', "authid=$repinfo->id", '', '', 'lock','','iframe',true,'','');
									if (isset($authz->authzid)) echo html::hidden("authzids[$uniquestr]", $authz->authzid);
									?>
								</td>
							</tr>
							<?php
						endforeach;
					endforeach;
				endforeach;
				?>
			</tbody>
			<tfoot>
				<tr>
					<td><?php echo html::submitButton();?></td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
<script>
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