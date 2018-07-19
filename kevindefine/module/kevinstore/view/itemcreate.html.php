<?php
/**
 * The itemcreate view of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php include '../../common/view/datepicker.html.php'; ?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('holders', $lang->user->placeholder); ?>
<?php js::set('typeGroup', $typeGroup); ?>
<div class='container mw-800px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['user']); ?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']); ?></small> <?php echo $lang->user->create; ?></strong>
    </div>
  </div>
  <form class='form-condensed mw-800px' method='post' target='hiddenwin' id='dataform'>
    <table align='center' class='table table-form'> 
      <tr>
        <th class='w-120px'><?php echo $lang->kevinstore->name; ?></th>
        <td class='w-auto'><?php echo html::input('name', '', "class='form-control' autocomplete='off'"); ?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kevinstore->type; ?></th>
        <td><?php echo html::select('type', $lang->kevinstore->DevTypeList, '', "class='form-control'"); ?></td>
      </tr>
      <tr>    
        <th><?php echo $lang->kevinstore->status; ?></th>
        <td><?php echo html::select('status', $lang->kevinstore->StatusList, '', "class='form-control'"); ?></td>      </tr>    
      </tr>
      <tr>
        <th><?php echo $lang->kevinstore->dept; ?></th>
        <td><?php echo html::select('dept', $depts, $deptID, "class='form-control chosen'"); ?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kevinstore->group; ?></th>
        <td><?php echo html::select('group', $groupList, 0, "class='form-control chosen'"); ?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kevinstore->join; ?></th>
        <td><?php echo html::input('join', '', "class='form-control form-date'"); ?></td>
      </tr>     
      <tr>
        <th><?php echo $lang->kevinstore->desc; ?></th>
        <td><?php echo html::textarea('description', "", "rows='5' class='form-control autosize' style='height: 108px;'"); ?></td>
      </tr>            
      <tr><th></th><td><?php echo html::submitButton() . html::backButton(); ?></td></tr>
    </table>
  </form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
