<form id='searchform' method="post" class='form-condensed'>
	<table width = 100%>
		<tr>
			<th class="nobr w-30px"><?php echo $lang->kevinuser->class;?></th>
			<td style="max-width:120px"><?php echo html::select("class", $classpairs, $class, "class='form-control chosen pull-right' ");?></td>
		</tr>
		<tr>
			<th class="nobr w-30px"><?php echo $lang->kevinuser->account;?></th>
			<td style="max-width:160px"><?php echo html::input("account",  $account, "class='form-control' ");?></td>
		</tr>
		<tr>
			<th class="nobr w-30px"><?php echo $lang->kevinuser->dept;?></th>
			<td style="max-width:120px"><?php echo html::input("dept", $dept, "class='form-control' ");?></td>
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