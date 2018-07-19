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
<?php
$userID = $this->kevinsvn->svnUser?$this->kevinsvn->svnUser->id:"";
?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='id'><?php echo $lang->kevinsvn->rep . " > " . $lang->kevinsvn->repcreate . " > ";?></span>
	</div>
</div>

<div class='row-table'>
	<form class='form' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
		<table class='table table-form table-borderless' width = '100%' cellpadding="5"> 
			<tr>
				<th class='w-100px nobr'><?php echo $lang->kevinsvn->name;?></th>
				<td class='w-auto'><?php echo html::input('name','',"class='form-control'");?></td>	
			</tr> 
<!--			<tr>
				<th class='nobr'><?php // echo $lang->kevinsvn->structure;?></th>
				<td class='w-auto'><?php // echo html::radio('structure',$lang->kevinsvn->repstrulist,'0');?></td>	
			</tr> -->
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->title;?></th>
				<td class='w-auto'><?php echo html::input('title','',"class='form-control'");?></td>
			</tr>
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->project;?></th>
				<td class='w-auto'><?php echo html::input('project','',"class='form-control'");?></td>	
			</tr> 
				<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->dept;?></th>
				<td class='w-auto'><?php echo html::select('dept',$depts,$this->app->user->dept,"class='form-control chosen'");?></td>	
			</tr> 
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->charger;?></th>
				<td class='w-auto'><?php echo html::select('charger',$users,$userID,"class='form-control chosen'");?></td>
			</tr>
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->description;?></th>
				<td class='w-auto'><?php echo html::input('description','',"class='form-control'");?></td>	
			</tr> 
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->class;?></th>
				<td class='w-auto'><?php echo html::input('class','',"class='form-control'");?></td>	
			</tr> 
			<tr>
                <td colspan='2' style='height:150px;' class="text-center"><?php echo html::submitButton($lang->save);?></td>
            </tr>
		</table>
	</form>
</div>