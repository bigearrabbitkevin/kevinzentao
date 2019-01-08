<?php
/**
 * The view file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinsvn
 * @link        http://www.zentao.net
 */
?>
<?php include '../../kevincom/view/header.html.php';?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix'><strong><?php echo $repid;?></strong></span>
		<span class='prefix' title='id'><?php echo $lang->kevinsvn->rep . " > " . $lang->kevinsvn->reparse . " > ".$lang->kevinsvn->repaccreate;?></span>
	</div>
</div>
<div class='row-table'>
	<?php if($dirs){ ?>
	<form class='form' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
		<table class='table table-form table-borderless' width = '100%' cellpadding="5"> 
			<?php foreach($dirs as $dir=>$account): ?>
				<tr>
					<th class='w-100px'><?php echo $dir;?></th>
					<td class='w-500px'>
						<?php if(is_array($account)){foreach ($account as $acc):?>
							<div class="chosen-container chosen-container-multi">
								<ul class="chosen-choices">
									<li class="search-choice"><span><?php echo $accounts[$acc];?></span></li>
									<li class="search-field"><input value=" " class="" autocomplete="off" style="width:0px;" type="text"></li>
								</ul>
							</div>
						<?php endforeach;$final=array_diff_key($accounts,$account);}else $final=$accounts;?>
					<?php echo html::select("authzacc[$dir][]",$final,'',"class='form-control chosen' multiple");?></td>	
				</tr> 
			<?php endforeach;?>
			<tr><td colspan='2' class="text-center"><?php echo $lang->kevinsvn->repnote;?></td></tr>
			<tr>
                <td colspan='2' style='height:150px;' class="text-center"><?php echo html::submitButton($lang->kevinsvn->savetodb);?></td>
            </tr>
		</table>
	</form><?php }else echo $lang->kevinsvn->nodiravaliable; ?>
</div>
