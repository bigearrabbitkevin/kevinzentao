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
<form class='form-condensed' method='post' target='hiddenwin' action='<?php echo inLink('recordBatchEdit');?>'>
	<table class='table table-form'>
		<thead>
			<tr class='text-center'>
				<th class='w-auto'><?php echo $lang->kevinuser->id;?></th>
				<th class='w-auto'><?php echo $lang->kevinuser->account;?></th>
				<th class='w-auto'><?php echo $lang->kevinuser->class;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->start;?> <span class='required'></span></th>
				<th class='w-auto'><?php echo $lang->kevinuser->end;?> <span class='required'></span></th>
			</tr>
		</thead>
		<?php foreach ($recordIDList as $recordID):
			?>
			<tr class='text-center'>
				<td><?php echo sprintf('%03d', $recordID) . html::hidden("recordIDList[$recordID]", $recordID);?></td>
				<td><?php echo $records[$recordID]->account  . html::hidden("account[$recordID]", $records[$recordID]->account);?></td>
				<td><?php echo html::select("class[$recordID]", $classpairs, $records[$recordID]->class, "class='form-control chosen'");?></td>
				<td><?php echo html::input("start[$recordID]", $records[$recordID]->start, "id='start".$recordID."' class='form-control form-date' onchange='setStartDate(this.id, this.value)'");?></td>
				<td><?php echo html::input("end[$recordID]", $records[$recordID]->end, "id='end".$recordID."' class='form-control form-date' onchange='setEndDate(this.id, this.value)'");?></td>
			</tr>
		<?php endforeach;?>
		<tr><td colspan='<?php echo count($visibleFields) + 6?>' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
	</table>
</form>
<script>
	$("li[data-id='recordlist']").addClass('active');
	$("li[data-id='kevinuser']").addClass('active');
	function setStartDate(id, value) {
		var val = value.split('-');
		if(value.length ==10 && val.length==3 && val[0].length==4 && val[1].length==2 && val[2].length==2) {
			
		}else if(value < 0 || value == "" || value.length !=7 || val.length!=2 || val[0].length!=4 || val[1].length!=2) {
			alert("<?php echo  $lang->kevinuser->dateError?>");
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
