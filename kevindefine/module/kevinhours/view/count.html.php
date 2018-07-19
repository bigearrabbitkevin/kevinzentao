<?php include '../../kevinhours/view/header.html.php'; ?>
<?php include '../../common/view/datepicker.html.php'; ?>
<?php
//提前判断权限。
$checkAllPriv        = common::hasPriv('kevinhours', 'checkAll');
$browseDeptHoursPriv = common::hasPriv('kevinhours', 'browseDeptHours');
$dispatchedPriv      = common::hasPriv('kevinhours', 'dispatched');
$printoverPriv       = common::hasPriv('kevinhours', 'printover');
$printovertablePriv  = common::hasPriv('kevinhours', 'printovertable');
?>
<div class='side' style="width: 260px;">
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']); ?> <strong>
					<?php echo $lang->kevinhours->filter; ?></strong>
			</div>
			<div style="min-height:500px">
				<form class='form-condensed' method='post' target='hiddenwin'>
					<table class='table table-form'>
						<tr>
							<th><?php echo $lang->kevinhours->certainYear; ?></th>
							<td style="max-width: 189px;"><?php echo html::select('year', $yearList, $year, "class='form-control chosen' style='width:180px'"); ?></td>
						</tr>
						<tr>
							<th><?php echo $lang->kevinhours->certainMonth; ?></th>
							<td style="max-width: 189px;"><?php echo html::select('month', $lang->kevinhours->month, $month, "onchange=setSeasonInputNull() class='form-control chosen' style='width:180px'"); ?></td>
						</tr>
						<?php if ($checkAllPriv || $browseDeptHoursPriv) { ?>
							<tr>
								<th><?php echo $lang->kevinhours->dept; ?></th>
								<td style="max-width: 189px;"><?php echo html::select('dept', $deptArray, $deptIndex, "onchange=getEmployee(this.value) class='form-control chosen' style='width:180px'"); ?></td>
							</tr>
						<?php } ?>
						<?php if ($checkAllPriv || $browseDeptHoursPriv) { ?>
							<tr>
								<th><?php echo $lang->kevinhours->containchild; ?></th>
								<td style="max-width: 189px;"><?php echo html::select('deptcount', $lang->kevinhours->deptCountList, $deptcount, "class='form-control chosen' style='width:180px'"); ?></td>
							</tr>
						<?php } ?>
						<tr>
							<th><?php echo $lang->kevinhours->hourstype; ?></th>
							<td style="max-width: 189px;"><?php echo html::select('hourstype', $lang->kevinhours->exportHoursTypeList, $hourstypeIndex, "class='form-control chosen' style='width:180px'"); ?></td>
						</tr>
						<?php if ($checkAllPriv || $browseDeptHoursPriv) { ?>
							<tr>
								<th><?php echo $lang->kevinhours->hoursscope; ?></th>
								<td style="max-width: 189px;"><?php echo html::select('employeetype', $lang->kevinhours->employeeTypeList, $employeetypeIndex, "class='form-control chosen' style='width:180px'"); ?></td>
							</tr>
						<?php } ?>


					</table>
					<table class='table table-form'>
						<?php if ($printoverPriv) { ?>
							<tr>
								<td class='text-center'><?php echo html::checkbox('fillauth', $lang->kevinhours->fillauthlist, '1', "id='fillauth'"); ?></td>
								<td class='text-center'><?php echo "<input class='btn' type='button' value='班组加班' onclick=printover();>" ?></td>
							</tr>
						<?php } ?>
						<?php if ($dispatchedPriv || $printovertablePriv) { ?>
							<tr>
								<?php if ($dispatchedPriv) { ?>
									<td class='text-center'><?php echo "<input class='btn' type='button' value='外协工作' onclick=dispatched();>" ?></td>
								<?php } ?>
								<?php if ($printovertablePriv) { ?>
									<td class='text-center'><?php echo "<input class='btn' type='button' value='正式加班' onmouseover=setCurrentMonth('$currentMonth') onclick=printovertable();>" ?></td>
								<?php } ?>
							</tr>
						<?php } ?>
						<tr>
							<td class='text-center'><?php echo html::checkbox('isIncludeAnn', $lang->kevinhours->isIncludeAnn, $this->session->isIncludeAnn, ''); ?></td>
						</tr>
						<tr>
							<td colspan="2" class='text-center'><?php echo html::submitButton("提交"); ?></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
<div class='main'>
	<center>
		<div id="usertable">
		</div>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
<script>
	$(document).ready(function () {
		var deptId = "<?php echo $deptIndex ?>";
		getEmployee(deptId)
	});
</script>
