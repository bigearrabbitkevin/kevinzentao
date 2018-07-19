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
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<style>
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
        cursor: not-allowed;
        background-color: #fff;
    }
</style>
<div class='container mw-700px'>
    <div id='titlebar'>
        <div class='heading'>
            <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
            <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $title;?></strong>
        </div>
    </div>
    <div class="main">
        <fieldset>
            <legend>
                <h3><?php echo $lang->kevinuser->recordview;?></h3>
            </legend>
            <form class='form-condensed mw-700px' method='post' target='hiddenwin' id='dataform'>
                <table align='center' class='table table-form'>
                    <tr>
                        <th><?php echo $lang->kevinuser->account;?></th>
                        <td><?php echo $accounts[$record->account];?></td>
                        <th><?php echo $lang->kevinuser->dept;?></th>
                        <td><?php echo  $record->name."(".$record->dept.")";?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->kevinuser->class;?></th>
                        <td><?php echo $record->classname."(".$record->class.")";?></td>
                        <th><?php echo $lang->kevinuser->worktype;?></th>
                        <td><?php echo $lang->kevinuser->worktypeList[$record->worktype];?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->kevinuser->start;?></th>
                        <td><?php echo $record->start;?></td>
                        <th><?php echo $lang->kevinuser->end;?></th>
                        <td><?php echo $record->end;?></td>
                    </tr>

                    <tr><th></th><td><?php echo html::backButton();?></td></tr>
                </table>
            </form>
        </fieldset>
		<?php include '../../common/view/action.html.php';?>
    </div>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
