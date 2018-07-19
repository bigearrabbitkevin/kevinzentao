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
<?php include '../../kevincom/view/header.html.php'; ?>
<?php include '../../common/view/datepicker.html.php'; ?>
<style>
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
        cursor: not-allowed;
        background-color: #fff;
    }
</style>
<div class='container mw-700px'>
    <div id='titlebar'>
        <div class='heading'>
            <span class='prefix'><?php echo html::icon($lang->icons['user']); ?></span>
            <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']); ?></small> <?php echo $title; ?></strong>
        </div>
    </div>
    <div class="main">
        <fieldset>
            <legend>
                <h3><?php echo $lang->kevinuser->classview; ?></h3>
            </legend>
            <table align='center' class='table table-form'>
                <tr>
                    <th><?php echo $lang->kevinuser->id; ?></th>
                    <td><?php echo $class->id; ?></td>
                    <th><?php echo $lang->kevinuser->role; ?></th>
                    <td><?php echo $class->role; ?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->classify1; ?></th>
                    <td><?php echo $class->classify1; ?></td>
                    <th><?php echo $lang->kevinuser->classify2; ?></th>
                    <td><?php echo $class->classify2; ?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->classify3; ?></th>
                    <td><?php echo $class->classify3; ?></td>
                    <th><?php echo $lang->kevinuser->classify4; ?></th>
                    <td><?php echo $class->classify4; ?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->classname; ?></th>
                    <td><?php echo $class->classname."(".$class->id.")"; ?></td>
                    <th><?php echo $lang->kevinuser->payrate; ?></th>
                    <td><?php echo $class->payrate*100 . '%'; ?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->hourFee; ?></th>
                    <td><?php echo $class->hourFee; ?></td>
                    <th><?php echo $lang->kevinuser->conversionFee; ?></th>
                    <td><?php echo $class->conversionFee; ?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->start; ?></th>
                    <td><?php echo $class->start; ?></td>
                    <th><?php echo $lang->kevinuser->end; ?></th>
                    <td> <?php echo $class->end; ?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->jobRequirements; ?></th>
                    <td><?php echo $class->jobRequirements; ?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->remarks; ?></th>
                    <td><?php echo $class->remarks; ?></td>
                </tr>

                <tr><th></th><td><?php echo html::backButton(); ?></td></tr>
            </table>
        </fieldset>
        <?php include '../../common/view/action.html.php';?>
    </div>
    
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
