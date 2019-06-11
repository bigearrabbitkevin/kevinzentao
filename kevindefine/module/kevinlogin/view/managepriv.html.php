<?php
/**
 * The manage privilege view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
 $this->moduleName= "kevinuser";//第一级标签

$groupList[''] = '所有';
 foreach($lang->menu as $module => $title):
	 $groupList[$module] =  substr($title, 0, strpos($title, '|'));
endforeach;
$groupList['other'] = '其它';

?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php include '../../common/view/chosen.html.php';?>
<div class='container '>
	<form class='form-condensed' method='post' target='hiddenwin' id="form_managepriv">
		<table class='table table-form'>
			<tr class='text-center'>
				<td class='strong w-p15'>Group</td>
				<td class='strong w-p30'><?php echo $lang->group->module; ?></td>
				<td class='strong w-p30'><?php echo $lang->group->method; ?></td>
				<td class='strong w-p30'><?php echo $lang->group->common; ?></td>
			</tr>
			<tr>
				<td><?php echo html::select('byGroup', $groupList, '', "onchange='managepriv_SelectGroup(this.value)' size=\"10\" style='height:500px' class='form-control'"); ?></td>
				<td><?php echo html::select('module', $modules, $this->session->chosenmodule, "onchange='setModuleActions(this.value)' size=\"10\" style='height:500px' class='form-control'"); ?></td>
				<td id='actionBox'>
					<?php
					$class = '';
					foreach ($actions as $module => $moduleActions) {
						echo html::select('actions', $moduleActions,'', " size='10' onchange='getMethodPrivs(this.value)' style='height:500px' class='form-control $class {$module}Actions'");
						$class = 'hidden';
					}
					?>
				</td>
				<td id="groupsBox"><?php echo html::select('groups[]', $groups, $this->session->chosengpstr, "multiple='multiple' size='10' style='height:500px' class=' chosen multiple form-control'"); ?></td>
			</tr>
			<tr>
				<td class='text-center' colspan='3'>
					<button type="button" onclick="managepriv_submit()" class="btn btn-primary"><?php echo $lang->save; ?></button>
					<?php
					echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
					echo html::hidden('foo'); // Just make $_POST not empty..
					?>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
<script src="<?php echo $jsRoot; ?>kevin/kevin.js"></script>
<script language='Javascript'>
	var grouplist = '<?php  echo json_encode($lang->menugroup);?>';
	var g_method='<?php echo $this->session->chosenmethod;?>';

</script>

