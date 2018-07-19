<?php {
	if (!isset($Filter)) {
		echo 'Please set Filter First!';
		die();
	}
	$Filter->groupcreate = isset($groupcreate) ? $groupcreate : 0;

	$Filter->deptlist	 = $this->kevinplan->getdeptpairs(0, 'Detail');
	$Filter->deletedList = array_merge(array("" => ""), $lang->kevinplan->deletedList); //第一个必须是空，才可以去除
	$ShowPlanFilter		 = $Filter->groupcreate;
	$planYearlist		 = array("" => "", 2016 => 2016, 2017 => 2017, 2018 => 2018);
	$Filter_planlist	 = $planlist;
	if ($ShowPlanFilter) unset($Filter_planlist[$plan]);

	$Filter_ShowColList	 = $Filter->ShowColList;
	$Filter_project		 = $Filter->project;
	if ('projectlist' == $Filter->methodName) {
		$Filter_project = 0;
	}

	if ($Filter_project) {
		$Filter_ShowColList['dept'] = 0;
	}
	//var_dump($chargerListWithName)
	?>
	<?php if (commonModel::hasPriv('kevinplan', 'projectFilter')):?>
		<form id='searchform' method="post" class='form-condensed'>
			<?php echo html::hidden('kevinformtype', "projectFilter");?>
			<table width = 100%>
				<tr class="<?php echo ($Filter_ShowColList['planYear']) ? '' : 'hidden';?>">
					<th class="nobr w-30px"><?php echo $lang->kevinplan->Year;?></th>
					<td style="max-width:160px"><?php echo html::select("planYear", $planYearlist, $Filter->planYear, "class='form-control chosen pull-right' ");?></td>
				</tr>
				<tr class="<?php echo ($Filter_ShowColList['plan']) ? '' : 'hidden';?>">
					<th class="nobr w-30px"><?php echo $lang->kevinplan->plan;?></th>
					<td style="max-width:160px"><?php
						if (isset($planFilter)) {
							
						}
						if ($ShowPlanFilter) {
							echo html::hidden('groupcreate', $Filter->groupcreate);
							echo html::hidden('plan', $Filter->plan);
							echo html::select("planFilter", $Filter_planlist, $Filter->planFilter, "class='form-control chosen' ");
							//echo html::hidden('plan', $Filter->plan);
						} else {
							echo html::hidden('groupcreate', 0);
							echo html::select("plan", $Filter_planlist, $Filter->plan, "class='form-control chosen' ");
							echo html::hidden('planFilter', $Filter->planFilter);
						}
						?></td>	
				</tr>

				<?php if (isset($Filter->FilterProjectList)):?>
					<tr class="<?php echo ($Filter_ShowColList['project']) ? '' : 'hidden';?>">
						<th class="nobr w-30px"><?php echo $lang->kevinplan->project;?></th>
						<td style="max-width:160px"><?php echo html::select("project", $Filter->FilterProjectList, $Filter->project, "class='form-control chosen'");?></td>
					</tr>
				<?php endif;?>

				<?php if (isset($chargerListWithName)):?>
					<tr class="<?php echo ($Filter_ShowColList['charger']) ? '' : 'hidden';?>">
						<th class="nobr w-30px"><?php echo $lang->kevinplan->charge;?></th>
						<td style="max-width:160px"><?php echo html::select("charger", $chargerListWithName, $Filter->charger, "class='form-control chosen'");?></td>
					</tr>
				<?php endif;?>

				<!--    member   -->
				<?php if (isset($memberlistWithName)):?>
					<tr class="<?php echo ($Filter_ShowColList['member']) ? '' : 'hidden';?>">
						<th class="nobr w-30px"><?php echo $lang->kevinplan->member;?></th>
						<td style="max-width:160px"><?php echo html::select("member", $memberlistWithName, $Filter->member, "class='form-control chosen'");?></td>
					</tr>
				<?php endif;?>

				<!--    dept   -->
				<?php if (isset($Filter->deptlist)):?>
					<tr class="<?php echo ($Filter_ShowColList['dept']) ? '' : 'hidden';?>">
						<th class="nobr w-30px"><strong><?php echo $lang->kevinplan->dept;?></strong></th>
						<td style="max-width:160px"><?php echo html::select('dept', $Filter->deptlist, $Filter->dept, "class='form-control chosen'");?></td>
					</tr>
				<?php endif;?>

				<!--    projectPri   -->
				<tr class="<?php echo ($Filter_ShowColList['projectPri']) ? '' : 'hidden';?>">
					<th class="nobr w-30px"><strong><?php echo $lang->kevinplan->projectPri;?></strong></th>
					<td style="max-width:160px"><?php echo html::select('projectPri', $lang->kevinplan->projectPriList, $Filter->projectPri, "class='form-control chosen'");?></td>
				</tr>



				<tr class="<?php echo ($Filter_ShowColList['projectName']) ? '' : 'hidden';?>">
					<th class="nobr w-30px"><?php echo $lang->kevinplan->project;?></th>
					<td style="max-width:160px"><?php echo html::input('projectName', $Filter->projectName, "class='form-control' placeholder='{$lang->kevinplan->projectName}'");?></td>
				</tr>

				<?php if (commonModel::hasPriv('kevinplan', 'browseDeleted')):?>
					<tr class="<?php echo ($Filter_ShowColList['deleted']) ? '' : 'hidden';?>">
						<th class="nobr w-30px"><?php echo $lang->kevinplan->delete;?></th>
						<td><?php echo html::select('deleted', $Filter->deletedList, $Filter->deleted, "class='form-control chosen'");?></td>
					</tr>
				<?php endif;?>

				<?php
				echo html::hidden('methodName', $methodName);
				if (isset($statisticType)) {
					echo html::hidden('statisticType', $statisticType);
				}
				?>
				<tr>
					<td  class="text-center" colspan="2"><?php echo html::submitButton('搜索');?></td>
				</tr>
			</table>	
		</form>
		<?php
	endif;
}
?>	