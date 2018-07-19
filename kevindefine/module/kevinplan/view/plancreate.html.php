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
 cellpadding="5"
 */
?>
<?php include '../../kevinhours/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php'; ?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='titlebar'>
		<div class='heading'>
			<strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->kevinplan->plancreate;?></strong>
		</div>
</div>
<div class='main'>
    <div class='row-table'>
<form class='form' method='post' target='hiddenwin' id='dataform' >

			<table class='table table-form table-borderless' width = '100%'  cellpadding="5">			 
				<tr>
					<th><?php echo $lang->kevinplan->planName;?></th>
                    <td><?php echo html::input('name', '',  "class='form-control'");?></td>
					
					<th><?php echo $lang->kevinplan->charger;?></th>
                    <td><?php echo html::select('charger',$userlist, $this->app->user->account,  "class='form-control chosen'");?></td>
				</tr>
             <tr>
					<th><?php echo $lang->kevinplan->dept;?></th>
                    <td><?php echo html::select('dept',$deptlist,$this->app->user->dept,  "class='form-control chosen'");?></td>
					
					<th><?php echo $lang->kevinplan->planYear;?></th>
                    <td><?php echo html::input('planYear', date('Y'),  "class='form-control'");?></td>
				</tr>
                <tr>
					<th><?php echo $lang->kevinplan->hoursPlan.'('.$this->lang->kevinplan->hoursunit.')';?></th>
                    <td><?php echo html::input('hoursPlan', 2000,  "class='form-control'");?></td>
					
					<th><?php echo $lang->kevinplan->status;?></th>
                    <td><?php echo html::select('status', $lang->kevinplan->statusList,'',  "class='form-control'");?></td>
				</tr>
                <!--<tr>
					<th><?php echo $lang->kevinplan->IsFinished;?></th>
                    <td><?php echo html::radio('IsFinished', $lang->kevinplan->yesOrNo,'',  "");?></td>
				</tr>-->
                <tr>
					<th><?php echo $lang->kevinplan->startDate;?></th>
					<td><?php echo html::input('startDate', date('Y-m-d'),  "class='form-control form-date' ");?></td>
					
					<th><?php echo $lang->kevinplan->endDate;?></th>
					<td ><?php echo html::input('endDate', date('Y-m-d',strtotime("+1 year")),  "class='form-control form-date' ");?></td>
				</tr> 
				<tr>
                    <td colspan='4' style="height:180px;" class="text-center"><?php echo html::submitButton($lang->save);?></td>
                </tr>
			</table>
</div>
</form>

</div>

