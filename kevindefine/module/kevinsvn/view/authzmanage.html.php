<?php
/**
 * The manage privilege view of group module of ZenTaoPMS.
 *
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinsvn
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<div class='container mw-900px'>
	<form class='form-condensed' method='post' target='hiddenwin'>
		<table class='table table-form'>
			<tr class='text-center'>
				<td class='strong'><?php echo $lang->kevinsvn->replist; ?></td>
				<td class='strong'><?php echo $lang->kevinsvn->dirtree; ?></td>
				<td class='strong'><?php echo $lang->kevinsvn->account; ?></td>
				<td class='strong'><?php echo $lang->kevinsvn->authz; ?></td>
			</tr>
			<tr>
				<td class='w-p30'><?php echo html::select('rep', $reps, '', "onchange='getdirarray(this.value)' size=\"10\" style='height:500px' class='form-control'"); ?></td>
				<td class='w-p30' id='dirarray'><?php echo html::select('dirtree', $dirarray, '', "size=\"10\" style='height:500px' class='form-control'"); ?></td>
				<td class='w-p30' id='accs'><?php echo html::select('acc[]', $accounts, '', "onchange='getauthz(this.value)' size=\"10\" style='height:500px' class='form-control' multiple=multiple"); ?></td>
				<td class='w-p30' id='authzs'><?php echo html::radio('authz', $lang->kevinsvn->authzEnum,'', "size=\"20\"",'block'); ?></td>
			</tr>
			<tr>
				<td class='text-center' colspan='3'>
					<?php
					echo html::submitButton($lang->save);
					echo html::linkButton($lang->goback, $this->createLink('kevinsvn', 'replist'));
					echo html::hidden('foo'); // Just make $_POST not empty..
					?>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
<script language='Javascript'>
	function getdirarray(repid){
		link = createLink('kevinsvn', 'ajaxgetdir', 'repid=' + repid);
		$('#dirarray #dirtree').remove();
//		$('.' + module + 'Actions').find("option[value='"+method+"']").attr('selected',true);
		$('#dirarray').load(link, function() {
			$('#dirarray').chosen(defaultChosenOptions);
		});
	}
	function getauthz(windowsID){
		

		var repid=$('#rep').val();
		var dir=$('#dirtree').val();

		windowsID=windowsID.replace(/\-/g, "henggang");
		dir = dir.replace(/\//g, "xiegang");
		link = createLink('kevinsvn', 'ajaxgetauthz', 'repid=' + repid+'&dir='+dir+'&windowsID='+windowsID);
//alert(link);
//		$('.' + module + 'Actions').find("option[value='"+method+"']").attr('selected',true);
//alert(dir);
		$('#authzs #authz').remove();
//		alert(link);

		$('#authzs').load(link, function() {
			$('#authz').chosen(defaultChosenOptions);
		});
	}
</script>

