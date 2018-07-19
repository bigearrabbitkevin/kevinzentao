<?php
/**
 * The rowedit view of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php if (!$rowItem) die("no row exist!"); ?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='LIST'><?php echo html::icon($lang->icons['edit']); ?> <strong><?php echo $title; ?></strong></span>
		<strong><?php echo $rowItem->name; ?> (<small><?php echo $rowItem->id; ?></small>)</strong>
	</div>
</div>
<div class='container mw-800px'>
	<fieldset>

		<legend><?php echo $lang->kevinplan->legendBasic; ?></legend>
		<table align='center' class='table table-form'>
			<tr>
				<th><?php echo $lang->kevinstore->name; ?></th>
				<td class='w-p40'><?php echo $rowItem->name; ?></td>
				<th class='w-90px'><?php echo $lang->kevinstore->id; ?></th>
				<td class='w-p40'><?php echo $rowItem->id; ?></td>
			</tr>
			<tr>
				<th class='w-90px'><?php echo $lang->kevinstore->group; ?></th>
				<td class='w-p40'><?php echo $rowItem->group; ?></td>
				<th class='w-90px'><?php echo $lang->kevinstore->subType; ?></th>
				<td class='w-p40'><?php echo $rowItem->subType; ?></td>
			</tr>    
			<tr>
				<th class='w-80px'><?php echo $lang->kevinstore->stdPrice; ?></th>
				<td><?php echo $rowItem->stdPrice; ?></td>
				<th class='w-80px'><?php echo $lang->kevinstore->totalPrice; ?></th>
				<td><?php echo $rowItem->totalPrice; ?></td>
			</tr>   
		</table>
	</fieldset>
	<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
		<table align='center' class='table table-form'>
			<tr>
				<th><?php echo $lang->kevinstore->number; ?></th>
				<td class='w-p40'><?php echo html::input('number', $rowItem->number, "readonly='on' class='form-control' autocomplete='off'"); ?></td>
				<th><?php echo $lang->kevinstore->count; ?></th>
				<td class='w-p40'><?php echo html::input('count', $rowItem->count, "class='form-control' autocomplete='off'"); ?></td>
			</tr>
			<tr>
				<th class='w-80px'><?php echo $lang->kevinstore->actionType; ?></th>
				<td><?php echo html::select('actionType', $lang->kevinstore->actionTypeList, $rowItem->actionType, "class='form-control'"); ?></td>
				<th class='w-90px'><?php echo $lang->kevinstore->actionTeam; ?></th>
				<td><?php echo html::select('actionTeam', $lang->kevinstore->actionTeamList, $rowItem->actionTeam, "class='form-control'"); ?></td>
			</tr>    

		</table>

		<table align='center' class='table table-form'>
			<tr><td colspan='2' class='text-center'><?php echo html::submitButton() . html::backButton(); ?></td></tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
