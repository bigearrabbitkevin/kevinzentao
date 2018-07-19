<?php
/**
 * The html template file of login method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: login.html.php 5084 2013-07-10 01:31:38Z wyd621@gmail.com $
 */
include '../../../common/view/header.lite.html.php';
?>
<div id='container'>
  <div id='login-panel'>
    <div class='panel-head'>
      <h4><?php printf($lang->welcome, $app->company->name);?></h4>
      <div class='panel-actions'>
        <div class='dropdown' id='langs'>
          <button class='btn' data-toggle='dropdown' title='Change Language/更换语言/更換語言'><?php echo $config->langs[$this->app->getClientLang()]; ?> <span class="caret"></span></button>
          <ul class='dropdown-menu'>
            <?php foreach($config->langs as $key => $value):?>
            <li class="<?php echo $key==$this->app->getClientLang()?'active':''; ?>"><a href="###" data-value="<?php echo $key; ?>"><?php echo $value; ?></a></li>
            <?php endforeach;?>
          </ul>
        </div>
        <button data-placement='bottom' data-toggle='popover' data-content="<?php if(extension_loaded('gd')) echo "<img height='297' width='297' src='" . $this->createLink('misc', 'qrcode') . "' />"; else echo $noGDLib; ?>" title="<?php echo $lang->user->mobileLogin;?>" id='mobile' class='btn'><i class='icon-mobile'></i></button>
      </div>
    </div>
      <form name="myForm" id = "myForm" method='post' target='hiddenwin' class='form-condensed' >
	  <div class="panel-content" id="login-form">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->user->account;?></th>
            <td><input class='form-control' type='text' name='account' id='account' placeholder="tom@abc.com Or tom "/></td>
          </tr>
          <tr>
            <th><?php echo $lang->user->password;?></th>
            <td><input class='form-control' type='password' name='password'  placeholder="domain or local passowrd"/></td>
          <tr>
            <th></th>
            <td id="keeplogin"><?php echo html::checkBox('keepLogin', $lang->user->keepLogin, $keepLogin)?>&nbsp;&nbsp;<?php 
		    if($app->company->guest) echo html::linkButton($lang->user->asGuest, $this->createLink($config->default->module));
            echo html::hidden('referer', $referer);
			?>
           </tr>      
		  <tr>            <th></th>
            <td>	
				<button id="defaultfocus" type="submit" name="defaultfocus" class="btn btn-submit btn-primary"><?php echo $lang->login?></button>
            </td>
          </tr>
		  </table>
    </div>
</form>  
    <div id='kevinloginfoot' class="panel-foot">
      <span>Auto LDAP Domain Certification! Use domain passowrd for domain user! </span>
    </div>  
  </div>
  <div id="poweredby" class = "text-left">

    <?php if($config->checkVersion):?>
    <iframe id='updater' class='hidden' frameborder='0' width='100%' scrolling='no' allowtransparency='true' src="http://api.zentao.net/updater-isLatest-<?php echo $config->version;?>-<?php echo $s;?>.html?lang=<?php echo str_replace('-', '_', $this->app->getClientLang())?>"></iframe>
    <?php endif;?>
  </div>
</div>
<?php include '../../../common/view/footer.lite.html.php';?>
