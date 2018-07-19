<?php
/**
 * The create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/monthpicker.html.php';?>
<div class='container mw-700px'>
    <div id='titlebar'>
        <div class='heading'>
            <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
            <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $title;?></strong>
        </div>
    </div>
    <div class="main" style="min-height: 400px;">  
        <form target='hiddenwin' class='form-condensed' method='post' class='mt-10px' id='dataform'>
            <table class='table table-form' style='width:100%'>
                <tr>
                    <th class="w-80px"><?php echo $lang->kevinuser->account;?></th>
					<td><div class="required required-wrapper"></div><?php if($func == 'edit'){
						echo $record->account. html::hidden("account", $record->account);			
					}else{
						echo html::select('account', $accounts, isset($record->account)?$record->account:'', "class='form-control chosen' placeholder='" . $lang->kevinuser->accountPlaceholder . "'");
					}
					?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->class;?></th>
                    <td><div class="required required-wrapper"></div><?php echo html::select('class', $classpairs, isset($record->class)?$record->class:'', "class='form-control chosen' placeholder='" . $lang->kevinuser->classPlaceholder . "'");?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->start;?></th>
                    <td><div class="required required-wrapper"></div><?php echo html::input('start', isset($record->start)?$record->start:'', "class='form-control form-date' placeholder='" . $lang->kevinuser->startPlaceholder . "'  onchange='setStartDate(this.value)'");?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->end;?></th>
                    <td><div class="required required-wrapper"></div><?php echo html::input('end', !empty($record->end)?$record->end:$this->config->kevinuser->endDate, "class='form-control form-date' placeholder='" . $lang->kevinuser->endPlaceholder . "'  onchange='setEndDate(this.value)'");?></td>
                </tr>
                <tr>
                    <td colspan='2' class='text-center'>
						<?php echo html::submitButton() . html::backButton();?>
                    </td>
                </tr>
            </table>
        </form>
        <div style="margin-top: 50px;">
			<?php
			if ($func == "edit") {
				include '../../common/view/action.html.php';
			}
			?>
        </div>    
    </div>
</div>
</div>
<script>
	function setStartDate(value) {
		var val = value.split('-');
		if(value.length ==10 && val.length==3 && val[0].length==4 && val[1].length==2 && val[2].length==2) {
			
		}else if(value < 0 || value == "" || value.length !=7 || val.length!=2 || val[0].length!=4 || val[1].length!=2) {
			alert("<?php echo $lang->kevinuser->dateError?>");
		}else{
			$("#start").val(value+'-01');
		}
	}
	
	function setEndDate(value) {
		var val = value.split('-');
		if(value.length ==10 && val.length==3 && val[0].length==4 && val[1].length==2 && val[2].length==2) {
			
		}else if(value < 0 || value == "" || value.length !=7 || val.length!=2 || val[0].length!=4 || val[1].length!=2) {
			alert("<?php echo $lang->kevinuser->dateError?>");
		}else{
			var date = new Date(value);
			date.setMonth(date.getMonth()+1);
			date.setDate(date.getDate()-1);
			$("#end").val(value+'-'+date.getDate());
		}
	}
</script>
<?php include '../../kevincom/view/footer.html.php';?>