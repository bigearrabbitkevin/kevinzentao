<?php include '../../common/view/header.lite.html.php';?>
<?php
if (!$planItem) {
	echo "No plan input!";
	die();
}
$deptlist[0] = ''; //empty
?>
<?php echo "<center>";?>
<table width="" border="0" cellpadding="1" cellspacing="0">
	<tr>
		<td class='text-center' >
			<?php echo "<h2>".$lang->kevinplan->planInfo."</h2>";?>
			<table width="100%" border="1" cellpadding="0" cellspacing="0">
				<tr height="15">
					<td class='w-80px'><strong><?php echo $lang->kevinplan->plan;?></strong></td>
					<td><?php echo sprintf('%03d', $planItem->id);?></td>
					<td class='w-80px'><strong><?php echo $lang->kevinplan->planName;?></strong></td>
					<td><?php echo $planItem->name;?></td>
					<td class='w-80px'><strong><?php echo $lang->kevinplan->hoursPlan;?></strong></td>
					<td><?php echo $planItem->hoursPlan . ' h /' . sprintf("%.2f", $planItem->hoursPlan / 2000) . ' ' . $lang->kevinplan->manYear;?></td>
					<td class='w-80px'> </td>
					<td> </td>
				</tr>
				<tr>
					<td class='w-80px'><strong><?php echo $lang->kevinplan->dept;?></strong></td>
					<td><?php echo isset($deptlist[$planItem->dept]) ? $deptlist[$planItem->dept] : '';?></td>

					<td class='w-100px'><strong><?php echo $lang->kevinplan->charger;?></strong></td>
					<td><?php echo isset($userlist[$planItem->charger]) ? $userlist[$planItem->charger] : '';?></td>

					<td class='w-100px'><strong><?php echo $lang->kevinplan->startDate;?></strong></td>
					<td><?php echo $planItem->startDate;?></td>

					<td class='w-100px'><strong><?php echo $lang->kevinplan->endDate;?></strong></td>
					<td><?php echo $planItem->endDate?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class='text-center'>
			<?php echo "<h2>项目计划</h2>";?>
			<table width="100%" border="1" cellpadding="0" cellspacing="0">
				<tr height="15">
					<td class='text-center w-40px'><?php echo $lang->kevinplan->YearAB;?></td>
					<td class='text-center w-40px'><?php echo "No.";?></td>
					<td class='text-center w-200px' style='min-width:100px'><?php echo $lang->kevinplan->projectPlanName;?></td>
					<td class='text-center w-30px' ><?php echo $lang->kevinplan->projectCode;?></td>
					<td class='text-center w-60px'><?php echo $lang->kevinplan->charger;?>1</td>
					<td class='text-center w-60px'><?php echo $lang->kevinplan->charger;?>2</td>
					<td class='text-center w-80px'><?php echo $lang->kevinplan->dept;?></td>
					<td class='text-center w-80px'><?php echo $lang->kevinplan->pri;?></td>
					<td class='text-center w-90px'><?php echo $lang->kevinplan->classPro;?></td>
					<td class='text-center w-60px'><?php echo $lang->kevinplan->ProNew;?></td>
					<td class='text-center w-90px'><?php echo $lang->kevinplan->status;?></td>
					<td class='text-right w-60px'><?php echo $lang->kevinplan->hours;?></td>
					<td class='text-right w-60px'><?php echo $lang->kevinplan->hoursCost;?></td>
					<td class='text-center w-date'><?php echo $lang->kevinplan->dateBuild;?></td>
					<td class='text-center w-date'><?php echo $lang->kevinplan->dateDR2;?></td>
					<!--
					<td class='text-center w-date'><?php echo $lang->kevinplan->dateDR3;?></td>
					<td class='text-center w-date'><?php echo $lang->kevinplan->dateDR4;?></td>
					-->
					<td class='text-center w-150px'><?php echo $lang->kevinplan->workcontent;?></td>
				</tr>
				<?php
				$totalHours	 = 0;
				$serial		 = 0;
				foreach ($ProjectArray as $item):
					$serial++;
					$item->manYear	 = $item->hoursPlan / 2000;
					$totalHours += $item->hoursPlan;
					if ($item->deleted) $style			 = "background:red";
					else $style			 = "";
					$viewLink		 = $this->createLink('kevinplan', 'projectview', "id = $item->id", '', true);
					$canView		 = common::hasPriv('kevinplan', 'projectview');
					?>
					<tr>
						<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->planYear;?></td>
						<td class='text-center' style="<?php echo $style;?>"><?php printf('%03d', $serial);?></td>
						<td class='text-left' style="word-wrap:break-word;"><?php echo $item->name;?></td>
						<td class='text-left'><?php if ($item->projectCode > 0) echo $item->projectCode;?>	</td>
						<td class='text-left'><?php echo (array_key_exists($item->charger, $userlist)) ? $userlist[$item->charger] : $item->charger;?></td>
						<td class='text-left'><?php echo (array_key_exists($item->charger2, $userlist)) ? $userlist[$item->charger2] : $item->charger2;?></td>
						<td class='text-left'><?php echo $deptlist[$item->dept];?></td>
						<td class='text-center nobr'  style="word-wrap:break-word;"><?php echo $lang->kevinplan->projectPriList[$item->pri];?></td>
						<td class='text-center nobr'  style="word-wrap:break-word;"><?php echo $lang->kevinplan->classProList[$item->classPro];?></td>
						<td class='text-center nobr'  style="word-wrap:break-word;"><?php echo $lang->kevinplan->ProNewList[$item->ProNew];?></td>
						<td class='text-center nobr'  style="word-wrap:break-word;"><?php echo $lang->kevinplan->statusList[$item->status];?></td>
						<td class='text-right'  style="word-wrap:break-word;"><?php echo $item->hoursPlan;?></td>
						<td class='text-right'  style="word-wrap:break-word;"><?php echo $item->hoursCost;?></td>
						<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->dateBuild;?></td>
						<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->dateDR2;?></td>
						<!--
						<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->dateDR3;?></td>
						<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->dateDR4;?></td>
						-->
						<td class='text-center'  style="word-wrap:break-word;"><?php echo $item->notes;?></td>
					</tr>
				<?php endforeach;?>
				<tr>
					<td colspan=13 align='right'>
						<div class='actions actions-form'>
							<?php echo $lang->kevinplan->hours . " = " . $totalHours . $lang->kevinplan->hoursunit . ". ( " . sprintf("%.2f", $totalHours / 2000) . $lang->kevinplan->manYear . ")";?>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</tfoot>
</table>
<?php //include '../../kevincom/view/footer.html.php';?>
