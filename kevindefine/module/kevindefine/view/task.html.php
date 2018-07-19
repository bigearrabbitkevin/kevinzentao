<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
$this->moduleName = "product";
include '../../common/view/header.html.php';
?>
<div id='featurebar'>
  <ul class='nav'>
	  <?php
	  echo "<li id='unclosedTab'>";
	  common::printLink('kevindefine', 'task', "product=$productID&type=unclosed", $lang->product->unclosed);
	  echo '</li>';
	  echo "<li id='allTab'>";
	  common::printLink('kevindefine', 'task', "product=$productID&type=all", $lang->kevindefine->all);
	  echo '</li>';
	  echo "<li id='undoneTab'>";
	  common::printLink('kevindefine', 'task', "product=$productID&type=undone", $lang->kevindefine->undone);
	  echo '</li>';
	  echo "<li id='assignedtomeTab'>";
	  common::printLink('kevindefine', 'task', "product=$productID&type=assignedtome", $lang->kevindefine->assignedToMe);
	  echo '</li>';
	  ?>
  </ul>
</div>
<div class='side' id='treebox'>
  <a class='side-handle' data-id='productTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']); ?> <strong><?php echo '产品列表'; ?></strong></div>
      <div class='panel-body'>
		<ul class='tree'>
<?php
foreach ($products as $id => $name) {
	echo '<li>' . html::a(helper::createLink('kevindefine', 'task'
			, "productID=$id&type=$type")
		, $name, '', "class='link'") . '</li>';
}
?>
		</ul>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <form method='post' id='myTaskForm'>
	<table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='tasktable'>
<?php $vars			 = "productID=$productID&type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
	  <thead>
		<tr class='text-center'>
		  <th class='w-id'>    <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB); ?></th>
		  <th class='w-70px'>   <?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB); ?></th>
		  <th class='w-150px'> <?php common::printOrderLink('project', $orderBy, $vars, $lang->product->project); ?></th>
		  <th>                 <?php common::printOrderLink('name', $orderBy, $vars, $lang->kevindefine->taskname); ?></th>
		  <th class='w-hour'>  <?php common::printOrderLink('estimate', $orderBy, $vars, $lang->kevindefine->estimateAB); ?></th>
		  <th class='w-hour'>  <?php common::printOrderLink('left', $orderBy, $vars, $lang->kevindefine->leftAB); ?></th>
		  <th class='w-date'>  <?php common::printOrderLink('deadline', $orderBy, $vars, $lang->kevindefine->deadlineAB); ?></th>
		  <th class='w-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB); ?></th>
		  <th class='w-user'>  <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->kevindefine->assign); ?></th>
		  <th class='w-140px'> <?php echo $lang->actions; ?></th>
		</tr>
	  </thead>   
	  <tbody>
<?php $canBatchEdit	 = common::hasPriv('task', 'batchEdit'); ?>
<?php $canBatchClose	 = (common::hasPriv('task', 'batchClose')); ?>
<?php foreach ($tasks as $task): ?>
			<tr class='text-center'>
			  <td class='text-left'>
				  <?php if ($canBatchEdit or $canBatchClose): ?><input type='checkbox' name='taskIDList[]' value='<?php echo $task->id; ?>' /><?php endif; ?>
				  <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id)); ?>
			  </td>
			  <td><span class='<?php echo 'pri' . zget($lang->kevindefine->priList, $task->pri, $task->pri); ?>'><?php echo zget($lang->kevindefine->priList, $task->pri, $task->pri); ?></span></td>
			  <td class='nobr text-left'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->project"), $task->projectName); ?></td>
			  <td class='text-left nobr'><?php
			  echo html::a($this->createLink('task', 'view', "taskID=$task->id", '', true)
				  , '<i class="icon icon-list-ul"></i>', '', "data-toggle='modal' data-type='iframe' title='$lang->modalTip'") . ' ';
			  echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name);
				  ?></td>
			  <td><?php echo $task->estimate; ?></td>
			  <td><?php echo $task->left; ?></td>
			  <td class='<?php if (isset($task->delay)) echo 'delayed'; ?>'><?php if (substr($task->deadline, 0, 4) > 0) echo $task->deadline; ?></td>
			  <td class='<?php echo $task->status; ?>'><?php echo $lang->kevindefine->taskStatusList[$task->status]; ?></td>
			  <td><?php if ('closed' == $task->assignedTo) echo $task->assignedTo;
			  else echo $task->realname; ?></td>
			  <td class='text-right'>
	<?php
	common::printIcon('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list', 'hand-right', '', 'iframe', true);
	common::printIcon('task', 'start', "taskID=$task->id", $task, 'list', 'play', '', 'iframe', true);
	common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
	common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', 'ok-sign', '', 'iframe', true);
	common::printIcon('task', 'close', "taskID=$task->id", $task, 'list', 'off', '', 'iframe', true);
	common::printIcon('task', 'edit', "taskID=$task->id", '', 'list', 'pencil');
	?>
			  </td>
			</tr>
			  <?php endforeach; ?>
	  </tbody>
	  <tfoot>
		<tr>
		  <td colspan='10'>
			  <?php if (count($tasks)): ?>
				<div class='table-actions clearfix'>
				  <?php
				  if ($canBatchEdit or $canBatchClose)
					  echo "<div class='btn-group'>" . html::selectButton() . '</div>';
				  echo "<div class='btn-group'>";
				  if ($canBatchEdit) {
					  $actionLink = $this->createLink('task', 'batchEdit', "projectID=0&orderBy=$orderBy");
					  echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\"");
				  }
				  if ($canBatchClose) {
					  $actionLink = $this->createLink('task', 'batchClose');
					  echo html::commonButton($lang->close, "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
				  }
				  echo '</div>';
				  ?>
				</div> 
<?php endif; ?>
<?php $pager->show(); ?>
		  </td>
		</tr>
	  </tfoot>
	</table> 
  </form>
</div>
<script language='javascript'>
	$('#<?php echo $type; ?>Tab').addClass('active')
</script>
<?php include '../../kevincom/view/footer.html.php'; ?>
