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
<?php 
$dept=$this->app->user->dept;
$userID = $this->kevinsvn->svnUser?$this->kevinsvn->svnUser->id:"";

?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='id'><?php echo $lang->kevinsvn->rep . " > " . $lang->kevinsvn->repsync;?></span>
	</div>
	<div class="actions">
		<?php echo html::backButton();?>
	</div>
</div>
<div class='row-table'>
	<?php if($reps){  
		?>
	<form class='form' method='post' id='dataform'>
		<table class='table table-form table-borderless' width = '100%' cellpadding="5"> 
			<thead>
				<th></th>
				<th><?php echo $lang->kevinsvn->name;?></th>
				<th><?php echo $lang->kevinsvn->title;?></th>
				<th><?php echo $lang->kevinsvn->project;?></th>
				<th><?php echo $lang->kevinsvn->dept;?></th>
				<th><?php echo $lang->kevinsvn->charger;?></th>
				<th><?php echo $lang->kevinsvn->description;?></th>
				<th><?php echo $lang->kevinsvn->class;?></th>
			</thead>
			<tbody>
			<?php foreach($reps as $i=>$rep): ?>
				<tr>
					<td align="center"><?php echo "<input type='checkbox' name='choices[$i]' value=$i>";?></td>
					<td class='w-500px'><?php echo $rep;echo html::hidden("name[$i]",$rep);?></td>	
					<td class='w-500px'><?php echo html::input("title[$i]",$rep,"class='form-control'");?></td>	
					<td class='w-500px'><?php echo html::input("project[$i]",'',"class='form-control'");?></td>	
					<td class='w-500px'><?php echo html::select("dept[$i]",$depts,$dept,"class='form-control chosen'");?></td>	
					<td class='w-500px'><?php echo html::select("charger[$i]",$users,$userID,"class='form-control chosen'");?></td>	
					<td class='w-500px'><?php echo html::input("description[$i]",'',"class='form-control'");?></td>	
					<td class='w-500px'><?php echo html::input("class[$i]",'',"class='form-control'");?></td>	
				</tr> 
			<?php endforeach;?>
			<tr>
                <td colspan='2' style='height:150px;' class="text-center"><?php echo html::submitButton($lang->kevinsvn->savetodb);?></td>
            </tr>
			</tbody>
		</table>
	</form><?php }else echo $lang->kevinsvn->norepavaliable;?>
</div>
