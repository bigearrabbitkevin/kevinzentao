<?php include '../../kevincom/view/header.html.php'; ?>
<?php 
$basedir="";

if($_POST) {
	if (isset ( $_POST ['dir'] )) { //config the basedir
		$basedir = $_POST ['dir'];
	} 
}
 ?>		
<div class='main' style="overflow:auto; ">
	<div>
		<div style="border: 0">
			<form method='post' id='uploadForm'>
				<table class="table active-disabled">
					<tbody>
					<tr class="search-field ">
						<td class="text-right w-150px">
							<span id="searchgroup1"><strong style="line-height: 2.5;">请输入要检测的路径</strong></span>
						</td>
						<td class="w-200px">
							<input type='text' name='dir' class='form-control' value="<?php echo $basedir; ?>"/>
						</td>
						<td class="w-70px">
							<div class='input-group-btn'><?php echo html::submitButton("提交"); ?></div>
						</td>
					</tr>

					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>

<?php

if ( $basedir) { //config the basedir
	global $app;
	$bomcheck = $app->loadClass('kvbomcheck');

	$bomcheck->checkdir ( $basedir );
}
?>
<?php include '../../kevincom/view/footer.html.php'; ?>
