<?php
//if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include '../../common/view/header.lite.html.php';
include '../../common/view/chosen.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):
$kevinhoursTopName = 'HOURS';
?>
<header id='header'>
  <nav id='mainmenu'>
      <?php $this->loadModel("kevincom");
	kevincomModel::printMainmenu("kevinhoursTop",$this->moduleName);?>
    <?php //commonModel::printModuleMenu("kevinhoursTop");?>
  </nav>
  <nav id="modulemenu">
    <?php commonModel::printModuleMenu($this->moduleName);?>
  </nav>
</header>
<style>
#wrap { padding:0px 0px 0px 0px;}
#header {padding: 5px 5px 0px 0px; background:#1d0b6b;}
#myhome {padding-right: 100px;}
</style>
<div id='wrap'>
<?php endif;?>
  <div class='outer'>
