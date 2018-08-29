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
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('holders', $lang->user->placeholder);?>

<div class='container mw-700px'>
    <div id='titlebar'>
        <div class='heading'>
            <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
            <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $title;?></strong>
        </div>
    </div>
    <div class="main" style="min-height: 450px;">  
        <form class='form-condensed mw-700px' method='post' target='hiddenwin' id='dataform'>
            <table align='center' class='table table-form'> 
                <tr>
                    <th class='w-80px'><?php echo $lang->kevinuser->deptParent;?></th>
                    <td><div class="" style="line-height: 30px;padding-left: 35px;"></div><?php echo html::select('parent', $optionMenu, isset($dept->parent)?$dept->parent:'', "class='form-control chosen'");?></td>
                </tr>
                <tr>
                    <th class='w-80px'><?php echo $lang->kevinuser->deptName;?></th>
                    <td><div class="required required-wrapper" style="line-height: 30px;padding-left: 35px;"></div><?php echo html::input('name', !empty($dept->name) ? $dept->name : '', "class='form-control' placeholder='" . $lang->kevinuser->deptNamePlaceholder . "'");?></td>
                </tr>
                <tr>
                    <th class='w-80px'><?php echo $lang->kevinuser->manager;?></th>
                    <td><?php echo html::select('manager', $users, !empty($dept->manager) ? $dept->manager : '', "class='form-control chosen'", true);?></td>
                </tr>  
				<tr>
                    <th class='w-80px'><?php echo $lang->kevinuser->deptgroup;?></th>
					<td colspan='3'><?php echo html::select('group[]', $groups, isset($dept->group)?$dept->group:'', 'size=3 multiple=multiple class="form-control chosen"');?></td>
                </tr>  
                <tr>
                    <th colspan="1"><?php echo $lang->kevinuser->email;?></th>
                    <td colspan="3"><?php echo html::input('email', !empty($dept->email) ? $dept->email : '', "class='form-control' placeholder='" . $lang->kevinuser->emailPlaceholder . "'");?></td>
                </tr>
                <tr>
                    <th colspan="1"><?php echo $lang->kevinuser->code;?></th>
                    <td colspan="3"><?php echo html::input('code', !empty($dept->code) ? $dept->code : '', "class='form-control' placeholder='" . $lang->kevinuser->codePlaceholder . "'");?></td>
                </tr>
				<tr>
                    <th colspan="1"><?php echo $lang->kevinuser->order;?></th>
                    <td colspan="3"><div class="required required-wrapper" style="line-height: 30px;padding-left: 35px;"></div><?php echo html::input('order', !empty($dept->order) ? $dept->order : '', "class='form-control' placeholder='" . $lang->kevinuser->orderPlaceholder . "'");?></td>
                </tr>
                <tr><th></th><td><?php echo html::submitButton() . html::backButton();?></td></tr>
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
<?php include '../../kevincom/view/footer.html.php';?>
