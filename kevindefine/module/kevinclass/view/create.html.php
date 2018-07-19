<?php include '../../kevincom/view/header.html.php';?>
<div class='panel'>
  <div class='panel-body'>
    <form id='ajaxForm' method='post' class='form-inline'>
      <table class='table table-form'>
        <tr>
          <th class='w-100px'><?php echo $lang->kevinclass->author;?></th>
          <td><?php echo html::input('author', $app->user->realname, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><span><?php echo $lang->kevinclass->title;?></span></th>
          <td>
            <div class='required required-wrapper'></div>
            <?php echo html::input('title', '', 'class=form-control');?>
          </td>
        </tr>
        <tr>
          <th><span><?php echo $lang->kevinclass->alias;?></span></th>
          <td>
            <div class='required required-wrapper'></div>
            <div class='input-group'>
              <span class='input-group-addon'>http://<?php echo $this->server->http_host . $config->webRoot?>book/</span>
              <?php echo html::input('alias', '', "class='form-control' placeholder='{$lang->kevinclass->aliasTip}'");?>
              <span class='input-group-addon'>.html</span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kevinclass->keywords;?></th>
          <td><?php echo html::input('keywords', '', 'class=form-control');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->kevinclass->summary;?></th>
          <td><?php echo html::textarea('summary', '', "class='form-control' rows='3'");?></td>
        </tr>
        <tr>
          <th></th><td><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
