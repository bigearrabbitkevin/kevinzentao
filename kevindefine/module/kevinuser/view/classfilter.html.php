<form id='searchform' method="post" class='form-condensed'>
    <table width = 100%>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->role;?></th>
            <td style="max-width:160px"><?php echo html::select("role", $roleList, $role, "class='form-control chosen pull-right' ");?></td>
        </tr>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->classify1;?></th>
            <td style="max-width:120px"><?php echo html::select("classify1", $classify1List, $classify1, "class='form-control chosen pull-right' ");?></td>
        </tr>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->classify2;?></th>
            <td style="max-width:160px"><?php echo html::select("classify2", $classify2List, $classify2, "class='form-control chosen pull-right' ");?></td>
        </tr>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->classify3;?></th>
            <td style="max-width:120px"><?php echo html::select("classify3", $classify3List, $classify3, "class='form-control chosen pull-right' ");?></td>
        </tr>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->delete;?></th>
            <td style="max-width:120px"><label class="checkbox-inline"><input name="deleted" value="1" id="deleted" type="checkbox" <?php if(!empty($deleted)):?> checked="checked"<?php endif;?>><?php echo $lang->kevinuser->deleted;?></label></td>
        </tr>
        <tr>
            <td  class="text-center" colspan="2"><?php echo html::submitButton('搜索');?></td>
        </tr>
    </table>	
</form>