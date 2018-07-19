<div <?php if (!$project) echo 'hidden'?> >
	<hr/>
	<th><?php echo $lang->kevinplan->deleteItem?></th>
	<table class='table table-condensed  table-hover table-striped tablesorter ' id='KevinValueList'>
		<thead>
			<tr class='text-center' height=35px>
				<th class='text-center w-id'><?php echo $lang->kevinplan->id;?></th>
				<th class='text-center w-50px'><?php echo $lang->kevinplan->Year;?></th>
				<?php if ($project == 0):?>
					<th class='text-left w-50px'><?php echo $lang->kevinplan->projectCode;?></th>
					<th class='text-left w-auto' style='min-width:100px'><?php echo $lang->kevinplan->projectPlanName;?></th>
				<?php endif;?>
				<th class='text-center w-80px'><?php echo $lang->kevinplan->dept;?></th>
				<th class='text-left w-user'><?php echo $lang->kevinplan->memberName;?></th>
				<th class='text-left w-auto' style='min-width:100px'><?php echo $lang->kevinplan->workcontent;?></th>
				<th class='text-right w-60px'><?php echo $lang->kevinplan->hours;?></th>
				<th class='text-right w-80px'><?php echo $lang->kevinplan->manYear;?></th>
				<th class='text-center w-date'><?php echo $lang->kevinplan->startDate;?></th>
				<th class='text-center w-date'><?php echo $lang->kevinplan->endDate;?></th>
				<th class='text-center w-60px'><?php echo $lang->kevinplan->status;?></th>
				<th class='text-center w-100px {sorter:false}'><?php echo $lang->actions;?></th>
			</tr>
		</thead>
		<?php
		foreach ($memberlistdel as $item):
			$item->manYear	 = $item->hours / 2000;
			if ($item->deleted) $style			 = "background:red";
			else $style			 = "";
			$canView		 = common::hasPriv('kevinplan', 'memberview');
			?>
			<tr>
				<td class='text-center' style="<?php echo $style;?>"><?php
					printf('%03d', $item->id);
					?>    </td>
				<td class='text-center'><?php echo $item->planYear;?></td>
				<?php if ($project == 0):?>
					<td class='text-right'><?php
						if ($item->projectCode == 0) echo '';
						else {
							echo $item->projectCode;
						}
						?></td>
					<td class='text-left'><?php
						commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project={$item->project}", '', 'list', 'user');
						echo $item->name;
						?></td>
				<?php endif;?>
				<td class='text-left nobr'><?php echo $deptArray[$item->dept];?></td>
				<td class='text-left'><?php
					commonModel::printIcon('kevinplan', 'memberlist', "type=&plan=&project=&member=$item->member", '', 'list', 'user');
					echo $userlist[$item->member];
					?></td>
				<td class='text-left'><?php echo $item->notes;?></td>
				<td class='text-right'><?php echo $item->hours;?></td>
				<td class='text-right'><?php echo sprintf("%.2f", $item->manYear * 100) . '%';?></td>
				<td class='text-center'><?php echo $item->startDate;?></td>
				<td class='text-center'><?php echo $item->endDate;?></td>
				<td class='text-center'><?php echo $lang->kevinplan->statusList[$item->status];?></td>
				<td class='text-center'>
					<?php
					commonModel::printIcon('kevinplan', 'memberview', "id=$item->id", '', 'list', 'search', '', 'iframe', true);
					if ($this->kevinplan->isCharger[$project]) common::printIcon('kevinplan', 'memberdelete', "id={$item->id}", '', 'list', 'remove', 'hiddenwin', '', 'iframe', true); //弹出窗口，取消后可以消失

					if ($this->kevinplan->isCharger[$project] || ( isset($this->kevinplan->isMember[$item->id]) && $this->kevinplan->isMember[$item->id])) {
						common::printIcon('kevinplan', 'memberedit', "id={$item->id}", '', 'list', 'pencil', '', 'iframe', true);
					} else {
						?><i class="icon-kevinplan-memberedit icon-pencil disabled" style="font-size:18px;"></i><?php }
					?>
				</td>
			</tr>
		<?php endforeach;?>
	</table>
</div>