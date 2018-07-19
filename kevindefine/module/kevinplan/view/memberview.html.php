<?php
/**
 * The view file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinplan
 * @link        http://www.zentao.net
 */
?>

<?php include '../../kevinhours/view/header.html.php'; ?>
<?php // include 'configfeaturebar.html.php';
//die(json_encode($memberItem)); ?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='item'><?php echo html::icon($lang->icons['app']); ?> <strong><?php echo $memberItem->id; ?></strong></span>
		<strong><?php //echo $memberItem->name; ?></strong>
		<?php if ($memberItem->deleted): ?>
			<span class='label label-danger'><?php echo "已删除"; ?></span>
		<?php endif; ?>
	</div>
	<div class='actions'>
		<?php
		$browseLink	 = $this->createLink('kevinplan', 'memberlist');
		$params		 = "id=$memberItem->id";
		$preAndNext = '';
		ob_start();
		echo "<div class='btn-member'>";
		if($this->kevinplan->isCharger||$this->kevinplan->isMember[$memberItem->id])common::printIcon('kevinplan', 'memberedit', $params, '', 'button', 'pencil', '', 'iframe', true);
		if (!$memberItem->deleted&&$this->kevinplan->isCharger) common::printIcon('kevinplan', 'memberdelete', $params, '', 'button', 'remove', 'hiddenwin');
        common::printRPN($browseLink, $preAndNext);
		echo '</div>';
		$actionLinks = ob_get_contents();
		ob_end_clean();
		echo $actionLinks;
		?>
	</div>
</div>
<div class='main main-side'>
	<fieldset >
		<legend ><?php echo $lang->kevinplan->project.$lang->kevinplan->basicInfo; ?></legend>
		<table class='table table-data  table-condensed table-borderless'>			
			<tr>
				<th class='w-10px'><strong><?php echo $lang->kevinplan->projectCode; ?></strong></th>
				<td><?php 
					if ($projectItem->projectCode==0) echo ''; 
					echo $projectItem->projectCode; 
				?></td>
				<td><?php 
					if ($projectItem->projectCode!=0)
						echo html::a($this->createLink('kevinhours', 'project', "id={$projectItem->projectCode}&type=thisYear", '', true)
						, '<i class="icon icon-time"></i>'.$lang->kevinplan->hours, '', "data-toggle='modal' data-type='iframe' data-icon='check'"); 
				?></td>
					
				<th class='w-10px'><strong><?php echo $lang->kevinplan->projectName; ?></strong></th>
				<td><?php echo $projectItem->name; ?></td>
				
			
				<th class='w-10px'><strong><?php echo $lang->kevinplan->dept; ?></strong></th>
				<td><?php echo isset($deptlist[$projectItem->dept])?$deptlist[$projectItem->dept]:''; ?></td>
				
				<th class='w-10px'><strong><?php echo $lang->kevinplan->charger; ?></strong></th>
				<td><?php echo isset($userlist[$projectItem->charger])?$userlist[$projectItem->charger]:''; ?></td>
			</tr>	
		</table>
	</fieldset>
	
	<fieldset>
		<legend><?php echo $lang->kevinplan->basicInfo; ?></legend>
		<table class='table table-data table-condensed table-borderless'>
			<!--<tr>
				<th  class='w-120px'><?php echo $lang->kevinplan->project; ?></th>
				<td><?php echo $projectItem->name;?></td>
			</tr> -->
			<tr>
				<th><?php echo $lang->kevinplan->member; ?></th>
				<td><?php echo $userlist[$memberItem->member]; ?></td>
				<th><?php echo $lang->kevinplan->dept; ?></th>
				<td><?php echo isset($deptlist[$memberItem->dept])?$deptlist[$memberItem->dept]:$memberItem->dept; ?></td>
				
			</tr> 
			<tr>
				<th  class='w-120px'><?php echo $lang->kevinplan->hours; ?></th>
				<td><?php echo $memberItem->hours; ;?></td>
				
				<th><?php echo $lang->kevinplan->hoursCost; ?></th>
				<td><?php echo $memberItem->hoursCost; ?></td>
			</tr> 
            <tr>
				<th><?php echo $lang->kevinplan->manYear;?></th>
				<td><?php echo sprintf ("%.1f",$memberItem->hours/2000*100).'%';?></td>
				<th><?php echo $lang->kevinplan->status; ?></th>
				<td><?php echo $lang->kevinplan->statusList[$memberItem->status]; ?></td>
			</tr>  
			
			<tr>
				<th><?php echo $lang->kevinplan->startDate;?></th>
				<td><?php echo $memberItem->startDate;?></td>
				
				<th><?php echo $lang->kevinplan->endDate;?></th>
				<td><?php echo $memberItem->endDate;?></td>
			</tr> 
			<tr>
				<th><?php echo $lang->kevinplan->addedBy; ?></th>
				<td><?php echo $userlist[$memberItem->addedBy]; ?></td>
				
				<th><?php echo $lang->kevinplan->addedDate; ?></th>
				<td><?php echo $memberItem->addedDate; ?></td>
            </tr>
			<tr>
				<th><?php echo $lang->kevinplan->lastEditedBy; ?></th>
				<td><?php echo $userlist[$memberItem->lastEditedBy]; ?></td>
				
				<th><?php echo $lang->kevinplan->lastEditedDate; ?></th>
				<td><?php echo $memberItem->lastEditedDate; ?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinplan->IsFinished;?></th>
                <td><?php echo $lang->kevinplan->yesOrNo[$memberItem->IsFinished];?></td>	
			</tr>
			<tr>
				<th><?php echo $lang->kevinplan->workcontent; ?></th>
                <td colspan='3'><?php echo $memberItem->notes;?></td>
			</tr> 
		</table>
	</fieldset>
	<div class='actions left'><?php if (!$memberItem->deleted) echo $actionLinks; ?></div>
</div>

<?php include '../../kevincom/view/footer.html.php'; ?>