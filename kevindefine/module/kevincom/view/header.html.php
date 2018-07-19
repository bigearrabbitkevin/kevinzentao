<?php
//插件不再支持插件
//if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include '../../common/view/header.lite.html.php';
include '../../common/view/chosen.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>

<header id='header'>
  <nav id='mainmenu'>
    <?php $this->loadModel("kevincom");
	kevincomModel::printMainmenu("kevin",$this->moduleName);?>
  </nav>
  <nav id="modulemenu">
    <?php commonModel::printModuleMenu($this->moduleName);?>
  </nav>
</header>

<div id='wrap'> 
<?php endif;?>
 <div class='outer'> 
