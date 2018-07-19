<?php
/**
 * The view file of group of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php include '../../common/view/kindeditor.html.php'; ?>
<?php include 'kevinstorebar.html.php'; ?>
<div id='grouptitlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['edit']); ?> <strong><?php echo $group->id; ?></strong></span>
    <strong><?php echo $group->name; ?></strong>
    <?php if ($group->deleted): ?>
        <span class='label label-danger'><?php echo $lang->kevinstore->deleted; ?></span>
    <?php endif; ?>
  </div>
      <?php
      $actionLinks = '';
      if (!$group->deleted) {
          ob_start();
          echo "<div class='btn-group'>";
          common::printIcon('kevinstore', 'groupedit', "group=$group->id", '', 'edit', 'pencil', '', 'iframe', 'yes');
          common::printIcon('kevinstore', 'groupdelete', "group=$group->id", '', '', 'remove', '', 'iframe', true, "data-width='550'");
          echo '</div>';

          echo "<div class='btn-group'>";
          //  common::printRPN($browseLink, $preAndNext);
          echo '</div>';


          $actionLinks = ob_get_contents();
          ob_clean();
      } else {
          echo "this group is deleted. please first undeleted.";
      }
      ?>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->kevinstore->desc; ?></legend>
        <div class='article-content'><?php echo $group->desc; ?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->kevinstore->dev; ?></legend>
        <div class='article-content'><?php
            foreach ($stores as $store):
                if ($store->id) {
                    echo " <span class='prefix'><strong>";
                    common::printLink('kevinstore', 'devview', "userid=$store->id", $store->name . "(" . $lang->kevinstore->DevTypeList[$store->type] . ")");
                    echo "</strong></span> ";
                }
            endforeach;
            ?></div>
      </fieldset>
      <div class='actions'> <?php if (!$group->deleted) echo $actionLinks; ?></div>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->kevinstore->legendBasic; ?></legend>
        <table class='table table-data table-condensed table-borderless'> 
          <tr>
            <th class='w-80px'><?php echo $lang->kevinstore->id; ?></th>
            <td><?php echo $group->id; ?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->kevinstore->name; ?></th>
            <td><?php echo $group->name; ?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->kevinstore->type; ?></th>
            <td><?php echo $lang->kevinstore->GroupTypeList[$group->type]; ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->kevinstore->createdate; ?></th>
            <td><?php echo $group->createdate; ?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php'; ?>
<?php include '../../kevincom/view/footer.html.php'; ?>
