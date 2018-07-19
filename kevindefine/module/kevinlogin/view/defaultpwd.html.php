<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
 $this->moduleName= "kevinuser";//第一级标签
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php include '../../common/view/tablesorter.html.php'; 
?>
<div class='text-left'>
<table><tr>
<td valign="top" width='400px' >
	<table align='center' class='table table-hover table-striped tablesorter' id='pwdList'>			
		<thead>
			<tr class='text-center'>
				<th class='w-id'><?php echo $lang->kevinlogin->serialNum;?></th>
				<th class='text-left'><?php echo $lang->kevinlogin->PasswordSource; ?></th>
				<th class='w-id'><?php echo $lang->actions; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = -10;$index=0;
			$pagid = $pager->pageID;
			$indexRec = ($pager->pageID-1) * $pager->recPerPage+1;
			foreach ($passwords as $tempId => $pwd):$i+=1;$index+=1;
				?>
				<tr class='text-center'>
					<td class='text-center'><?php echo $indexRec; $indexRec++;?></td>
					<td class='text-left'>
						<?php echo $pwd->source; ?>
					</td>
					<td class='text-center'>
						<?php common::printIcon('kevinlogin', 'delete', "id=$pwd->id", '', 'list', 'remove', 'hiddenwin'); ?>
					</td>
				</tr>
			<?php endforeach; ?>

		</tbody>
	</table>
	<?php if (count($passwords)): ?>		
		<?php $pager->show(); ?>				

	<?php endif; ?>
</td>
<td  valign="top"  width='20px'>
</td>
<td  valign="top"  width='400px'>
	<form class='form-condensed' method='post' target='hiddenwin'>
		<table align='center' class='table table-form' id='pwdList'>			
			<thead>
				<tr class='text-center'>
					<th class='w-id'><?php echo $lang->kevinlogin->serialNum;?></th>
					<th class='text-left'><?php echo $lang->kevinlogin->PasswordSource; ?></th>
					<th class='w-id'><?php echo $lang->actions; ?></th>
				</tr>
			</thead>
			<tbody>
		
				<?php for($j=-10;$j<-6;$j++){$index+=1;?>
				<tr class='text-center'>
					<td class='text-center'><i class="icon-plus-sign"></i></td>
					<td class='text-center'>
						<?php
						echo html::input("source[$j]", '', "class='form-control'") . html::hidden("pwdList[$j]", $j);
						?>
					</td>
				</tr>
				<?php }?>
				<tr class='text-center'>
					<td colspan="3" class='text-center'><?php echo html::submitButton('', '', 'btn-primary') ." ". html::backButton(); ?></td>
				</tr>
			</tbody>
		</table>
	</form>
</td>
</tr>
</table>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>