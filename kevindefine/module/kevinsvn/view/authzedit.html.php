<?php
/**
 * The view file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinsvn
 * @link        http://www.zentao.net
 */
?>
<?php include '../../kevinhours/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='id'><?php echo $lang->kevinsvn->project . " > " . $lang->kevinsvn->authzedit . " > ";?> <strong><?php echo $authzItem->id;?></strong></span>
	</div>
</div>
<div class='row-table'>
	<form class='form' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
		<table class='table table-form table-borderless' width = '100%' cellpadding="5"> 
			<tr>
				<th class='w-name nobr'><?php echo $lang->kevinsvn->rep;?></th>
				<td class='w-auto'><?php echo $authzItem->name;?></td>	
			</tr> 
			<tr>
				<th class='w-name nobr'><?php echo $lang->kevinsvn->folder;?></th>
				<td class='w-auto'><?php echo $authzItem->folder;?></td>
			</tr>
			<tr>
				<th class='w-name nobr'><?php echo $lang->kevinsvn->account;?></th>
				<td class='w-auto'><?php echo $userlist[$authzItem->account];?></td>	
			</tr> 
			<tr>
				<th class='w-auto'><?php echo $lang->kevinsvn->authz;?></th>
				<td><?php echo html::radio('authz',$lang->kevinsvn->authzEnum,$authzItem->authz);?></td>
			</tr>
			<tr>
                <td colspan='2' class="text-center"><?php echo html::submitButton($lang->save);?></td>
            </tr>
		</table>
	</form>
</div>