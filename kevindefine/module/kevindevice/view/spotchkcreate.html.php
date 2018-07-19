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
				<th class='w-100px'><?php echo $lang->kevindevice->name;?></th>
				<td><?php echo html::input('name','', "class='form-control'");?></td>
			</tr> 
			<tr>
				<td class='text-center' colspan="4"><?php echo html::submitButton() . html::backButton();?></td>
			</tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>

