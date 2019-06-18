<?php
/**
 * The 详细显示
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevindevice
 */
?>
<table class='table table-condensed table-hover tablesorter ' id='DeviceList'>
	<thead>
		<tr class='colhead'>
			<?php $vars				 = "param=$param&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
			<th class='w-id'><?php common::printorderlink('id', $orderBy, $vars, $lang->idAB);?></th>
			<th class='w-auto'><?php common::printOrderLink('name', $orderBy, $vars, $lang->kevindevice->name);?></th>
			<th class='w-100px'><?php common::printOrderLink('tcpip', $orderBy, $vars, $lang->kevindevice->tcpip);?></th>
			<th class='w-50px'><?php echo $lang->kevindevice->yearlimit;?></th>
			<th class='w-auto'><?php common::printOrderLink('purpose', $orderBy, $vars, $lang->kevindevice->purpose);?></th>
			<th class='w-80px'><?php common::printOrderLink('user', $orderBy, $vars, $lang->kevindevice->user);?></th>
			<th class='w-80px'><?php common::printOrderLink('charge', $orderBy, $vars, $lang->kevindevice->charge);?></th>
			<th class='w-100px'><?php common::printOrderLink('dept', $orderBy, $vars, $lang->kevindevice->dept);?></th>
			<th class='w-user'><?php common::printOrderLink('type', $orderBy, $vars, $lang->kevindevice->type);?></th>
			<th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->kevindevice->status);?></th>
			<th class='w-250px'><?php common::printOrderLink('description', $orderBy, $vars, $lang->kevindevice->desc);?></th>
			<th class='w-100px'><?php common::printOrderLink('repairend', $orderBy, $vars, $lang->kevindevice->repairend); ?></th>
			<th class='w-100px'><?php common::printOrderLink('join', $orderBy, $vars, $lang->kevindevice->join);?></th>
			<th class='w-100px'><?php common::printOrderLink('dieDate', $orderBy, $vars, $lang->kevindevice->dieDate);?></th>
			<th class='w-80px'><?php echo $lang->actions;?></th>
		</tr>
	</thead>
	<tbody>

		<?php
		$canBatchEdit		 = false; //common::hasPriv('user', 'batchEdit');
		$canManageContacts	 = false; //common::hasPriv('user', 'manageContacts');
		$candevdelete		 = common::hasPriv('kevindevice', 'devdelete');
		$canUserEdit		 = common::hasPriv('kevindevice', 'devedit');
		?>
		<?php
		foreach ($devices as $device):
			$byend=  time()-strtotime($device->repairend);
			if($byend>=0&&$device->repairend!="0000-00-00")$endstyle='background:red;';
			else $endstyle='';
			$yearlimit	 = time() - strtotime($device->join);
			$device->yearlimit= ceil($yearlimit / 60 / 60 / 24 / 365)-1;
			if ($device->join == "0000-00-00") {
				$device->yearlimit	 = '';
				$linestyle	 = "background:#D5D5D5;";
				$datestyle	 = "";
			} elseif (time() - strtotime($device->join . '+5 years') >= 0) {
				$linestyle	 = "background:yellow;";
				$datestyle	 = "";
			} else {
				$linestyle	 = "";
				$datestyle	 = "color: green;";
			}
			?>
			<tr class='text-center' >
				<td>
					<?php
					if ($canBatchEdit or $canManageContacts) echo "<input type='checkbox' name='devices[]' value='$device->name'> ";
					printf('%03d', $device->id);
					?>
				</td>
				<td><?php if (!common::printLink('kevindevice', 'devview', "id=$device->id", $device->name)) echo $device->name;?></td>
				<td><?php echo $device->tcpip;?></td>
				<td style="<?php echo $linestyle . ' ' . $datestyle;?>"><?php echo $device->yearlimit;?></td>
				<td><?php echo $device->purpose;?></td>	
				<td><?php echo (isset($users[$device->user])) ? $users[$device->user] : $device->user;?></td>
				<td><?php echo (isset($users[$device->charge])) ? $users[$device->charge] : $device->charge;?></td>
				<td><?php echo $device->deptName;?></td>
				<td><?php echo $lang->kevindevice->DevTypeList[$device->type];?></td>
				<td <?php if ( 'discard' == $device->status) echo 'style="background:yellow; "';?>><?php echo $lang->kevindevice->StatusList[$device->status];?></td>
				<td><?php echo $device->description;?></td>
				<td style=<?php echo $endstyle?>><?php echo ($device->repairend == "0000-00-00")? "":$device->repairend; ?></td>
				<td><?php echo ($device->join == "0000-00-00") ? "" : $device->join;?></td>
				<td><?php echo ($device->dieDate == "0000-00-00") ? "" : $device->dieDate;?></td>
				<td class='text-left'>
					<?php
					if ($canUserEdit) {
						common::printIcon('kevindevice', 'devedit', "userID=$device->id", '', '', 'pencil');
						// echo html::a($this->createLink('kevindevice', 'devedit', "userID=$device->id"), '<i class="icon-common-edit icon-pencil"></i>', '', "title='{$lang->kevindevice->edit}' class='btn-icon iframe'");
					}
					if ($candevdelete) {
						common::printIcon('kevindevice', 'devdelete', "userID=$device->id", '', '', 'remove', '', 'iframe', true, "data-width='550'");
					}
					?>
				</td>
			</tr>
<?php endforeach;?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan='12'>
				<div class='table-actions clearfix'>
					<?php
					if ($canBatchEdit or $canManageContacts) echo "<div class='btn-group'>" . html::selectButton() . '</div>';
					if ($canBatchEdit) echo html::submitButton($lang->edit, 'onclick=batchEdit()', 'btn-default');
					if ($canManageContacts) echo html::submitButton($lang->kevindevice->contacts->manage, 'onclick=manageContacts()');
					?>
				</div>
<?php echo $pager->show();?>
			</td>
		</tr>
	</tfoot>
</table>