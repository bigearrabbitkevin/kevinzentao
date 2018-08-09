<?php include '../../kevincom/view/header.html.php'; ?>
<?php 
//types
$types['0'] = '检查';
$types['1'] = '移除BOM';
$basedir="";
$AutoRemoveBom = '0';

$isPost = 0;
if($_POST) {
	$isPost = 1;
	if (isset ( $_POST ['dir'] )) { //config the basedir
		$basedir = $_POST ['dir'];
	} 
	if (isset ( $_POST ['AutoRemoveBom'] )) { //config the basedir
		$AutoRemoveBom = $_POST ['AutoRemoveBom'];
		$AutoRemoveBom = ($AutoRemoveBom == '1'? '1':'0');
	} 
}
 ?>		
<div class='main text-left' style="overflow:auto; ">
	<form method='post' id='uploadForm'>
		<table class="table active-disabled">
			<tbody>
			<tr class="search-field ">
				<td class="text-right w-150px">请输入要检测的路径</span>
				</td>
				<td class="w-auto"><?php echo html::input("dir", $basedir, "class='form-control' placeholder = 'Server Folder ,Not local Folder'"); ?></td>

				<td class="w-160px"><?php echo html::radio('AutoRemoveBom', $types, $AutoRemoveBom);?></td>
				<td class="w-70px">
					<div class='input-group-btn'><?php echo html::submitButton("提交"); ?></div>
				</td>
				<td class="w-auto">
				</td>
			</tr>

			</tbody>
		</table>
	</form>
</div>

<?php

if ( $basedir) { //config the basedir
	$bomcheck = $app->loadClass('kvbomcheck');
	$bomcheck->Initial();// empty list
	$bomcheck->AutoRemoveBom = $AutoRemoveBom;// auto remove Bom
	
	//run main check function
	$bomcheck->checkdir ( $basedir);
	
	//checo result
	if(	$AutoRemoveBom) echo "Auto Remove UTF-8 BOM";
	else echo "Only Check UTF-8 BOM";
	echo " for Folder \"$basedir\"<br>\n ";
	
	//counts
	echo "{$bomcheck->CountChecked} : all files.<br>";
	$count = count($bomcheck->BomFileList);
	echo "$count : UTF-8 Files : <br>\n";
	//show files
	foreach($bomcheck->BomFileList as $fileItem){
		$fileItem = str_replace("/", "\\",$fileItem);// replace tolocal
		echo "<p>filename: $fileItem </p>\n";	
	}
} else if ($isPost){
	echo "Please input folder: Server Folder ,Not local Folder！";
}
	
?>
<?php include '../../kevincom/view/footer.html.php'; ?>
