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

?>
<?php include '../../kevincom/view/header.html.php'; ?>
<div class='container mw-900px'>
	<form class='form-condensed' method='post' target='hiddenwin'>
		<table class='table table-form'>
			<tr class='text-center'>
				<td class='strong'><?php echo $lang->group->module; ?></td>
				<td class='strong'><?php echo $lang->group->method; ?></td>
				<td class='strong'><?php echo $lang->group->common; ?></td>
			</tr>
			<tr>
				<td class='w-p30'><?php echo html::select('module', $modules, $this->session->chosenmodule, "onchange='setModuleActions(this.value)' size=\"10\" style='height:500px' class='form-control'"); ?></td>
				<td class='w-p30' id='actionBox'>
					<?php
					$class = '';
					foreach ($actions as $module => $moduleActions) {
						echo html::select('actions', $moduleActions,'', " size='10' onchange='getMethodPrivs(this.value)' style='height:500px' class='form-control $class {$module}Actions'");
						$class = 'hidden';
					}
					?>
				</td>
				<td id="groupsBox"><?php echo html::select('groups[]', $groups, $this->session->chosengpstr, "multiple='multiple' size='10' style='height:500px' class='form-control'"); ?></td>
			</tr>
			<tr>
				<td class='text-center' colspan='3'>
					<?php
					echo html::submitButton($lang->save);
					echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
					echo html::hidden('foo'); // Just make $_POST not empty..
					?>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
<script language='Javascript'>
	/**
	 * Control the actions select control for a module.
	 * 
	 * @param   string $module 
	 * @access  public
	 * @return  void
	 */
	var chosenmodule = document.getElementById('module').value;
	var chosenmethod='<?php echo $this->session->chosenmethod;?>';
	setModuleActions(chosenmodule);
	getMethodPrivs(chosenmethod);
	function setModuleActions(module)
	{
		$('#actionBox select').addClass('hidden');          // Hide all select first.
		$('#actionBox select').val('');                     // Unselect all select.
		$('.' + module + 'Actions').removeClass('hidden');  // Show the action control for current module.
	}
	function getMethodPrivs(method) {
		var module = document.getElementById('module').value;
		link = createLink('kevinlogin', 'ajaxGetGroupPrivsByMethod', 'module=' + module + '&method=' + method);
		$('.' + module + 'Actions').find("option[value='"+method+"']").attr('selected',true);
		$('#groupsBox').load(link, function() {
			$('groups[]').chosen(defaultChosenOptions);
		});
	}
</script>

