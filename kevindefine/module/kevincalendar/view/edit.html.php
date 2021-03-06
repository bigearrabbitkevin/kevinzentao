<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-400px'>
	<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
		<table class='table table-form'> 
			<tr>
				<th class='w-80px'><?php echo $lang->kevincalendar->date;?></th>
				<td>
					<div class='input-group'>
					<?php echo html::input('date', $kevincalendar->date, "class='form-control form-date'");?>
					</div>
				</td>
			</tr> 
			<tr>
				<th><?php echo $lang->kevincalendar->status;?></th>
				<td><?php echo html::select('status', $lang->kevincalendar->statusList, $kevincalendar->status, "class='form-control'");?></td>
			</tr>  
			<tr>
				<th><?php echo $lang->kevincalendar->desc;?></th>
				<td><?php echo html::input('desc', htmlspecialchars($kevincalendar->desc), "class='form-control'");?></td>
			</tr>
			<tr>
				<td></td>
				<td>
				<?php echo html::submitButton() . html::backButton();?>
				</td>
			</tr>
			<tr><td>&nbsp;</td><tr>
			<tr><td>&nbsp;</td><tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
