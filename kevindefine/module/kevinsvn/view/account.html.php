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
$dept=$this->app->user->dept;
?>
<div id='featurebar'>
	<div class='actions'>
		<?php
		echo html::a(helper::createLink('kevinsvn', 'account','repid=0','',false), "<i class='icon-upload'></i> " . $lang->kevinsvn->accountimport, '', "class='btn'");
		?>
	</div>
</div>
<div class="text-left">
	<div class='mw-1000px text-left'>
		<form class='form-condensed' method='post' target='hiddenwin'>
			<table align='center' class='table table-form' id='pwdList'>
				<thead>
					<tr class='text-center'>
						<th class='w-50px'><?php echo $lang->kevinsvn->id; ?></th>
						<th class='w-150px'><?php echo $lang->kevinsvn->dept; ?></th>
						<th class='w-150px'><?php echo $lang->kevinsvn->account; ?></th>
						<th class='w-200px'><?php echo $lang->kevinsvn->svnaccount; ?></th>
						<th class='w-600px'><?php echo $lang->kevinsvn->windowsID; ?></th>
						<th class='w-300px'><?php echo $lang->kevinsvn->type; ?></th>
						<th class='w-id'><?php echo $lang->actions; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i			 = -10;
					$index		 = 0;
					$pagid		 = $pager->pageID;
					$indexRec	 = ($pager->pageID - 1) * $pager->recPerPage + 1;
					foreach ($accounts as $tempId => $user):$i+=1;
						$index+=1;
						$bgstyle='';
						$nowrite='';
						if($user->disable){
							$bgstyle="style=background:red;";
							$nowrite="readonly='readonly'";
						}
						?>
						<tr class='text-center'>
							<td class=' w-id' <?php echo $bgstyle;?>><?php
								echo $user->id;
								$indexRec++;
								?></td>
							<td class='w-id'><?php echo $depts[$user->dept] . html::hidden("udept[$user->id]", $user->dept); ?></td>
							<td class='w-id'><?php echo $users[$user->account] . html::hidden("uaccount[$user->id]", $user->account); ?></td>
							<td class='w-id'>
								<?php echo html::input("usvnaccount[$user->id]", $user->svnaccount, "class='form-control' $nowrite"); ?>
							</td>
							<td class='w-id'>
								<?php echo html::input("uwindowsID[$user->id]", $user->windowsID, "class='form-control' $nowrite"); ?>
							</td>
							<td class=' w-id'>
								<?php echo (($user->disable)?html::input("utype[$user->id]",$lang->kevinsvn->typelist[$user->type],"class='form-control' $nowrite"):html::select("utype[$user->id]",$lang->kevinsvn->typelist,$user->type,"class='form-control'")). html::hidden("uidList[$user->id]", $user->id); ?>
							</td>
							<td class='w-id'>
								<?php // echo ($user->disable)?"<button class='disabled btn-icon' type='button'><i class='icon-remove disabled icon-delete'></i></button>":common::printIcon('kevinsvn', 'accountdelete', "id=$user->id", '', 'list', 'remove', 'hiddenwin'); ?>
								<?php echo ($user->disable)?common::printIcon('kevinsvn', 'accountdelete', "id=$user->id", '', 'list', 'refresh', 'hiddenwin'):common::printIcon('kevinsvn', 'accountdelete', "id=$user->id", '', 'list', 'remove', 'hiddenwin'); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					<?php if (count($accounts)): ?>			
						<tr>
							<td colspan='8' align='left'>
								<?php $pager->show(); ?>							
							</td>
						</tr>	
					<?php endif; ?>	
					<?php foreach ($winIDs as $j => $winID) {	?>
						<tr class='text-center'>
							<td class='text-center'><i class="icon-plus-sign"></i></td>
							<td class=' w-id'>
								<?php echo html::select("cdept[$j]",$depts, $dept, "class='form-control chosen'") ; ?>
							</td>
							<td class=' w-id'>
								<?php echo html::select("caccount[$j]",$users,'', "class='form-control chosen'") ; ?>
							</td>		
							<td class=' w-id'>
								<?php echo html::input("csvnaccount[$j]", '', "class='form-control'"); ?>
							</td>
							<td class=' w-id'>
								<?php echo html::input("cwindowsID[$j]", $winID, "class='form-control'"); ?>
							</td>
							<td class=' w-id'>
								<?php echo  html::select("ctype[$j]",$lang->kevinsvn->typelist,'normal',"class='form-control'"); ?>
							</td>
						</tr>
					<?php } ?>
					<tr class='text-center'>
						<td colspan="8"><?php echo html::submitButton() . html::backButton(); ?></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
