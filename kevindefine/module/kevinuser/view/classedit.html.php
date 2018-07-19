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
<?php js::set('holders', $lang->user->placeholder);?>

<div class='container mw-700px'>
    <div id='titlebar'>
        <div class='heading'>
            <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
            <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $title;?></strong>
        </div>
    </div>
    <div class="main">  
        <form class='form-condensed mw-700px' method='post' target='hiddenwin' id='dataform'>
            <table align='center' class='table table-form'> 
                <tr>
                    <th colspan="1"><?php echo $lang->kevinuser->role;?></th>
                    <td colspan="3">
						<?php echo html::radio('role', $roleList, isset($class->role) ? $class->role : $lang->kevinuser->roleChoice);	?>
					</td>
                </tr>
				<tr>
                    <th colspan="1"><?php echo $lang->kevinuser->classify1;?></th>
                    <td colspan="3"><?php echo html::radio('classify1', $classify1List, isset($class->classify1) ? $class->classify1 : $lang->kevinuser->classify1Choice);?>
					</td>
                </tr>
                <tr>
                    <th colspan="1"><?php echo $lang->kevinuser->classify2;?></th>
                    <td colspan="3"><?php echo html::radio('classify2', $classify2List, isset($class->classify2) ? $class->classify2 : $lang->kevinuser->classify2Choice);?>
					</td>
                </tr>
				<tr>
                    <th colspan="1"><?php echo $lang->kevinuser->classify3;?></th>
                    <td colspan="3"><?php echo html::radio('classify3', $classify3List, isset($class->classify3) ? $class->classify3 : $lang->kevinuser->classify3Choice);?>
					</td>
                </tr>
                <tr>
                    <th colspan="1"><?php echo $lang->kevinuser->classify4;?></th>
                    <td colspan="3"><div class="required-wrapper"></div><?php echo html::input('classify4', isset($class->classify4)?$class->classify4:'', "class='form-control' placeholder='" . $lang->kevinuser->classify4Placeholder . "'");?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->payrate;?></th>
                    <td><div id="percentTip" class="required required-wrapper" style="line-height: 30px;padding-left: 35px;"></div><input name="payrate" id="payrate" value="<?php echo empty($class->payrate) ? '' : $class->payrate * 100;?>" class="form-control" placeholder="<?php echo $lang->kevinuser->payratePlaceholder;?>" type="number" onkeyup="value = value.replace(/[^\d]/g, '');if (value.length == 0)
								$('#percentTip').html('');" oninput="if(value.length>3) value=value.slice(0,3); if(value.length>0){$('#percentTip').html('%');}else{$('#percentTip').html('');}" step="1" max="100" min="1"></td>
                    <th><?php echo $lang->kevinuser->hourFee;?></th>
                    <td><div class="required required-wrapper"></div><input name="hourFee" id="hourFee" value="<?php echo isset($class->hourFee)?$class->hourFee:'';?>" class="form-control" placeholder="<?php echo $lang->kevinuser->hourFeePlaceholder;?>" type="number"  onkeyup="value = value.replace(/[^\d]/g, '')" step="0.01" min="0"></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->start;?></th>
                    <td><div class="required required-wrapper"></div><?php echo html::input('start', isset($class->start)?$class->start:'', "class='form-control form-date' placeholder='" . $lang->kevinuser->startPlaceholder . "' onchange='setStartDate(this.value)'");?></td>
                    <th><?php echo $lang->kevinuser->end;?></th>
                    <td><div class="required required-wrapper"></div><?php echo html::input('end', !empty($class->end)?$class->end:$this->config->kevinuser->endDate, "class='form-control form-date' placeholder='" . $lang->kevinuser->endPlaceholder . "'  onchange='setEndDate(this.value)'");?></td>
                </tr>
                <tr>
                    <th colspan="1"><?php echo $lang->kevinuser->jobRequirements;?></th>
                    <td colspan="3"><?php echo html::input('jobRequirements', isset($class->jobRequirements)?$class->jobRequirements:'', "class='form-control' placeholder='" . $lang->kevinuser->jobRequirementsPlaceholder . "'");?></td>
                </tr>
                <tr>
                    <th colspan="1"><?php echo $lang->kevinuser->remarks;?></th>
                    <td colspan="3"><?php echo html::textarea('remarks', isset($class->remarks)?$class->remarks:'', "class='form-control' style='height:160px;' placeholder='" . $lang->kevinuser->remarksPlaceholder . "'");?></td>
                </tr>

                <tr><th colspan="2"></th><td><?php echo html::submitButton() . html::backButton();?></td></tr>
            </table>
        </form>
        <div style="margin-top: 20px;">
			<?php
			if ($func == "edit") {
				include '../../common/view/action.html.php';
			}
			?>
        </div>    
    </div>
</div>
<script >
	window.onload = function () {
		if ($("#payrate").val() > 0)
			$('#percentTip').html('%');
	}
	
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
