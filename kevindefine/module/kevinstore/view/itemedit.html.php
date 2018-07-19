<?php
/**
 * The itemedit view of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php if (!$item) die("no item exist!"); ?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='LIST'><?php echo html::icon($lang->icons['edit']); ?> <strong><?php echo $title; ?></strong></span>
		<strong><?php
			if (!commonModel::printLink('kevinstore', 'itemview', "number=$item->number", $item->name)) echo $item->name;
			?> (<small><?php echo $item->number; ?></small>)</strong>
	</div>
</div>
<div class='container mw-800px'>
	<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
		<table align='center' class='table table-form'>
			<tr>
				<th><?php echo $lang->kevinstore->number; ?></th>
				<td class='w-p40'><?php echo html::input('number', $item->number, "class='form-control' autocomplete='off'"); ?></td>
				<th class='w-90px'><?php echo $lang->kevinstore->name; ?></th>
				<td class='w-p40'><?php echo html::input('name', $item->name, "class='form-control' autocomplete='off'"); ?></td>
			</tr>
			<tr>
				<th class='w-80px'><?php echo $lang->kevinstore->group; ?></th>
				<td><?php echo html::select('group', $groupList, $item->group, "class='form-control'"); ?></td>
				<th class='w-90px'><?php echo $lang->kevinstore->subType; ?></th>
				<td><?php echo html::select('subType', $subTypeList, $item->subType, "class='form-control'"); ?></td>
			</tr>    
			<tr>
				<th class='w-80px'><?php echo $lang->kevinstore->stdPrice; ?></th>
				<td class='w-p40'><?php echo html::input('stdPrice', $item->stdPrice, "class='form-control' autocomplete='off'"); ?></td>
			</tr> 
		</table>

		<table align='center' class='table table-form'>
			<tr><td colspan='2' class='text-center'><?php echo html::submitButton() . html::backButton(); ?></td></tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
