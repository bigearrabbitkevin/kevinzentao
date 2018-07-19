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
<?php include '../../kevinhours/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php'; ?>
<?php include '../../common/view/form.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class="container">
    <form class='form' method='post' target='hiddenwin' id='dataform'>
        <div class='row-table'>
            <table width = '100%' cellpadding="5">
                <tr>
                    <th><?php echo $lang->kevinplan->planName;?></th>
                    <td><?php echo html::input('name', $planItem->name,  "class='form-control'");?></td>
                    <th><?php echo $lang->kevinplan->charger;?></th>
                    <td><?php echo html::select('charger',$userlist, $planItem->charger,  "class='form-control chosen'");?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinplan->dept;?></th>
                    <td colspan='3'><?php echo html::select('dept',$deptlist,$planItem->dept,  "class='form-control chosen'");?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinplan->planYear;?></th>
                    <td><?php echo html::input('planYear', $planItem->planYear,  "class='form-control'");?></td>

                    <th><?php echo $lang->kevinplan->hoursPlan;?></th>
                    <td><?php echo html::input('hoursPlan', $planItem->hoursPlan,  "class='form-control'");?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinplan->startDate;?></th>
                    <td><?php echo html::input('startDate',$planItem->startDate,  "class='form-control form-date' ");?></td>

                    <th><?php echo $lang->kevinplan->endDate;?></th>
                    <td><?php echo html::input('endDate', $planItem->endDate,  "class='form-control form-date' ");?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinplan->status;?></th>
                    <td><?php echo html::select('status', $lang->kevinplan->statusList,$planItem->status,  "class='form-control'");?></td>
                    <th><?php echo $lang->kevinplan->IsFinished;?></th>
                    <td><?php echo html::radio('IsFinished', $lang->kevinplan->yesOrNo,$planItem->IsFinished,  "");?></td>
                </tr>
                <tr>
				    <th><?php echo $lang->kevinplan->member;?></th>
					<td colspan='3'>
                        <div class="chosen-single" tabindex="-1">
					   <?php
					   $memberarr=explode(' ',$planItem->members);
					   //array_unshift($userlist);
					   echo html::select('members[]',$userlist, $memberarr, "multiple class='form-control chosen'");
                       ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan='4' style="height:200px;" class="text-center"><?php echo html::submitButton($lang->save);?></td>
                </tr>
            </table>
        </div>
    </form>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
