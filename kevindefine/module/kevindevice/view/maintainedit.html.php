<?php
/**
 * The devedit view of kevindevice module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevindevice
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php include '../../common/view/datepicker.html.php'; ?>
<?php include '../../common/view/kindeditor.html.php'; ?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix'><?php echo html::icon($lang->icons['edit']);?> <strong><?php echo $maintain->id;?></strong></span>
		<small><?php echo html::icon($lang->icons['edit']) . '' . $lang->kevindevice->maintainedit;?></small>
	</div>
</div>
<div class='container mw-800px'>
	<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
		<table class='table-form table-condensed' width = '100%' cellpadding="5"> 		
			<tr>
				<th><?php echo $lang->kevindevice->time; ?></th>
				<td><?php echo html::input('time', $maintain->time, "class='form-control form-date'"); ?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevindevice->platform; ?></th>
				<td><?php echo html::input('platform', $maintain->platform, "class='form-control' "); ?></td>	
			</tr>    
			<tr>
				<th><?php echo $lang->kevindevice->log; ?></th>
				<td><?php echo html::input('log', $maintain->log, "class='form-control'"); ?></td>
			</tr>
		</table>
		<table align='center' class='table table-form'>
			<tr><td colspan='2' class='text-center' style="height:100px;"><?php echo html::submitButton() . html::backButton(); ?></td></tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
