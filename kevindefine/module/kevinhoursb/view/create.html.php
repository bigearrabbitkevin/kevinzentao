<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include 'titlebar.html.php'; ?>
<div class='container mw-700px'>
	<div id='titlebar'>
		<div class='heading'>
		<strong class='pull-left'><?php echo $lang->kevinhoursb->create;?>&nbsp;</strong>
		</div>
	</div>
	<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
		<table class='table table-form'> 
			<tr>
				<th class='w-80px'><?php echo $lang->kevinhoursb->date;?></th>
				<td>
					<div class='input-group'>
					<?php echo html::input('date', $date, "class='form-control form-date'");?>
					</div>
				</td>
			</tr> 
			<tr>
				<th><?php echo $lang->kevinhoursb->status;?></th>
				<td><?php echo html::select('status', $lang->kevinhoursb->statusList, '', "class='form-control'");?></td>
			</tr>  
			<tr>
				<th><?php echo $lang->kevinhoursb->desc;?></th>
				<td><?php echo html::input('desc', '', "class='form-control'");?></td>
			</tr>

			<tr>
				<td></td>
				<td><?php echo html::submitButton() . html::backButton();?></td>
			</tr>
			<tr><td>&nbsp;</td><tr>
			<tr><td>&nbsp;</td><tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
