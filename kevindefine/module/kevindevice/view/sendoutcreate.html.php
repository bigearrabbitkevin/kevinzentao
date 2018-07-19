<?php include '../../sk/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='titlebar'>
	<div class='heading'>
		<strong><small><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->kevindevice->spotchkcreate;?></strong>
	</div>
</div>
<div class='main'>
	<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
		<table class='table-form table-condensed' width = '100%' cellpadding="5">
			<tr>
				<th class='w-100px'><?php echo $lang->kevindevice->time;?></th>
				<td><?php echo html::input('time','', "class='form-control form-date'");?></td>
			</tr> 
			<tr>
				<th class='w-100px'><?php echo $lang->kevindevice->sendoutCount;?></th>
				<td><?php echo html::input('sendout',0, "class='form-control'");?></td>
			</tr> 
			<tr>
				<th class='w-100px'><?php echo $lang->kevindevice->totalCount;?></th>
				<td><?php echo html::input('total',0, "class='form-control'");?></td>
			</tr> 
			<tr>
				<td class='text-center' style="height: 120px" colspan="4"><?php echo html::submitButton() . html::backButton();?></td>
			</tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>

