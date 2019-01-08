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
<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php
$users = $this->kevinsvn->svnUsers->PairsActive;
if(!$repinfo) die("Rep is Empty");
if(!array_key_exists($repinfo->charger, $users)){
	$users[$repinfo->charger] = $this->kevinsvn->svnUsers->PairsAll[$repinfo->charger];
}
?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='id'><?php echo $lang->kevinsvn->rep . " > " . $lang->kevinsvn->repedit . " > ";?> <strong><?php echo $repinfo->id;?></strong></span>
	</div>
</div>

<div class='row-table'>
	<form class='form' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
		<table class='table table-form table-borderless' width = '100%' cellpadding="5"> 
			<tr>
				<th class='w-100px nobr'><?php echo $lang->kevinsvn->name;?></th>
				<td class='w-auto'><?php echo html::input('name',$repinfo->name,"class='form-control' readonly");?></td>	
			</tr> 
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->title;?></th>
				<td class='w-auto'><?php echo html::input('title',$repinfo->title,"class='form-control'");?></td>
			</tr>
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->project;?></th>
				<td class='w-auto'><?php echo html::input('project',$repinfo->project,"class='form-control'");?></td>	
			</tr> 
				<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->dept;?></th>
				<td class='w-auto'><?php echo html::select('dept',$depts,$repinfo->dept,"class='form-control chosen'");?></td>	
			</tr> 
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->charger;?></th>
				<td class='w-auto'><?php echo html::select('charger',$users,$repinfo->charger,"class='form-control chosen'");?></td>
			</tr>
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->description;?></th>
				<td class='w-auto'><?php echo html::input('description',$repinfo->description,"class='form-control'");?></td>	
			</tr> 
			<tr>
				<th class='nobr'><?php echo $lang->kevinsvn->class;?></th>
				<td class='w-auto'><?php echo html::input('class',$repinfo->class,"class='form-control'");?></td>	
			</tr> 
			<tr style='height:150px;'>
                <td colspan='2' class="text-center"><?php echo html::submitButton($lang->save);?></td>
            </tr>
		</table>
	</form>
</div>