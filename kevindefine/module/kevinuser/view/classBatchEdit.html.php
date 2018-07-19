<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/monthpicker.html.php';?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix'><?php echo html::icon($lang->icons['project']);?></span>
		<strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $title;?></strong>
		<div class='actions'>
			<button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
		</div>
	</div>
</div>
<?php
$visibleFields = array();
foreach (explode(',', $showFields) as $field) {
	if ($field) $visibleFields[$field] = '';
}
$minWidth = (count($visibleFields) > 5) ? 'w-150px' : '';
?>
<form class='form-condensed' method='post' target='hiddenwin' action='<?php echo inLink('classBatchEdit');?>'>
	<table class='table table-form'>
		<thead>
			<tr class='text-center'>
				<th class='w-auto'><?php echo $lang->kevinuser->id;?></th>
				<th class='w-auto'><?php echo $lang->kevinuser->role;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->classify1;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->classify2;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->classify3;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->classify4;?> </th>
				<th class='w-auto'><?php echo $lang->kevinuser->payrate;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->hourFee;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->start;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->end;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->jobRequirements;?></th>
				<th class='w-auto'><?php echo $lang->kevinuser->remarks;?> </th>
			</tr>
		</thead>
		<?php foreach ($classIDList as $classID):?>
			<tr class='text-center'>
				<td><?php echo sprintf('%03d', $classID) . html::hidden("classIDList[$classID]", $classID);?></td>
				<td class='<?php echo zget($visibleFields, 'role', 'hidden')?>'>
					<?php	echo html::select("role[$classID]", $roleList, $classes[$classID]->role, "class='form-control chosen pull-right'");?>
				</td>
				<td class='<?php echo zget($visibleFields, 'classify1', 'hidden')?>'>
					<?php echo html::select("classify1[$classID]", $classify1List, $classes[$classID]->classify1, "class='form-control chosen'");?>
				</td>
				<td class='<?php echo zget($visibleFields, 'classify2', 'hidden')?>'>
					<?php echo html::select("classify2[$classID]", $classify2List, $classes[$classID]->classify2, "class='form-control chosen'");?>
				</td>
				<td class='<?php echo zget($visibleFields, 'classify3', 'hidden')?>'>
					<?php echo html::select("classify3[$classID]", $classify3List, $classes[$classID]->classify3, "class='form-control chosen'");?>
				</td>
				<td class='<?php echo zget($visibleFields, 'classify4', 'hidden')?>'><?php echo html::input("classify4[$classID]", $classes[$classID]->classify4, "class='form-control' autocomplete='off'");?></td>
				<td class='<?php echo zget($visibleFields, 'payrate', 'hidden')?>'><div id="percentTip<?php echo $classID?>" class="required-wrapper" style="line-height: 30px;margin-left: -15px;"></div><input name="payrate[<?php echo $classID;?>]" id="payrate<?php echo $classID?>" value="<?php echo $classes[$classID]->payrate * 100;?>" class="form-control" type="number" onkeyup="value = value.replace(/[^\d]/g, '');if (value.length == 0)
					$('#percentTip<?php echo $classID;?>').html('');" oninput="if(value.length>3) value=value.slice(0,3); if(value.length>0){$('#percentTip<?php echo $classID;?>').html('%');}else{$('#percentTip<?php echo $classID;?>').html('');}" step="1" max="100" min="1"></td>
				<td class='<?php echo zget($visibleFields, 'hourFee', 'hidden')?>'><input name="hourFee[<?php echo $classID;?>]" id="hourFee" value="<?php echo $classes[$classID]->hourFee;?>" class="form-control" type="number" onkeyup="value = value.replace(/[^\d]/g, '')" step="0.01" min="0"></td>
				<td><?php echo html::input("start[$classID]", $classes[$classID]->start, "id='start".$classID."' class='form-control form-date' onchange='setStartDate(this.id, this.value)'");?></td>
				<td><?php echo html::input("end[$classID]", $classes[$classID]->end, "id='end".$classID."' class='form-control form-date' onchange='setEndDate(this.id, this.value)'");?></td>
				<!--<td class='<?php echo zget($visibleFields, 'monthFee', 'hidden')?>'><?php echo html::input("monthFee[$classID]", $classes[$classID]->monthFee, "class='form-control' autocomplete='off'");?></td>-->
				<td class='<?php echo zget($visibleFields, 'jobRequirements', 'hidden')?>'><?php echo html::input("jobRequirements[$classID]", $classes[$classID]->jobRequirements, "class='form-control' autocomplete='off'");?></td>
				<td class='<?php echo zget($visibleFields, 'remarks', 'hidden')?>'>    <?php echo html::textarea("remarks[$classID]", htmlspecialchars($classes[$classID]->remarks), "rows='1' class='form-control autosize'");?></td>
			</tr>
		<?php endforeach;?>
		<tr><td colspan='<?php echo count($visibleFields) + 6?>' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
	</table>
</form>
<script >
	$("li[data-id='kevinuser']").addClass('active');
	$("li[data-id='classlist']").addClass('active');
	window.onload = function () {
<?php foreach ($classIDList as $classID):?>
			if ($("#payrate<?php echo $classID;?>").val() > 0)
				$('#percentTip<?php echo $classID;?>').html('%');
<?php endforeach;?>
	}
</script>
<script>
	function setStartDate(id, value) {
		var val = value.split('-');
		if(value.length ==10 && val.length==3 && val[0].length==4 && val[1].length==2 && val[2].length==2) {
			
		}else if(value < 0 || value == "" || value.length !=7 || val.length!=2 || val[0].length!=4 || val[1].length!=2) {
			alert("<?php echo $lang->kevinuser->dateError?>");
		}else{
			$("#"+id).val(value+'-01');
		}
	}
	
	function setEndDate(id, value) {
		var val = value.split('-');
		if(value.length ==10 && val.length==3 && val[0].length==4 && val[1].length==2 && val[2].length==2) {
			
		}else if(value < 0 || value == "" || value.length !=7 || val.length!=2 || val[0].length!=4 || val[1].length!=2) {
			alert("<?php echo $lang->kevinuser->dateError?>");
		}else{
			var date = new Date(value);
			date.setMonth(date.getMonth()+1);
			date.setDate(date.getDate()-1);
			$("#"+id).val(value+'-'+date.getDate());
		}
	}
</script>
<?php include '../../kevincom/view/footer.html.php';?>
