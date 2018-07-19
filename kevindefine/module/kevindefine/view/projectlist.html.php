<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 2343 2011-11-21 05:24:56Z wwccss $
 */
?>
<?php
$this->moduleName = "product";
include '../../common/view/header.html.php';
include '../../common/view/sparkline.html.php';

$canBatchEdit  = common::hasPriv('kevindefine', 'projectbatchEdit');
$canBatchClose = (common::hasPriv('kevindefine', 'projectbatchClose'));

//check cashCode exist
$showCashCode = false;
foreach ($projectStats as $project):
    $showCashCode = isset($project->cashCode);
    break;
endforeach;

$vars = "productID=$productID&type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
$productsSelect = array(0 => $this->lang->product->select) + $products;
?>
<div id='featurebar'>
  <div class='actions'>
    <?php echo html::a($this->createLink('project', 'create'), "<i class='icon-plus'></i> " . $lang->project->create,'', "class='btn'") ?>
  </div>
  <ul class='nav'>
    <?php echo "<li>" . html::select('product', $productsSelect, $productID, "class='chosen' onchange='byProduct(this.value)'") . '</li>';?>
    <?php echo "<li id='undoneTab'>" . html::a(inlink("projectlist", "productID=$productID&type=undone"), $lang->project->undone) . '</li>';?>
    <?php echo "<li id='allTab'>" . html::a(inlink("projectlist", "productID=$productID&type=all"), $lang->project->all) . '</li>';?>
    <?php echo "<li id='waitTab'>" . html::a(inlink("projectlist", "productID=$productID&type=wait"), $lang->project->statusList['wait']) . '</li>';?>
    <?php echo "<li id='doingTab'>" . html::a(inlink("projectlist", "productID=$productID&type=doing"), $lang->project->statusList['doing']) . '</li>';?>
    <?php echo "<li id='suspendedTab'>" . html::a(inlink("projectlist", "productID=$productID&type=suspended"), $lang->project->statusList['suspended']) . '</li>';?>
    <?php echo "<li id='doneTab'>" . html::a(inlink("projectlist", "productID=$productID&type=done"), $lang->project->statusList['done']) . '</li>';?>
  </ul>
</div>
<div class='side' id='treebox'>
    <a class='side-handle' data-id='productTree'><i class='icon-caret-left'></i></a>
    <div class='side-body'>
        <div class='panel panel-sm'>
            <div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo '产品列表';?></strong></div>
            <div class='panel-body'>
                <ul class='tree'>
                    <?php
                    foreach ($products as $id => $name) {
                        echo '<li>' . html::a(helper::createLink('kevindefine', 'projectlist'
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
     <form method='post' id='myProjectForm'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='projectlisttable'>
        <tbody>
        <thead>
            <tr class='colhead'>
                <th class='w-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
                <th class='w-p50'><?php common::printOrderLink('name', $orderBy, $vars, $lang->project->name);?></th>
                <th class='w-150px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->project->code);?></th>
                <th class='w-date'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->project->begin);?></th>
                <th class='w-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
                <th class='w-90px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->project->PM);?></th>
                <th class='w-90px'><?php common::printOrderLink('QD', $orderBy, $vars, $lang->project->QD);?></th>
                <?php if ($showCashCode):?>
                    <th class='w-90px'><?php echo $lang->kevindefine->cashCode;?></th>
                <?php endif;?>
                <th class='w-140px'> <?php echo $lang->actions;?></th>
            </tr>
        </thead>
        <?php foreach ($projectStats as $project):?>
            <tr class='text-center' data-id='<?php echo $project->id?>' data-order='<?php echo $project->order?>'>                    
                <td>
                    <?php if ($canBatchEdit):?>
                        <input type='checkbox' name='projectIDList[<?php echo $project->id;?>]' value='<?php echo $project->id;?>' /> 
                    <?php endif;?>
                    <?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), sprintf('%03d', $project->id));?>
                </td>

                <td class='text-left'><?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name, '_parent');?></td>
                <td><?php echo $project->code;?></td>
                <td><?php echo $project->begin;?></td>
                <td class='status-<?php echo $project->status?>'><?php echo $lang->project->statusList[$project->status];?></td>
                <td><?php echo $project->PM;?></td>
                <td><?php echo $project->QD;?></td>
                <?php if ($showCashCode):?>
                    <th><?php echo $project->cashCode;?></th>
                <?php endif;?>
                <td class='text-right'>
                    <?php
                    common::printIcon('project', 'start', "projectID=$project->id", $project, 'list', 'play', '', 'iframe', true);
                    common::printIcon('project', 'edit', "taskID=$project->id", '', 'list', 'pencil');
                    common::printIcon('project', 'close', "taskID=$project->id", $project, 'list', 'off', '', 'iframe', true);
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan='10'>
                    <?php if (count($projectStats)):?>
                        <div class='table-actions clearfix'>
                            <?php
                            if ($canBatchEdit or $canBatchClose)
                                echo "<div class='btn-group'>" . html::selectButton() . '</div>';
                            echo "<div class='btn-group'>";
                            if ($canBatchEdit) {
                                $actionLink = $this->createLink('kevindefine', 'projectbatchEdit', "projectID=0&orderBy=$orderBy");
                                echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\"");
                            }
                            echo '</div>';
                            ?>
                        </div> 
                    <?php endif;?>
                    <?php $pager->show();?>
                </td>
            </tr>
        </tfoot>
    </table>
  </form>
</div>
<script language='javascript'>
	$('#<?php echo $type; ?>Tab').addClass('active')
</script>
<?php include '../../kevincom/view/footer.html.php';?>
