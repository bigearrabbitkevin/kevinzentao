<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/form.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php 
$path = explode(',', $node->path);
js::set('path', $path);
?>
<div class="panel">
  <div class='panel-body'>
    <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
      <table class='table table-form'>
                <?php if($node->type == 'article'):?>
        <tr>
          <th><?php echo $lang->sklawnotes->addedDate;?></th>
          <td>
				<li id='byDate' class='datepicker-wrapper datepicker-date'>
              <?php echo html::input('addedDate', $this->sklawnotes->formatTime($node->addedDate), "class='form-control form-date'");?>
              </li>
          </td>
          <td><span class="help-inline"><?php echo $lang->sklawnotes->note->addedDate;?></span></td>
        </tr>
        <?php endif;?>
        <?php if($node->type != 'book'):?>
        <tr>
          <th><?php echo $lang->sklawnotes->parent;?></th>
          <td><?php echo html::select('parent', $optionMenu, $node->parent, "class='chosen form-control'");?></td>
        </tr>
        <?php endif; ?>
        <?php if($node->type == 'book'):?>
        <tr>
          <th><?php echo $lang->sklawnotes->order;?></th>
          <td><?php echo html::input('order', $node->order, 'class="form-control"');?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->sklawnotes->title;?></th>
          <td colspan='2'>
            <div class='required required-wrapper'></div>
            <?php echo html::input('title', $node->title, 'class="form-control"');?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->sklawnotes->alias;?></th>
          <td colspan='2'>
            <?php if($node->type == 'book'):?>
            <div class='required required-wrapper'></div>
            <?php endif;?>
            <div class='input-group text-1'>
              <span class='input-group-addon'>http://<?php echo $this->server->http_host . $config->webRoot?>book/id@</span>
              <?php echo html::input('alias', $node->alias, "class='form-control' placeholder='{$lang->sklawnotes->aliasTip}'");?>
              <span class='input-group-addon'>.html</span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->sklawnotes->keywords;?></th>
          <td colspan='2'><?php echo html::input('keywords', $node->keywords, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->sklawnotes->summary;?></th>
          <td colspan='2'><?php echo html::textarea('summary', $node->summary, "class='form-control' rows='2'");?></td>
        </tr>
        <?php if($node->type == 'article'):?>
        <tr>
          <th><?php echo $lang->sklawnotes->content;?></th>
          <td colspan='2' valign='middle'><?php echo html::textarea('content', $node->content, "rows='15' class='form-control'");?></td>
        </tr>
        <?php endif;?>
		<tr>
          <th class='col-xs-1'><?php echo $lang->sklawnotes->author;?></th>
          <td class='w-p40'><?php echo html::input('author', $node->author, "class='form-control'");?></td>
          <td><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
