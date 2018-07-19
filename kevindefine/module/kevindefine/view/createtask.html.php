<?php
/**
 * The export view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <strong><?php echo $this->lang->kevindefine->createTaskTips;?></strong>
  </div>
</div>
<form class='form-condensed pdb-20' method='post' target='hiddenwin'>
  <table class='table table-form'>
    <tr>
      <td class='w-200px' colspan='5'>
        <?php
			echo html::select('project', $projects, $project, "class='form-control' size='5'");
		?>
      </td>
    </tr>
	<tr class='text-center'><td><?php echo html::submitButton($this->lang->kevindefine->selectproject);?></td></tr>
  </table>
</form>
<?php include '../../kevincom/view/footer.html.php';?>
