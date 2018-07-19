<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix'><?php echo html::icon($lang->icons['edit']);?> <strong><?php echo $item->name;?></strong></span>
		<small><?php echo html::icon($lang->icons['edit']) . '' . $lang->kevindevice->supplieredit;?></small>
	</div>
</div>
<div class='main'>
	<div class='body'>
		<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
			<table class='table-form table-condensed' width = '100%' cellpadding="5"> 			
				<tr>
					<th class='w-100px'><?php echo $lang->kevindevice->name;?></th>
					<td><?php echo html::input('time',$item->time, "class='form-control form-date'");?></td>
				</tr> 
				<tr>
					<th class='w-100px'><?php echo $lang->kevindevice->sendoutCount;?></th>
					<td><?php echo html::input('sendout',$item->sendout, "class='form-control'");?></td>
				</tr>
				<tr>
					<th class='w-100px'><?php echo $lang->kevindevice->totalCount;?></th>
					<td><?php echo html::input('total',$item->total, "class='form-control'");?></td>
				</tr>
				<tr style="height: 110px">
					<td class='text-center' colspan="2"><?php echo html::submitButton() . html::backButton();?></td>
				</tr>
			</table>
		</form>
	</div>
</div>

