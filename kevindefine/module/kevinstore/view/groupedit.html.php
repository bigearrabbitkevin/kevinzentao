<?php
/**
 * The edit view of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php include '../../kevincom/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='GROUP'><?php echo html::icon($lang->icons['group']);?> <strong><?php echo $group->id;?></strong></span>
    <strong><?php echo $group->name;?></strong>
    <small class='text-muted'> <?php echo $lang->kevinstore->groupedit;?> <?php echo html::icon($lang->icons['edit']);?></small>
  </div>
</div>

<form class='form-condensed mw-500px pdb-20' method='post' target='hiddenwin' id='dataform'>
  <table align='center' class='table table-form'> 
    <tr>
      <th class='w-100px'><?php echo $lang->kevinstore->name;?></th>
      <td><?php echo html::input('name', $group->name, "class='form-control'");?></td>
    </tr>  
	 <tr>
        <th class='w-100px'><?php echo $lang->kevinstore->type; ?></th>
	  	<td><?php echo html::select('type', $lang->kevinstore->GroupTypeList,$group->type, "class='form-control'"); ?></td>
	</tr>  
    <tr>
      <th><?php echo $lang->kevinstore->desc;?></th>
      <td><?php echo html::textarea('desc', $group->desc, "rows='5' class='form-control'");?></td>
    </tr>  
    <tr><th></th><td><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../kevincom/view/footer.html.php';?>
