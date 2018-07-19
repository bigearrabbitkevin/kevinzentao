<?php
/**
 * The manage member view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managemember.html.php 4627 2013-04-10 05:42:20Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='GROUP'><?php echo html::icon($lang->icons['group']);?> <strong><?php echo $group->id;?></strong></span>
    <strong><?php echo $group->name;?></strong>
    <small class='text-muted'> <?php echo $lang->group->manageMember;?> <?php echo html::icon($lang->icons['manage']);?></small>
  </div>
</div>
<div class='row-table row-table-swap'>
  <div class="col-side">
    <div class='side-body'>
      <div class='panel panel-sm'>
        <div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?> <strong><?php echo $lang->dept->common;?></strong></div>
        <div class='panel-body'><?php echo $deptTree;?></div>
      </div>
    </div>
  </div>
  <div class="col-main">
    <form class='form-condensed pdb-20' method='post' target='hiddenwin'>
      <table align='center' class='table table-form'> 
        <?php if($groupUsers):?>
        <tr>
		<!--Start: 区分组内和组外语句。 kevin.-->
          <th class='w-100px' ><?php echo $lang->group->inside;?><?php echo html::selectAll('group', 'checkbox', true);?> </th>
          <td id='group' class='form-control ' size='10' style='height:200px;overflow:auto;' ><?php $i = 1;?>
			  <div class='w-100px' ><strong><?php echo $group->name.'人员';//echo '科室人员';?></strong><?php echo html::selectAll('indept', 'checkbox', true);?></div>
            <?php foreach($groupUsers as $account => $realname):?>
			  <div class='group-item' id='indept'><?php if(in_array($account, $deptusers)) echo html::checkbox('members', array($account => $realname), $account);?></div>
		<!--end: 区分组内和组外语句。 kevin.--> 
            <?php endforeach;?>
			<!--Start:添加非组内人员循环语句。 kevin.-->
			  <div class='w-100px' ><strong><?php echo '非'.$group->name.'人员';//echo '非科室人员';?></strong><?php echo html::selectAll('extdept', 'checkbox', true);?></div>
			 <?php foreach($groupUsers as $account => $realname):?>
			   <div class='group-item extdept' id='extdept' ><?php if(!in_array($account, $deptusers)) echo html::checkbox('members', array($account => $realname), $account);?></div>
			<!--end: 添加非组内人员循环语句。 kevin.-->
			 <?php endforeach;?>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-100px'><?php echo $lang->group->outside;?><?php echo html::selectAll('other','checkbox');?> </th>
			<!--下面一句：添加高度限制语句 kevin.-->
          <td id='other' class='form-control' size='10' style='height:200px;overflow:auto;'><?php $i = 1;?>
            <?php foreach($otherUsers as $account => $realname):?>
            <div class='group-item'><?php echo html::checkbox('members', array($account => $realname), '');?></div>
            <?php endforeach;?>
          </td>
        </tr>
        <tr>
          <th></th>
          <td class='text-center'>
            <?php 
            echo html::submitButton();
            echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
            echo html::hidden('foo'); // Just a var, to make sure $_POST is not empty.
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>

<?php include '../../../common/view/footer.html.php';?>
